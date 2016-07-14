<?php
session_start();
require_once("../mysql-lib.php");
require_once("../debug.php");
require_once("researcher-validation.php");
$pageName = "verbose-fact";
$columnName = array('FactID', 'TopicName', 'SubTitle', 'SubContent', 'Edit');

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

                    //insert and get topicID
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
                header('Location: mcq.php');
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
    $topicResult = getTopics($conn);
    $verboseFactResult = getVerboseFacts($conn);
    $phpSelf = $pageName;
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
                <h1 class="page-header">VerboseFact Overview</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <!-- Options -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Verbose Fact and SubTitles
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="dataTable_wrapper">
                            <table class="table table-striped table-bordered table-hover" id="datatables">
                                <?php require_once('table-head.php'); ?>
                                <tbody>
                                <?php

                                define("SUB_CONTENT_INDEX", 3);
                                define("EDIT_INDEX", 4);
                                define("OMIT_LEN", 40);

                                for ($i = 0; $i < count($verboseFactResult); $i++) { ?>
                                    <tr class="<?php if ($i % 2 == 0) {
                                        echo "odd";
                                    } else {
                                        echo "even";
                                    } ?>">
                                        <?php
                                        for ($j = 0; $j < count($columnName); $j++) { ?>
                                            <td <?php if ($j == 0) echo 'style="display:none"'; ?>>
                                                <?php if ($j != EDIT_INDEX && $j != SUB_CONTENT_INDEX)
                                                echo $verboseFactResult[$i]->$columnName[$j];
                                                else if ($j == SUB_CONTENT_INDEX) {
                                                    $subContent = $verboseFactResult[$i]->$columnName[$j];
                                                    if (strlen($subContent) == 0) { ?>
                                                        <div class="alert alert-danger">
                                                            <p><strong>Reminder</strong> : You have not added any
                                                                verbose fact for this topic!
                                                        </div>
                                                    <?php } else {
                                                        echo mb_strcut($subContent, 0, OMIT_LEN);
                                                        if (strlen($subContent) >= OMIT_LEN) echo "...";
                                                    }

                                                } else if ($j == EDIT_INDEX){ ?>
                                                <a href="verbose-fact-editor.php?topicID=<?php echo $verboseFactResult[$i]->TopicID ?>"><span
                                                        class="pull-right" aria-hidden="true">&nbsp;</span><span
                                                        class="glyphicon glyphicon-edit pull-right" data-toggle="modal"
                                                        data-target="#dialog" aria-hidden="true"></span>
                                                    <?php } ?>
                                            </td>
                                        <?php } ?>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                        <div class="well row">
                            <h4>VerboseFact Overview Notification</h4>
                            <div class="alert alert-info">
                                <p>View verbose facts for each topic by filtering or searching. You can
                                    edit the facts in one topic by clicking the edit button.</p>
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

<!-- SB Admin Library -->
<?php require_once('sb-admin-lib.php'); ?>
<!-- Page-Level Scripts -->
<script>
    $(document).ready(function () {
        var table = $('#datatables').DataTable({
            responsive: true,
            //rows group for Question and edit box
            rowsGroup: [1, 4],
            "pageLength": 100,
            "aoColumnDefs": [
                {"bSearchable": false, "aTargets": [0]}
            ]
        });
    });
</script>
<script src="researcher-tts.js"></script>
</body>

</html>
