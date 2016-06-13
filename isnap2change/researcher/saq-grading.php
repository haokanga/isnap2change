<?php
session_start();
require_once("../mysql-lib.php");
require_once("../debug.php");
require_once("researcher-validation.php");
$pageName = "saq-grading";
$columnName = array('QuizID', 'Week', 'StudentID', 'TopicName', 'ClassName', 'Username');

try {
    $conn = db_connect();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['update'])) {
            $update = $_POST['update'];
            if ($update == -1) {
                $quizID = $_POST['quizID'];
                $studentID = $_POST['studentID'];
                deleteSAQSubmission($conn, $quizID, $studentID, $pageName);
            }
        }
    }
} catch (Exception $e) {
    debug_err($pageName, $e);
}

try {
    $submissionResult = getSAQSubmissions($conn);
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
                <h1 class="page-header">Short Answer Submissions Overview</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Short Answer Submissions Information Table
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="dataTable_wrapper">
                            <table class="table table-striped table-bordered table-hover" id="datatables">
                                <thead>
                                <tr>
                                    <?php for ($i = 0; $i < count($columnName); $i++) { ?>
                                        <th <?php if ($i == 0) {
                                            echo 'style="display:none"';
                                        } ?>><?php echo $columnName[$i]; ?></th>
                                    <?php } ?>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php for ($i = 0; $i < count($submissionResult); $i++) {
                                    $quizID = $submissionResult[$i]->QuizID;
                                    $studentID = $submissionResult[$i]->StudentID; ?>
                                    <tr class="<?php if ($i % 2 == 0) {
                                        echo "odd";
                                    } else {
                                        echo "even";
                                    } ?>">
                                        <?php for ($j = 0; $j < count($columnName); $j++) { ?>
                                            <td <?php if ($j == 0) {
                                                echo 'style="display:none"';
                                            } ?>>
                                                <?php if (strlen($submissionResult[$i]->$columnName[$j]) > 0) echo $submissionResult[$i]->$columnName[$j]; else echo 0; ?>
                                            </td>
                                        <?php } ?>
                                        <td>
                                            <?php echo getQuizStatus($conn, $quizID, $studentID) ?>
                                            <span class="glyphicon glyphicon-remove pull-right"
                                                  aria-hidden="true"></span>
                                            <span class="pull-right" aria-hidden="true">&nbsp;</span>
                                            <a href="saq-grader.php?quizID=<?php echo $quizID ?>&studentID=<?php echo $studentID ?>">
                                                <span class="glyphicon glyphicon-edit pull-right"
                                                      aria-hidden="true"></span></a>
                                        </td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                        <div class="well row">
                            <h4>Short Answer Submissions Overview Notification</h4>
                            <div class="alert alert-info">
                                <p>View submissions by filtering or searching. </p>
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
                <h4 class="modal-title" id="dialogTitle">Edit Quiz</h4>
            </div>
            <div class="modal-body">
                <form id="submission" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <!--if 1, insert; else if -1 delete;-->
                    <input type=hidden name="update" id="update" value="1" required>
                    <label for="QuizID" style="display:none">QuizID</label>
                    <input type="text" class="form-control dialoginput" id="QuizID" name="quizID" style="display:none">
                    <label for="Week">Week</label>
                    <input type="text" class="form-control dialoginput" id="Week" name="week"
                           placeholder="Input Week Number" required>
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
    $('.glyphicon-remove').on('click', function () {
        if (confirm('[WARNING] Are you sure to remove this submission?')) {
            $('#update').val(-1);
            for (i = 0; i < dialogInputArr.length; i++) {
                dialogInputArr.eq(i).val($(this).parent().parent().children('td').eq(i).text().trim());
            }
            $('#submission').submit();
        }
    });
    $(document).ready(function () {
        var table = $('#datatables').DataTable({
            responsive: true,
            "order": [[6, "desc"], [1, "asc"], [2, "asc"]],
            "pageLength": 100,
            "aoColumnDefs": [
                {"bSearchable": false, "aTargets": [0]}
            ]
        })
    });
</script>
</body>

</html>
