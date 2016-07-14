<?php
session_start();
require_once("../mysql-lib.php");
require_once("../debug.php");
require_once("researcher-validation.php");
$pageName = "saq-editor";
$columnName = array('SAQID', 'Question', 'Points', 'Edit');

try {
    $conn = db_connect();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['metadataUpdate'])) {
            $metadataUpdate = $_POST['metadataUpdate'];
            if ($metadataUpdate == 0) {
                try {
                    $quizID = $_POST['quizID'];
                    $week = $_POST['week'];
                    $topicName = $_POST['topicName'];
                    $conn->beginTransaction();

                    $topicResult = getTopicByName($conn, $topicName);
                    $topicID = $topicResult->TopicID;
                    updateQuiz($conn, $quizID, $topicID, $week);

                    $conn->commit();
                } catch (Exception $e) {
                    debug_err($pageName, $e);
                    $conn->rollBack();
                }
            } else if ($metadataUpdate == -1) {
                $quizID = $_POST['quizID'];
                deleteQuiz($conn, $quizID);
                header('Location: saq.php');
            }
        }
        if (isset($_POST['update'])) {
            $update = $_POST['update'];
            if ($update == 1) {
                $quizID = $_POST['quizID'];
                $points = $_POST['points'];
                $question = $_POST['question'];
                createSAQQuestion($conn, $quizID, $points, $question);
            } else if ($update == 0) {
                $saqID = $_POST['saqID'];
                $points = $_POST['points'];
                $question = $_POST['question'];
                updateSAQQuestion($conn, $saqID, $points, $question);
            } else if ($update == -1) {
                $saqID = $_POST['saqID'];
                deleteSAQQuestion($conn, $saqID);
            }
        }
    }
} catch (Exception $e) {
    debug_err($pageName, $e);
}

try {
    if (isset($_GET['quizID'])) {
        $quizID = $_GET['quizID'];
        $quizResult = getSAQQuiz($conn, $quizID);
        $topicResult = getTopics($conn);
        $materialRes = getLearningMaterial($conn, $quizID);
        $saqQuesResult = getSAQQuestions($conn, $quizID);
        $phpSelf = $pageName . '.php?quizID=' . $quizID;
    }
} catch (Exception $e) {
    debug_err($pageName, $e);
}

db_close($conn);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Header Library -->
    <?php require_once('header-lib.php'); ?>
</head>

<body>

<div id="wrapper">

    <?php require_once('navigation.php'); ?>

    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Short Answer Quiz Editor</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">

                <!-- MetaData -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Quiz MetaData
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <form id="metadata-submission" method="post" action="<?php echo $phpSelf; ?>">
                            <!--if 0 update; else if -1 delete;-->
                            <input type=hidden name="metadataUpdate" id="metadataUpdate" value="1" required>
                            <label for="QuizID" style="display:none">QuizID</label>
                            <input type="text" class="form-control" id="QuizID" name="quizID" style="display:none"
                                   value="<?php echo $quizResult->QuizID; ?>">
                            <br>
                            <label for="Week">Week</label>
                            <input type="text" class="form-control" id="Week" name="week"
                                   placeholder="Input Week Number" value="<?php echo $quizResult->Week; ?>">
                            <br>
                            <label for='TopicName'>TopicName</label>
                            <select class="form-control" id="TopicName" form="metadata-submission" name="topicName"
                                    required>
                                <?php for ($j = 0; $j < count($topicResult); $j++) { ?>
                                    <option
                                        value='<?php echo $topicResult[$j]->TopicName ?>' <?php if ($topicResult[$j]->TopicName == $quizResult->TopicName) echo 'selected' ?> ><?php echo $topicResult[$j]->TopicName ?></option>
                                <?php } ?>
                            </select>
                            <br>
                            <label for="Points">Points</label>
                            <input type="text" class="form-control" id="Points" name="points" placeholder="Input Points"
                                   value="<?php echo $quizResult->Points; ?>" disabled>
                            <br>
                            <label for="Questions">Questions</label>
                            <input type="text" class="form-control" id="Questions" name="questions"
                                   value="<?php echo $quizResult->Questions; ?>" disabled>
                            <br>
                        </form>
                        <!--edit metadata-->
                        <span class="glyphicon glyphicon-remove pull-right" id="metadata-remove"
                              aria-hidden="true"></span><span class="pull-right" aria-hidden="true">&nbsp;</span><span
                            class="glyphicon glyphicon-floppy-saved pull-right" id="metadata-save"
                            aria-hidden="true"></span>
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->

                <?php require_once('learning-material-editor-iframe.php'); ?>

                <!-- Questions -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Questions and Points
                        <span class="glyphicon glyphicon-plus pull-right" data-toggle="modal"
                              data-target="#dialog"></span>
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="dataTable_wrapper">
                            <table class="table table-striped table-bordered table-hover" id="datatables">
                                <?php require_once('table-head.php'); ?>
                                <tbody>
                                <?php for ($i = 0; $i < count($saqQuesResult); $i++) { ?>
                                    <tr class="<?php if ($i % 2 == 0) {
                                        echo "odd";
                                    } else {
                                        echo "even";
                                    } ?>">
                                        <td style="display:none"><?php echo $saqQuesResult[$i]->$columnName[0]; ?></td>
                                        <td><?php echo $saqQuesResult[$i]->$columnName[1] ?></td>
                                        <td><?php echo $saqQuesResult[$i]->$columnName[2] ?></td>
                                        <td>
                                            <span class="glyphicon glyphicon-remove pull-right "
                                                  aria-hidden="true"></span>
                                            <span class="pull-right" aria-hidden="true">&nbsp;</span>
                                            <span class="glyphicon glyphicon-edit pull-right" data-toggle="modal"
                                                  data-target="#dialog" aria-hidden="true"></span>
                                        </td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                        <div class="well row">
                            <h4>Short Answer Quiz Overview Notification</h4>
                            <div class="alert alert-info">
                                <p>View short answer questions in this quiz by filtering or searching. You can
                                    create/update/delete any question.</p>
                            </div>
                            <div class="alert alert-danger">
                                <p><strong>Warning</strong> : If you remove one question. All the <strong>student
                                        answers</strong> of this question will also get deleted (not recoverable), not
                                    only the question itself.</p>
                            </div>
                        </div>
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /#page-wrapper -->
</div>
<!-- /#wrapper -->
<!-- Modal -->
<div class="modal fade" id="dialog" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="dialogTitle">Edit Question</h4>
            </div>
            <div class="modal-body">
                <form id="submission" method="post" action="<?php echo $phpSelf; ?>">
                    <input type=hidden name="update" id="update" value="1" required>
                    <label for="SAQID" style="display:none">SAQID</label>
                    <input type="text" class="form-control dialoginput" id="SAQID" name="saqID" style="display:none">
                    <label for="Question">Question</label>
                    <input type="text" class="form-control dialoginput" id="Question" name="question"
                           placeholder="Input Question" value="" required>
                    <br>
                    <label for="Points">Points</label>
                    <input type="text" class="form-control dialoginput" id="Points" name="points"
                           placeholder="Input Points" value="" required>
                    <br>
                    <label for="QuizID" style="display:none">QuizID</label>
                    <input type="text" class="form-control" id="QuizID" name="quizID" style="display:none"
                           value="<?php echo $quizID; ?>" required>
                    <br>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" class="btn btn-default">Save</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- SB Admin Library -->
<?php require_once('sb-admin-lib.php'); ?>
<!-- Page-Level Scripts -->
<script>
    //DO NOT put them in $(document).ready() since the table has multi pages
    var dialogInputArr = $('.dialoginput');
    $('.glyphicon-plus').on('click', function () {
        $('#dialogTitle').text("Add Question");
        $('#update').val(1);
        for (i = 0; i < dialogInputArr.length; i++) {
            dialogInputArr.eq(i).val('');
        }
    });
    $('div > .glyphicon-remove').on('click', function () {
        if (confirm('[WARNING] Are you sure to remove this quiz? If you remove one quiz. All the questions and submission of this quiz will also get deleted (not recoverable). It includes learning material, questions, their submissions and your grading/feedback, not only the quiz itself.')) {
            $('#metadataUpdate').val(-1);
            $('#metadata-submission').submit();
        }
    });
    $('td > .glyphicon-edit').on('click', function () {
        $('#update').val(0);
        for (i = 0; i < dialogInputArr.length; i++) {
            dialogInputArr.eq(i).val($(this).parent().parent().children('td').eq(i).text().trim());
        }
    });
    $('td > .glyphicon-remove').on('click', function () {
        $('#update').val(-1);
        for (i = 0; i < dialogInputArr.length; i++) {
            dialogInputArr.eq(i).val($(this).parent().parent().children('td').eq(i).text().trim());
        }
        $('#submission').submit();
    });
    $('#btnSave').on('click', function () {
        $('#submission').validate({
            rules: {
                points: {
                    required: true,
                    digits: true
                }
            }
        });
        $('#submission').submit();
    });

    $(document).ready(function () {
        var table = $('#datatables').DataTable({
            responsive: true,
            "pageLength": 100,
            "aoColumnDefs": [
                {"bSearchable": false, "aTargets": [0]}
            ]
        })
        $('#metadata-save').on('click', function () {
            $('#metadataUpdate').val(0);
            $('#metadata-submission').validate({
                rules: {
                    week: {
                        required: true,
                        digits: true
                    }
                }
            });
            $('#metadata-submission').submit();
        });
    });
</script>
<script src="researcher-tts.js"></script>
</body>

</html>
