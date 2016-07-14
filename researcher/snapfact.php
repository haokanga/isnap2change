<?php
session_start();
require_once("../mysql-lib.php");
require_once("../debug.php");
require_once("researcher-validation.php");
$pageName = "snapfact";
$columnName = array('FactID', 'TopicName', 'Content', 'Edit');

try {
    $conn = db_connect();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['update'])) {
            $update = $_POST['update'];
            if ($update == 1) {
                $topicName = $_POST['topicName'];
                $topicID = getTopicByName($conn, $topicName)->TopicID;
                $content = $_POST['content'];
                createSnapFact($conn, $topicID, $content);
            } else if ($update == 0) {
                $factID = $_POST['factID'];
                $topicName = $_POST['topicName'];
                $topicID = getTopicByName($conn, $topicName)->TopicID;
                $content = $_POST['content'];
                updateSnapFact($conn, $factID, $topicID, $content);
            } else if ($update == -1) {
                $factID = $_POST['factID'];
                deleteSnapFact($conn, $factID);
            }
        }
    }
} catch (Exception $e) {
    debug_err($pageName, $e);
}

try {
    $snapFactResult = getSnapFacts($conn);
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
    <!-- Navigation Layout-->
    <?php require_once('navigation.php'); ?>

    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">SnapFact Overview</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        SnapFact Information Table <span class="glyphicon glyphicon-plus pull-right" data-toggle="modal"
                                                         data-target="#dialog"></span>
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="dataTable_wrapper">
                            <table class="table table-striped table-bordered table-hover" id="datatables">
                                <?php require_once('table-head.php'); ?>
                                <tbody>
                                <?php for ($i = 0; $i < count($snapFactResult); $i++) { ?>
                                    <tr class="<?php if ($i % 2 == 0) {
                                        echo "odd";
                                    } else {
                                        echo "even";
                                    } ?>">
                                        <?php for ($j = 0; $j < count($columnName); $j++) { ?>
                                            <td <?php if ($j == 0) echo 'style="display:none"'; ?>><?php if ($j != 3) echo $snapFactResult[$i]->$columnName[$j]; ?>
                                                <?php if ($j == 3) echo '<span class="glyphicon glyphicon-remove pull-right" aria-hidden="true"></span><span class="pull-right" aria-hidden="true">&nbsp;</span><span class="glyphicon glyphicon-edit pull-right" data-toggle="modal" data-target="#dialog" aria-hidden="true"></span>'; ?>
                                            </td>
                                        <?php } ?>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                        <div class="well row">
                            <h4>SnapFact Overview Notification</h4>
                            <div class="alert alert-info">
                                <p>View snap facts by filtering or searching. You can create/update/delete any snap
                                    fact.</p>
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
                <h4 class="modal-title" id="dialogTitle">Edit SnapFact</h4>
            </div>
            <div class="modal-body">
                <form id="submission" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <!--if 1, insert; else if 0 update; else if -1 delete;-->
                    <input type=hidden name="update" id="update" value="1">
                    <label for="factID" style="display:none">FactID</label>
                    <input type="text" class="form-control dialoginput" id="factID" name="factID"
                           style="display:none">
                    <br>

                    <label for='topicName'>TopicName</label>
                    <select class="form-control dialoginput" id="TopicName" form="submission" name="topicName" required>
                        <option value="" disabled selected>Select Topic</option>
                        <?php for ($j = 0; $j < count($topicResult); $j++) { ?>
                            <option
                                value='<?php echo $topicResult[$j]->TopicName ?>'><?php echo $topicResult[$j]->TopicName ?></option>
                        <?php } ?>
                    </select>
                    <br>

                    <label for="content">Content</label>
                    <textarea class="form-control dialoginput" id="content" name="content" rows="8" required></textarea>
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
    $('.glyphicon-edit').on('click', function () {
        $('#dialogTitle').text("Edit <?php echo $pageName ?>");
        $('#update').val(0);
        for (i = 0; i < dialogInputArr.length; i++) {
            dialogInputArr.eq(i).val($(this).parent().parent().children('td').eq(i).text().trim());
        }
        //disable factID and Classes
        dialogInputArr.eq(0).attr('disabled', 'disabled');
    });
    $('.glyphicon-plus').on('click', function () {
        $('#dialogTitle').text("Add <?php echo $pageName ?>");
        $('#update').val(1);
        for (i = 0; i < dialogInputArr.length; i++) {
            dialogInputArr.eq(i).val('');
        }
        //disable factID and Classes
        dialogInputArr.eq(0).attr('disabled', 'disabled');
    });
    $('.glyphicon-remove').on('click', function () {
        $('#update').val(-1);
        //fill required input
        dialogInputArr.eq(0).prop('disabled', false);
        for (i = 0; i < dialogInputArr.length; i++) {
            dialogInputArr.eq(i).val($(this).parent().parent().children('td').eq(i).text().trim());
        }
        $('#submission').submit();

    });
    $('#btnSave').on('click', function () {
        $('#submission').validate();
        //enable factID
        dialogInputArr.eq(0).prop('disabled', false);
        $('#submission').submit();
    });
    $(document).ready(function () {
        var table = $('#datatables').DataTable({
            responsive: true,
            "pageLength": 100,
            "aoColumnDefs": [
                {"bSearchable": false, "aTargets": [0]}
            ]
        })
    });
</script>
<script src="researcher-tts.js"></script>
</body>

</html>
