<?php
session_start();
require_once("../mysql-lib.php");
require_once("../debug.php");
require_once("researcher-validation.php");
$pageName = "mcq-editor";
$parentPage = 'Location: mcq.php';
$columnName = array('MCQID', 'Question', 'Option', 'Explanation', 'Edit');

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
                    $points = $_POST['points'];
                    $questionnaires = $_POST['questionnaires'];
                    $conn->beginTransaction();
                    
                    $topicID = getTopicByName($conn, $topicName)->TopicID;
                    updateQuiz($conn, $quizID, $topicID, $week);
                    updateMCQSection($conn, $quizID, $points, $questionnaires);

                    $conn->commit();
                } catch (Exception $e) {
                    debug_err($pageName, $e);
                    $conn->rollBack();
                }
            } else if ($metadataUpdate == -1) {
                $quizID = $_POST['quizID'];
                deleteQuiz($conn, $quizID);
                header($parentPage);
            }
        }
        if (isset($_POST['update'])) {
            $update = $_POST['update'];
            if ($update == 1) {
                $quizID = $_POST['quizID'];
                $question = $_POST['question'];
                $mcqID = createMCQQuestion($conn, $quizID, $question);
                header('Location: mcq-option-editor.php?quizID=' . $quizID . '&mcqID=' . $mcqID);
            } else if ($update == -1) {
                $mcqID = $_POST['mcqID'];
                deleteMCQQuestion($conn, $mcqID);
            }
        }
    }
} catch (Exception $e) {
    debug_err($pageName, $e);
}

try {
    if (isset($_GET['quizID'])) {
        $quizID = $_GET['quizID'];
        $quizResult = getMCQQuiz($conn, $quizID);
        $topicResult = getTopics($conn);
        $materialRes = getLearningMaterial($conn, $quizID);
        $mcqQuesResult = getMCQQuestions($conn, $quizID);
        $phpSelf = $pageName . '.php?quizID=' . $quizID;
    }
} catch (Exception $e) {
    debug_err($pageName, $e);
}

db_close($conn);

?>
<!DOCTYPE html>
<html lang="en">

<!-- Header Library -->
<?php require_once('header-lib.php'); ?>

<body>

<div id="wrapper">
    <!-- Navigation Layout-->
    <?php require_once('navigation.php'); ?>

    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Multiple Choice Quiz Editor</h1>
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
                                   value="<?php echo $quizResult->Points; ?>" required>
                            <br>
                            <label for="Questionnaires">Questionnaires</label>
                            <input type="hidden" class="form-control" id="Questionnaires" name="questionnaires"
                                   value="0">
                            <input type="checkbox" class="form-control" id="Questionnaires" name="questionnaires"
                                   value="1" <?php if ($quizResult->Questionnaires != 0) echo 'checked'; ?>>
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

                <!-- Options -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Questions and Options
                        <span class="glyphicon glyphicon-plus pull-right" data-toggle="modal"
                              data-target="#dialog"></span>
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="dataTable_wrapper">
                            <table class="table table-striped table-bordered table-hover" id="datatables">
                                <?php require_once('table-head.php'); ?>
                                <tbody>
                                <?php for ($i = 0; $i < count($mcqQuesResult); $i++) { ?>
                                    <tr class="<?php if ($i % 2 == 0) {
                                        echo "odd";
                                    } else {
                                        echo "even";
                                    } ?>">
                                        <td style="display:none"><?php echo $mcqQuesResult[$i]->$columnName[0]; ?></td>
                                        <!--Question-->
                                        <td><?php echo $mcqQuesResult[$i]->$columnName[1]?>
                                        </td>
                                        <td class="<?php if ($mcqQuesResult[$i]->Content == $mcqQuesResult[$i]->CorrectChoice && strlen($mcqQuesResult[$i]->Content) > 0) {
                                            echo 'bg-success';
                                        } else {
                                            echo 'bg-danger';
                                        } ?>">
                                            <?php echo $mcqQuesResult[$i]->Content; ?>
                                        </td>
                                        <td><?php echo $mcqQuesResult[$i]->$columnName[3] ?></td>
                                        <td>
                                            <span class="glyphicon glyphicon-remove pull-right "
                                                  aria-hidden="true"></span>
                                            <span class="pull-right" aria-hidden="true">&nbsp;</span>
                                            <a href="mcq-option-editor.php?quizID=<?php echo $quizID ?>&mcqID=<?php echo $mcqQuesResult[$i]->$columnName[0]; ?>">
                                                <span class="glyphicon glyphicon-edit pull-right" aria-hidden="true"></span></a>
                                        </td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                        <div class="well row">
                            <h4>Multiple Choice Quiz Overview Notification</h4>
                            <div class="alert alert-info">
                                <p>View multiple choice questions in this quiz by filtering or searching. You can
                                    create/update/delete any question.</p>
                            </div>
                            <div class="alert alert-danger">
                                <p><strong>Warning</strong> : If you remove one question. All the <strong>options and
                                        student answers</strong> of this question will also get deleted (not
                                    recoverable), not only the question itself.</p>
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
                    <label for="MCQID" style="display:none">MCQID</label>
                    <input type="text" class="form-control dialoginput" id="MCQID" name="mcqID" style="display:none">
                    <label for="Question">Question</label>
                    <input type="text" class="form-control dialoginput" id="Question" name="question" value="" required>
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
        if (confirm('[WARNING] Are you sure to remove this quiz? If you remove one quiz. All the questions and submission of this quiz will also get deleted (not recoverable). It includes learning material, questions and options, their submissions and your grading/feedback, not only the quiz itself.')) {
            $('#metadataUpdate').val(-1);
            $('#metadata-submission').submit();
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
        $('#submission').validate();
        $('#submission').submit();
    });

    $(document).ready(function () {
        var table = $('#datatables').DataTable({
            responsive: true,
            //rows group for Question and edit box
            rowsGroup: [1, 4],
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
                    },
                    points: {
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
