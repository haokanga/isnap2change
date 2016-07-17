<?php
session_start();
require_once("mysql-lib.php");
require_once("debug.php");

try {
    $conn = db_connect();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['update'])) {
            $update = $_POST['update'];
            if ($update == 0) {
                $logID = $_POST['logID'];
                $userFeedback = $_POST['userFeedback'];
                updateLog($conn, $logID, $userFeedback);
            }
        }
    }
} catch (Exception $e) {
    // don't trigger debug_err here to prevent infinite loop
}

try {
    if (isset($_POST['logID'])) {
        $logID = $_POST['logID'];
    }
} catch (Exception $e) {
    // don't trigger debug_err here to prevent infinite loop
}
?>

<!DOCTYPE html>
<html lang="en">

<!-- Bootstrap Core CSS -->
<link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

<body>

<!-- Modal -->
<div class="modal fade" id="dialog" role="dialog" data-backdrop="static"
     data-keyboard="false">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="dialogTitle">Bug Report</h4>
            </div>
            <div class="modal-body">
                <form id="submission" method="post" action="<?php echo $phpSelf; ?>">
                    <input type=hidden name="update" id="update" value="1" required>
                    <label for="logID" style="display:none">LogID</label>
                    <input type="text" class="form-control dialoginput" id="logID" name="logID" style="display:none">
                    <label for="userFeedback">User Feedback</label>
                    <textarea class="form-control dialoginput" id="userFeedback" name="userFeedback"
                              placeholder="Describe bug" rows="8" required></textarea>
                    <br>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" class="btn btn-default">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

<!-- Metis Menu Plugin JavaScript -->
<script src="bower_components/metisMenu/dist/metisMenu.min.js"></script>

<!-- DataTables JavaScript -->
<script src="bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>

<!-- Custom Theme JavaScript -->
<script src="sb-admin/js/sb-admin-2.js"></script>

<!--jQuery Validate plugin-->
<script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.min.js"></script>

<!-- DataTables rowsGroup Plugin -->
<script src="bower_components/datatables-plugins/rowsgroup/dataTables.rowsGroup.js "></script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/ui-lightness/jquery-ui.css"/>
<link rel="stylesheet" href="//code.jquery.com/qunit/qunit-1.18.0.css"/>

<script>
    $(window).load(function(){
        $('#dialog').modal('show');

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
    });
</script>
