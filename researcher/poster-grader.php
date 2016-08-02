<?php
session_start();
require_once("../mysql-lib.php");
require_once("../debug.php");
require_once("researcher-lib.php");

if (isset($_GET['quizID'])) {
    $quizID = $_GET['quizID'];
}

try {
    $conn = db_connect();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['update'])) {
            $update = $_POST['update'];
            if ($update == 0) {
                $studentID = $_POST['studentID'];
                $grading = $_POST['grading'];
                updatePosterGradings($conn, $quizID, $studentID, $grading);
            }
        }
    }
} catch (Exception $e) {
    debug_err($e);
}

try {
    $posterSubmissionResult = getPosterSubmissionsByQuiz($conn, $quizID);
    $materialRes = getLearningMaterial($conn, $quizID);
    $phpSelf = $pageName . '.php?quizID=' . $quizID;
} catch (Exception $e) {
    debug_err($e);
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
                <h1 class="page-header">Grader</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">

                <?php require_once('learning-material.php'); ?>

                <!-- Questions -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Student Submission
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="parent-container">
                            <!-- Grader -->
                            <form id="submission" method="post" action="<?php echo $phpSelf ?>"
                                  style="text-align: center;">
                                <input type=hidden name="update" id="update" value="0" required>
                                <input type=hidden name="quizID" value="<?php echo $quizID ?>" required>
                                <?php for ($i = 0; $i < count($posterSubmissionResult); $i++) {
                                    $quizID = $posterSubmissionResult[$i]->QuizID;
                                    ?>
                                    <div class="col-lg-4">

                                        <div class="panel panel-default">
                                            <div class="panel-heading">

                                            </div>
                                            <div class="panel-body">
                                                <input type=hidden id="studentID[]"
                                                       disabled
                                                       value="<?php echo $posterSubmissionResult[$i]->StudentID ?>">
                                                <br>
                                                <a href="<?php echo $posterSubmissionResult[$i]->ImageURL ?>"><img
                                                        src="<?php echo $posterSubmissionResult[$i]->ImageURL ?>"
                                                        alt="Failed to load poster. Please contact developers."
                                                        width="200"
                                                        height="200"/></a>
                                                <br>
                                                <br>
                                                <label for="textInput<?php echo $quizID ?>">Grading</label>
                                                <input type="text" class="pull-right"
                                                       id="textInput<?php echo $quizID ?>" name="grading[]"
                                                       value="<?php echo $posterSubmissionResult[$i]->Grading > 0 ? $posterSubmissionResult[$i]->Grading : $posterSubmissionResult[$i]->Points; ?>"
                                                       disabled>
                                                <label for="bonus<?php echo $quizID ?>">Bonus</label>
                                                <input type="hidden" id="bonus[]" name="bonus[]"
                                                       value="0">
                                                <input class="form-control" type="checkbox"
                                                       id="bonus<?php echo $quizID ?>" name="bonus[]"
                                                       value="1" <?php if ($posterSubmissionResult[$i]->Grading > 0) echo 'checked'; ?>>
                                                <br>
                                            </div>
                                        </div>

                                    </div>

                                <?php } ?>
                            </form>
                        </div>
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
    function updateTextInput(quizID, val) {
        document.getElementById('textInput' + quizID).value = val;
    }

    $(document).ready(function () {
        $('#btnSave').on('click', function () {
            $('#submission').validate();
            $('#submission').submit();
        });

        $('.parent-container').magnificPopup({
            delegate: 'a', // child items selector, by clicking on it popup will open
            type: 'image',
            // other options
            gallery: {
                enabled: true, // set to true to enable gallery
                navigateByImgClick: true,
                arrowMarkup: '<button title="%title%" type="button" class="mfp-arrow mfp-arrow-%dir%"></button>', // markup of an arrow button
                tPrev: 'Previous (Left arrow key)', // title for left button
                tNext: 'Next (Right arrow key)', // title for right button
                tCounter: '<span class="mfp-counter">%curr% of %total%</span>' // markup of counter
            }
        })
    });
</script>
</body>

</html>
