<?php
session_start();
require_once("../mysql-lib.php");
require_once("../debug.php");
require_once("researcher-validation.php");
$pageName = "week";

try {
    $conn = db_connect();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $week = $_POST['week'];
        $updateSql = removeWeek($conn, $week);
        //General error: 2014 Cannot execute queries while other unbuffered queries are active. Consider using PDOStatement::fetchAll().
        unset($updateSql);
    }
} catch (Exception $e) {
    debug_err($pageName, $e);
}

try {
    $weekResult = getQuizNum($conn);
    $weekNumResult = getMaxWeek($conn);
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
                <h1 class="page-header">Week Overview</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Week Information Table
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="dataTable_wrapper">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                <tr>
                                    <th>Week</th>
                                    <th>QuizNum</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                for ($i = 1; $i <= $weekNumResult->WeekNum; $i++) {
                                    $notEmpty = false;
                                    for ($j = 0; $j < count($weekResult); $j++) {
                                        if ($weekResult[$j]->Week == $i) { ?>
                                            <tr class="<?php if (($i - 1) % 2 == 0) {
                                                echo "odd";
                                            } else {
                                                echo "even";
                                            } ?>">
                                                <td><a id="<?php echo $i; ?>"
                                                       href="quiz.php?week=<?php echo $i; ?>"><?php echo $i; ?></a>
                                                </td>
                                                <td><?php echo $weekResult[$j]->QuizNum; ?><span
                                                        class="glyphicon glyphicon-remove pull-right" aria-hidden="true"
                                                        data-toggle="modal" data-target="#dialog"></span></td>
                                            </tr>
                                            <?php $notEmpty = true;
                                        }
                                    }
                                    if (!$notEmpty) { ?>
                                        <tr class="<?php if (($i - 1) % 2 == 0) {
                                            echo "odd";
                                        } else {
                                            echo "even";
                                        } ?>">
                                            <td><a id="<?php echo $i; ?>"
                                                   href="quiz.php?week=<?php echo $i; ?>"><?php echo $i; ?></a>
                                            </td>
                                            <td><?php echo 0; ?><span class="glyphicon glyphicon-remove pull-right"
                                                                      aria-hidden="true" data-toggle="modal"
                                                                      data-target="#dialog"></span></td>
                                        </tr>
                                    <?php }
                                } ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                        <div class="well row">
                            <h4>School Overview Notification</h4>
                            <div class="alert alert-info">
                                <p>View weeks by filtering or searching. You can create/delete any week.</p>
                            </div>
                            <div class="alert alert-warning">
                                <p>If you need to add another week, you don't need to explictly add it in this page. Go
                                    to <a href="quiz.php">Quiz Overview</a> to add a new quiz with that week number can
                                    simply work.</p>
                            </div>
                            <div class="alert alert-danger">
                                <p><strong>Reminder</strong>: If you remove one week, all the <strong>quizzes</strong>
                                    linked to this week will still exist, but their "week" attribute will be set to
                                    "null" and you should assign them to another week if you need via <a
                                        href="quiz.php">Quiz Overview</a>.</p>
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
<div class="modal fade" id="dialog" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="dialogTitle">Remove Week</h4>
            </div>
            <div class="modal-body">
                <form id="submission" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <label for="Week" style="display:none">Week</label>
                    <select class="form-control dialoginput" id="Week" form="submission" name="week" required>
                        <?php for ($i = 1; $i <= $weekNumResult->WeekNum; $i++) { ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php } ?>
                    </select>
                </form>
            </div>
            <div class="alert alert-danger">
                <p><strong>Reminder</strong>: If you remove one week, all the <strong>quizzes</strong> linked to this
                    week will still exist, but their "week" attribute will be set to "null" and you should assign them
                    to another week if you need via <a href="quiz.php">Quiz Overview</a>.</p>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnConfirm" class="btn btn-default">Confirm</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- SB Admin Library -->
<?php require_once('sb-admin-lib.php'); ?>
<!-- Page-Level Scripts -->
<script>
    $(document).ready(function () {
        $('#dataTables-example').DataTable({
            responsive: true,
            "pageLength": 25
        });
    });
    var dialogInputArr = $('.dialoginput');
    $('.glyphicon-remove').on('click', function () {
        for (i = 0; i < dialogInputArr.length; i++) {
            console.log($(this).parent().parent().children('span'));
            dialogInputArr.eq(i).val($(this).parent().parent().children('td').eq(i).children('a').attr("id"));
        }
    });
    $('#btnConfirm').on('click', function () {
        $('#submission').validate();
        $('#submission').submit();
    });
</script>
</body>

</html>
