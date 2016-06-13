<?php
session_start();
require_once("../mysql-lib.php");
require_once("../debug.php");
require_once("researcher-validation.php");
$pageName = "saq-editor";
$columnName = array('QuizID', 'Week', 'TopicName', 'Points', 'Questions');

try {
    $conn = db_connect();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['update'])) {
            $update = $_POST['update'];
            if ($update == 0) {
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
    if (isset($_GET['quizID']) && isset($_GET['studentID'])) {
        $quizID = $_GET['quizID'];
        $studentID = $_GET['studentID'];
        $saqSubmissionResult = getSAQSubmission($conn, $quizID, $studentID);
        $phpSelf = $pageName . '.php?quizID=' . $quizID . '&studentID=' . $studentID;
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
                <h1 class="page-header">Short Answer Submission Grader</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">

                <!-- Questions -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Student Short Answer Submission
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <form id="submission" method="post" action="<?php echo $phpSelf ?>">
                            <?php for ($i = 0; $i < count($saqSubmissionResult); $i++) {
                                $saqID = $saqSubmissionResult[$i]->SAQID;
                                ?>
                                <div class="well row">

                                    <label for=studentAnswer[]">Student Answer:</label>
                                    <textarea class="form-control" id="studentAnswer[]" rows="8"
                                              disabled><?php echo $saqSubmissionResult[$i]->Answer ?></textarea>
                                    <label for="feedback[]">Feedback</label>
                                    <input type="text" class="form-control dialogInput" id="feedback"
                                           name="feedback[]"
                                           placeholder="Input Feedback" required>
                                    <br>
                                    <label for="grading[]">Grading</label>
                                    <input type="text" class="dialoginput pull-right" id="textInput<?php echo $saqID ?>"
                                           value="<?php echo $saqSubmissionResult[$i]->Points ?>" disabled>
                                    <input type="range" class="dialoginput" min="0"
                                           max="<?php echo $saqSubmissionResult[$i]->Points ?>"
                                           value="<?php echo $saqSubmissionResult[$i]->Points ?>"
                                           id="grading" name="grading[]"
                                           onchange="updateTextInput(<?php echo $saqID ?>, this.value);">
                                </div>
                            <?php } ?>
                        </form>
                    </div>
                    <!-- /.panel-body -->
                    <div class="panel-footer text-center">
                        <button type="button" id="btnSave" class="btn btn-default btn-info">Save</button>
                    </div>
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

<!-- SB Admin Library -->
<?php require_once('sb-admin-lib.php'); ?>
<!-- Page-Level Scripts -->
<script>
    function updateTextInput(saqID, val) {
        document.getElementById('textInput' + saqID).value = val;
    }

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
</body>

</html>
