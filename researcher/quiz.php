<?php
session_start();
require_once("../mysql-lib.php");
require_once("../debug.php");
require_once("researcher-lib.php");
$columnName = array('QuizID', 'Week', 'QuizType', 'TopicName', 'Points');


try {
    $conn = db_connect();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['update'])) {
            $update = $_POST['update'];
            if ($update == 1) {
                try {
                    $week = $_POST['week'];
                    $quizType = $_POST['quizType'];
                    $topicName = $_POST['topicName'];
                    $topicID = getTopicByName($conn, $topicName)->TopicID;

                    $conn->beginTransaction();
                    $points = isset($_POST['points']) ? $_POST['points'] : 0;

                    //if editable
                    if (in_array($quizType, $editableQuizTypeArr)) {
                        //create quiz section
                        switch ($quizType) {
                            case "MCQ":
                                $quizID = createQuiz($conn, $topicID, $quizType, $week);
                                $questionnaire = 0;
                                createMCQSection($conn, $quizID, $points, $questionnaire);
                                break;
                            case "Questionnaire":
                                $quizID = createQuiz($conn, $topicID, $quizType, $week);
                                $questionnaire = 1;
                                createMCQSection($conn, $quizID, $points, $questionnaire);
                                break;
                            case "SAQ":
                            case "Video":
                            case "Image":
                                $quizID = createQuiz($conn, $topicID, $quizType, $week);
                                createSAQLikeSection($conn, $quizID);
                                break;
                            case "Matching":
                                $quizID = createQuiz($conn, $topicID, $quizType, $week);
                                $description = '';
                                createMatchingSection($conn, $quizID, $description, $points);
                                break;
                            case "Poster":
                                $quizID = createQuiz($conn, $topicID, $quizType, $week);
                                $question = '';
                                createPosterSection($conn, $quizID, $question, $points);
                                break;
                            default:
                                throw new Exception("Unexpected Quiz Type. QuizID: " . $quizID);
                        }
                        //create default learning material
                        if ($quizType == "Video")
                            createVideoLearningMaterial($conn, $quizID);
                        else if ($quizType == "Image")
                            createImageLearningMaterial($conn, $quizID);
                        else
                            createEmptyLearningMaterial($conn, $quizID);
                    } //if misc
                    else {
                        $quizID = createQuiz($conn, $topicID, 'Misc', $week);
                        createMiscSection($conn, $quizID, $points, $quizType);
                    }

                    $conn->commit();
                } catch (Exception $e) {
                    debug_err($pageName, $e);
                    $conn->rollBack();
                }
            } else if ($update == 0) {
                // if edit misc quiz
                $week = $_POST['week'];
                $quizID = $_POST['quizID'];
                $topicName = $_POST['topicName'];
                $topicID = getTopicByName($conn, $topicName)->TopicID;

                $conn->beginTransaction();
                $points = isset($_POST['points']) ? $_POST['points'] : 0;

                updateQuiz($conn, $quizID, $topicID, $week);
                updateMiscSection($conn, $quizID, $points);

            } else if ($update == -1) {
                $quizID = $_POST['quizID'];
                deleteQuiz($conn, $quizID);
            }
        }
    }
} catch (Exception $e) {
    debug_err($pageName, $e);
}


try {
    // misc.php
    if (strpos($pageName, 'misc') !== false) {
        $quizResult = getMiscQuizzes($conn);
    } // quiz.php
    else if (strpos($pageName, 'quiz') !== false) {
        if (isset($_GET['week'])) {
            $quizResult = getQuizzesByWeek($conn, $_GET['week']);
        } else {
            $quizResult = getQuizzes($conn);
        }
    }
    $topicResult = getTopics($conn);
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
    <?php require_once('navigation.php'); ?>
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header"><?php echo $pageNameForView; ?> Overview
                    <?php if (isset($_GET['week'])) { ?>
                        <div class="alert alert-info alert-dismissable" style="display: inline-block;">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true"
                                    onclick="location.href='quiz.php';">×
                            </button>
                            <i class="fa fa-info-circle"></i> <?php echo 'Week ' . $_GET['week']; ?>
                        </div>
                    <?php } ?>
                </h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Quiz Information Table <span class="glyphicon glyphicon-plus pull-right" data-toggle="modal"
                                                     data-target="#dialog"></span>
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="dataTable_wrapper">
                            <table class="table table-striped table-bordered table-hover" id="datatables">
                                <?php require_once('table-head.php'); ?>
                                <tbody>
                                <?php for ($i = 0; $i < count($quizResult); $i++) {
                                    $quizID = $quizResult[$i]->QuizID;
                                    $quizType = getQuizType($conn, $quizID);
                                    $points = getQuizPoints($conn, $quizID);
                                    ?>
                                    <tr class="<?php if ($i % 2 == 0) {
                                        echo "odd";
                                    } else {
                                        echo "even";
                                    } ?>">
                                        <td style="display:none"><?php echo $quizID ?></td>
                                        <td><?php echo $quizResult[$i]->Week ?></td>
                                        <td><?php echo $quizType ?></td>
                                        <td><?php echo $quizResult[$i]->TopicName ?></td>
                                        <td><?php echo $points ?>
                                            <span class="glyphicon glyphicon-remove pull-right"
                                                  aria-hidden="true"></span>
                                            <span class="pull-right" aria-hidden="true">&nbsp;</span>
                                            <?php if (in_array($quizType, $editableQuizTypeArr)) { ?>
                                                <a href="<?php echo strtolower($quizType); ?>-editor.php?quizID=<?php echo $quizID ?>">
                                                    <span class="glyphicon glyphicon-edit pull-right"
                                                          aria-hidden="true"></span>
                                                </a>
                                            <?php } else if (in_array($quizType, $miscQuizTypeArr)) { ?>
                                                <span class="glyphicon glyphicon-edit pull-right"
                                                      data-toggle="modal"
                                                      data-target="#dialog" aria-hidden="true"></span>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                        <?php require_once('quiz-overview-notification.php'); ?>
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
                <h4 class="modal-title" id="dialogTitle"></h4>
            </div>
            <div class="modal-body">
                <form id="submission" method="post"
                      action="<?php if (isset($_GET['week'])) echo $_SERVER['PHP_SELF'] . '?week=' . $_GET['week']; else echo $_SERVER['PHP_SELF']; ?>">
                    <!--if 1, insert; else if -1 delete;-->
                    <input type=hidden name="update" id="update" value="1" required>
                    <label for="QuizID" style="display:none">QuizID</label>
                    <input type="text" class="form-control dialoginput" id="QuizID" name="quizID" style="display:none">
                    <label for="Week">Week</label>
                    <input type="text" class="form-control dialoginput" id="Week" name="week"
                           placeholder="Input Week Number" <?php if (isset($_GET['week'])) {
                        $w = $_GET['week'];
                        echo "value='" . $w . "'";
                    } ?> required>
                    <br>
                    <label for='QuizType'>QuizType</label>
                    <select class="form-control dialoginput" id="QuizType" form="submission" name="quizType" required>
                        <option value="" disabled selected>Select Quiz Type</option>

                        // quiz.php
                        <?php if (strpos($pageName, 'quiz') !== false) { ?>
                            <optgroup label="Editable Quiz">
                                <?php for ($i = 0; $i < count($editableQuizTypeArr); $i++) { ?>
                                    <option
                                        value="<?php echo $editableQuizTypeArr[$i] ?>"><?php echo $editableQuizTypeArr[$i] ?></option>
                                <?php } ?>
                            </optgroup>
                        <?php } ?>
                        <optgroup label="Misc Quiz">
                            <?php for ($i = 0; $i < count($miscQuizTypeArr); $i++) { ?>
                                <option
                                    value="<?php echo $miscQuizTypeArr[$i] ?>"><?php echo $miscQuizTypeArr[$i] ?></option>
                            <?php } ?>
                        </optgroup>
                    </select>
                    <br>
                    <label for='TopicName'>TopicName</label>
                    <select class="form-control dialoginput" id="TopicName" form="submission" name="topicName" required>
                        <option value="" disabled selected>Select Topic</option>
                        <?php for ($j = 0; $j < count($topicResult); $j++) { ?>
                            <option
                                value='<?php echo $topicResult[$j]->TopicName ?>'><?php echo $topicResult[$j]->TopicName ?></option>
                        <?php } ?>
                    </select>
                    <br>
                    <label for="Points">Points</label>
                    <input type="text" class="form-control dialoginput" id="Points" name="points"
                           placeholder="Input Points">
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
<input type=hidden name="keyword" id="keyword" value="<?php if (isset($_GET['week'])) {
    echo $_GET['week'];
} ?>">
<!-- SB Admin Library -->
<?php require_once('sb-admin-lib.php'); ?>
<!-- Page-Level Scripts -->
<script>
    //DO NOT put them in $(document).ready() since the table has multi pages
    var dialogInputArr = $('.dialoginput');
    var len = dialogInputArr.length;
    var pointsIndex = len - 1;
    var quizTypeIndex = len - 3;

    $('.glyphicon-edit').on('click', function () {
        $("label").remove(".error");
        $('#dialogTitle').text("Edit <?php echo $pageNameForView ?>");
        $('#update').val(0);
        for (i = 0; i < dialogInputArr.length; i++) {
            dialogInputArr.eq(i).val($(this).parent().parent().children('td').eq(i).text().trim());
        }
        //if edit, disable quiz type
        dialogInputArr.eq(quizTypeIndex).attr('disabled', 'disabled');
    });
    $('.glyphicon-plus').on('click', function () {
        $("label").remove(".error");
        $('#dialogTitle').text("Add <?php echo $pageNameForView ?>");
        $('#update').val(1);
        //enable all the buttons
        for (i = 0; i < dialogInputArr.length; i++) {
            dialogInputArr.eq(i).prop('disabled', false);
            if (i != 1) {
                dialogInputArr.eq(i).val('');
            } else {
                <?php if(!isset($_GET['week'])){?>
                dialogInputArr.eq(i).val('');
                <?php } ?>
            }
        }
    });
    $('.glyphicon-remove').on('click', function () {
        if (confirm('[WARNING] Are you sure to remove this quiz? If you remove one quiz. All the questions and submission of this quiz will also get deleted (not recoverable). It includes learning material, questions and options, their submissions and your grading/feedback, not only the quiz itself.')) {
            $('#update').val(-1);
            for (i = 0; i < dialogInputArr.length; i++) {
                dialogInputArr.eq(i).val($(this).parent().parent().children('td').eq(i).text().trim());
            }
            $('#submission').submit();
        }
    });
    $('#btnSave').on('click', function () {
        $('#submission').validate({
            rules: {
                week: {
                    required: true,
                    digits: true
                },
                points: {
                    <?php if (strpos($pageName, 'misc') !== false) echo "required: true,"; ?>
                    digits: true
                }
            }
        });
        $('#submission').submit();
    });

    $(document).ready(function () {
        var table = $('#datatables').DataTable({
            responsive: true,
            "initComplete": function (settings, json) {
                $('.input-sm').eq(1).val($("#keyword").val().trim());
            },
            "order": [[1, "asc"]],
            "pageLength": 50,
            "aoColumnDefs": [
                {"bSearchable": false, "aTargets": [0]}
            ]
        });
        //search keyword, exact match
        table.search(
            $("#keyword").val().trim(), true, false, true
        ).draw();
        //if quiz is saq-like, disable Points
        $("#QuizType").change(function () {
            if ($.inArray($(this).val().trim(), <?php echo json_encode($saqLikeQuizTypeArr); ?>) != -1) {
                dialogInputArr.eq(pointsIndex).attr('disabled', 'disabled');
            } else {
                dialogInputArr.eq(pointsIndex).prop('disabled', false);
            }
        });
    });
</script>
</body>

</html>
