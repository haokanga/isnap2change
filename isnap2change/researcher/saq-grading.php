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
                                        <th <?php if ($i == 0 || $i == 2) {
                                            echo 'style="display:none"';
                                        } ?>><?php echo $columnName[$i]; ?></th>
                                    <?php } ?>
                                    <th>Score</th>
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
                                            <td <?php if ($j == 0 || $j == 2) {
                                                echo 'style="display:none"';
                                            } ?>>
                                                <?php if (strlen($submissionResult[$i]->$columnName[$j]) > 0) echo $submissionResult[$i]->$columnName[$j]; else echo 0; ?>
                                            </td>
                                        <?php } ?>
                                        <td>
                                            <?php
                                            $status = getQuizStatus($conn, $quizID, $studentID);
                                            if ($status == 'GRADED') {
                                                $stuQuizScore = getStuQuizScore($conn, $quizID, $studentID);
                                                $quizPoints = getQuizPoints($conn, $quizID);
                                                $percentage = $stuQuizScore / $quizPoints;
                                                echo $stuQuizScore . '/' . $quizPoints . '  (' . ($stuQuizScore / $quizPoints * 100) . '%)';
                                            } else
                                                echo '-'; ?>
                                        </td>
                                        <td>
                                            <?php
                                            echo $status ?>
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
<form id="submission" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <input type=hidden name="update" id="update" value="-1" required>
    <input type=hidden name="studentID" id="studentID" value="" required>
    <input type=hidden id="quizID" name="quizID" value="" required>
</form>

<!-- SB Admin Library -->
<?php require_once('sb-admin-lib.php'); ?>
<!-- Page-Level Scripts -->
<script>
    //DO NOT put them in $(document).ready() since the table has multi pages
    $('.glyphicon-remove').on('click', function () {
        if (confirm('[WARNING] Are you sure to remove this submission?')) {
            $('#update').val(-1);
            $('#quizID').val($(this).parent().parent().children('td').eq(0).text().trim());
            $('#studentID').val($(this).parent().parent().children('td').eq(2).text().trim());
            $('#submission').submit();
        }
    });
    $(document).ready(function () {
        var table = $('#datatables').DataTable({
            responsive: true,
            "order": [[7, "desc"], [1, "asc"], [2, "asc"]],
            "pageLength": 100,
            "aoColumnDefs": [
                {"bSearchable": false, "aTargets": [0]}
            ]
        })
    });
</script>
</body>

</html>
