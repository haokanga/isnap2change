<?php
session_start();
require_once("../mysql-lib.php");
require_once("../debug.php");
require_once("researcher-lib.php");
$columnName = array('StudentID', 'ClassName', 'Username', 'FirstName', 'LastName', 'Email', 'Gender', 'DOB', 'Score', 'SubmissionDate');

try {
    $conn = db_connect();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['update'])) {
            $update = $_POST['update'];
            //reset student password
            if ($update == 0) {
                $studentID = $_POST['studentID'];
                resetPassword($conn, $studentID);
            } //delete student (with help of DELETE CASCADE)            
            else if ($update == -1) {
                $studentID = $_POST['studentID'];
                deleteStudent($conn, $studentID);
            }
        }
    }
} catch (Exception $e) {
    debug_err($pageName, $e);
}

try {
    refreshAllStudentsScore($conn);
    $studentResult = getStudents($conn);
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
                <h1 class="page-header">Student Overview</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Student Information Table
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div>
                            Toggle column:
                            <?php for ($i = 1; $i < count($columnName); $i++) {
                                if ($columnName[$i] != 'Username') { ?>
                                    <i class="fa fa-check-square-o fa-fw"></i><a class="toggle-vis"
                                                                                 data-column="<?php echo $i; ?>"><?php echo $columnName[$i]; ?></a>&nbsp;
                                <?php }
                            } ?>
                            <br>
                            <br>
                        </div>
                        <div class="dataTable_wrapper">
                            <table class="table table-striped table-bordered table-hover" id="datatables">
                                <?php require_once('table-head.php'); ?>
                                <tbody>
                                <?php for ($i = 0; $i < count($studentResult); $i++) { ?>
                                    <tr class="<?php if ($i % 2 == 0) {
                                        echo "odd";
                                    } else {
                                        echo "even";
                                    } ?>">
                                        <?php for ($j = 0; $j < count($columnName); $j++) { ?>
                                            <td <?php if ($j == 0) echo 'style="display:none"'; ?>><?php echo $studentResult[$i]->$columnName[$j]; ?>
                                                <?php if ($j == 2) echo '<span class="glyphicon glyphicon-remove pull-right" aria-hidden="true"></span><span class="pull-right" aria-hidden="true">&nbsp;</span><span class="glyphicon glyphicon-edit pull-right" data-toggle="modal" data-target="#dialog" aria-hidden="true"></span>'; ?>
                                            </td>
                                        <?php } ?>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                        <div class="well row">
                            <h4>Student Overview Notification</h4>
                            <div class="alert alert-info">
                                <p>View students by filtering or searching. You can <strong>reset student
                                        password</strong> or delete students.</p>
                            </div>
                            <div class="alert alert-danger">
                                <p><strong>Reminder</strong> : If you remove one student. All the data of this student
                                    will also get deleted (not recoverable). It includes <strong>student submissions of
                                        every task and your grading/feedback</strong>, not only the student itself.</p>
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
                <h4 class="modal-title" id="dialogTitle">Reset Password</h4>
            </div>
            <div class="modal-body">
                <form id="submission" method="post"
                      action="<?php if (isset($_GET['classID'])) echo $_SERVER['PHP_SELF'] . '?classID=' . $_GET['classID']; else echo $_SERVER['PHP_SELF']; ?>">
                    <!--if 0 update; else if -1 delete;-->
                    <input type=hidden name="update" id="update" value="1">
                    <?php for ($i = 0; $i < count($columnName); $i++) {
                        if ($columnName[$i] == 'StudentID' || $columnName[$i] == 'Username') {
                            ?>
                            <label for="<?php echo $columnName[$i]; ?>" <?php if ($i == 0) {
                                echo 'style="display:none"';
                            } ?>><?php echo $columnName[$i]; ?></label>
                            <input type="text" class="form-control dialoginput" id="<?php echo $columnName[$i]; ?>"
                                   name="<?php echo lcfirst($columnName[$i]); ?>"
                                <?php if ($i == 0) {
                                    echo 'style="display:none"';
                                } ?> >
                        <?php }
                    } ?>
                    <br>
                    <div class="alert alert-info">
                        <p>You can <strong>reset student password</strong> to <code>WelcomeToiSNAP2</code>.</p>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btmResetPwd" class="btn btn-default">Reset Password</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<input type=hidden name="keyword" id="keyword" value="
      <?php
if (isset($_GET['classID'])) {
    // get ClassName
    try {
        $classID = $_GET['classID'];
        $classResult = getClass($conn, $classID);
        echo $classResult->ClassName;
    } catch (Exception $e) {
        debug_err($pageName, $e);
        echo '';
    }
} else
    echo '';
?>">
<!-- SB Admin Library -->
<?php require_once('sb-admin-lib.php'); ?>
<!-- Page-Level Scripts -->
<script>
    function randomString(length) {
        return Math.round((Math.pow(36, length + 1) - Math.random() * Math.pow(36, length))).toString(36).slice(1);
    }
    //DO NOT put them in $(document).ready() since the table has multi pages
    var dialogInputArr = $('.dialoginput');
    $('.glyphicon-edit').on('click', function () {
        $('#update').val(0);
        //studentID, username
        dialogInputArr.eq(0).val($(this).parent().parent().children('td').eq(0).text());
        dialogInputArr.eq(1).val($(this).parent().text());
        dialogInputArr.each(function () {
            $(this).attr('disabled', 'disabled');
        });
    });
    $('.glyphicon-remove').on('click', function () {
        if (confirm('[WARNING] Are you sure to remove this student? All the data of this student will also get deleted (not recoverable). It includes student submissions of every task and your grading/feedback, not only the student itself.')) {
            $('#update').val(-1);
            //studentID, username
            dialogInputArr.eq(0).val($(this).parent().parent().children('td').eq(0).text());
            dialogInputArr.eq(1).val($(this).parent().text());
            //enable all the input
            dialogInputArr.each(function () {
                $(this).prop('disabled', false);
            });
            $('#submission').submit();
        }
    });
    $('#btmResetPwd').on('click', function () {
        $('#submission').validate();
        //enable all the input
        dialogInputArr.each(function () {
            $(this).prop('disabled', false);
        });
        if (confirm('[WARNING] Are you sure to reset this student password to `WelcomeToiSNAP2`? (not recoverable).')) {
            $('#submission').submit();
        }
    });

    $(document).ready(function () {
        var table = $('#datatables').DataTable({
            responsive: true,
            "initComplete": function (settings, json) {
                $('.input-sm').eq(1).val($("#keyword").val().trim());
            },
            "pageLength": 50
        })
        //search keyword, exact match
        table.search(
            $("#keyword").val().trim(), true, false, true
        ).draw();

        //Toggle column visibility
        $('a.toggle-vis').on('click', function (e) {
            e.preventDefault();
            // Get the column API object
            var column = table.column($(this).attr('data-column'));
            column.visible(!column.visible());
            var checkbox = $(this).parent().children().eq($(this).index() - 1);
            if (checkbox.hasClass('fa-check-square-o'))
                checkbox.removeClass('fa-check-square-o').addClass('fa-square-o');
            else if (checkbox.hasClass('fa-square-o'))
                checkbox.removeClass('fa-square-o').addClass('fa-check-square-o');
        });

        $('.fa-square-o, .fa-check-square-o').on('click', function (e) {
            $(this).parent().children().eq($(this).index() + 1).click();
        });

        //hide 'FirstName','LastName','Gender', 'DOB' by default
        var hiddenColArray = ['FirstName', 'LastName', 'Gender', 'DOB']
        $('a.toggle-vis').each(function () {
            if (jQuery.inArray($(this).text(), hiddenColArray) != -1)
                $(this).click();
        });
    });
</script>
</body>

</html>
