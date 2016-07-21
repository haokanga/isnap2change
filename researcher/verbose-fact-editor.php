<?php
session_start();
require_once("../mysql-lib.php");
require_once("../debug.php");
require_once("researcher-lib.php");
$columnName = array('VerboseFactID', 'Title', 'Content', 'Edit');

try {
    $conn = db_connect();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['update'])) {
            $update = $_POST['update'];
            if ($update == 1) {
                $topicID = $_POST['topicID'];
                $title = $_POST['title'];
                $content = $_POST['content'];
                createVerboseFact($conn, $topicID, $title, $content);
            } else if ($update == 0) {
                $verboseFactID = $_POST['subFactID'];
                $title = $_POST['title'];
                $content = $_POST['content'];
                updateVerboseFact($conn, $verboseFactID, $title, $content);
            } else if ($update == -1) {
                $verboseFactID = $_POST['subFactID'];
                deleteVerboseFact($conn, $verboseFactID);
            }
        }
    }
} catch (Exception $e) {
    debug_err($pageName, $e);
}

try {
    if (isset($_GET['topicID'])) {
        $topicID = $_GET['topicID'];
        $topicName = getTopic($conn, $topicID)->TopicName;
        $verboseFactResult = getVerboseFactsByTopic($conn, $topicID);
        $phpSelf = $pageName . '.php?topicID=' . $topicID;
    }
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
                <h1 class="page-header">Verbose Fact Editor
                    <button type="button" class="btn btn-lg btn-info pull-right"
                            onclick="location.href='<?php echo "verbose-fact.php"; ?>'">GO BACK
                    </button>
                </h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <!-- MetaData -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Verbose Fact MetaData
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <form id="metadata-submission" method="post" action="<?php echo $phpSelf; ?>">
                            <label for="topicID" style="display:none">TopicID</label>
                            <input type="text" class="form-control" id="topicID" name="topicID" style="display:none"
                                   value="<?php echo $topicID; ?>" required>
                            <br>
                            <label for="topicName">TopicName</label>
                            <input type="text" class="form-control" id="topicName" name="topicName"
                                   value="<?php echo $topicName; ?>" disabled>
                            <br>
                            <label for="verboseFacts">Verbose Facts</label>
                            <input type="text" class="form-control" id="verboseFacts" name="verboseFacts"
                                   value="<?php echo getVerboseFactNumByTopic($conn, $topicID); ?>" disabled>
                            <br>
                        </form>
                        <!--No Verbose Fact Reminder-->
                        <div class="alert alert-danger" id="noVerboseFactReminder" hidden>
                            <p><strong>Reminder</strong> : You have not added any verbose fact for this
                                topic!
                        </div>
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->


                <div class="panel panel-default">
                    <div class="panel-heading">
                        Verbose Fact Information Table <span class="glyphicon glyphicon-plus pull-right"
                                                             data-toggle="modal"
                                                             data-target="#dialog"></span>
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="dataTable_wrapper">
                            <table class="table table-striped table-bordered table-hover" id="datatables">
                                <?php require_once('table-head.php'); ?>
                                <tbody>
                                <?php for ($i = 0; $i < count($verboseFactResult); $i++) { ?>
                                    <tr class="<?php if ($i % 2 == 0) {
                                        echo "odd";
                                    } else {
                                        echo "even";
                                    } ?>">
                                        <?php for ($j = 0; $j < count($columnName); $j++) { ?>
                                            <td <?php if ($j == 0) {
                                                echo 'style="display:none"';
                                            } ?>>
                                                <?php
                                                if ($j != count($columnName) - 1)
                                                    echo $verboseFactResult[$i]->$columnName[$j];
                                                else { ?>
                                                    <span class="glyphicon glyphicon-remove pull-right"
                                                          aria-hidden="true"></span>
                                                    <span class="pull-right" aria-hidden="true">&nbsp;</span>
                                                    <span class="glyphicon glyphicon-edit pull-right"
                                                          data-toggle="modal"
                                                          data-target="#dialog"
                                                          aria-hidden="true"></span>
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
                            <h4>Verbose Fact Notification</h4>
                            <div class="alert alert-info">
                                <p>You can create/update/delete any verbose fact for this topic.</p>
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
                <h4 class="modal-title" id="dialogTitle">Edit VerboseFact</h4>
            </div>
            <div class="modal-body">
                <form id="submission" method="post" action="<?php echo $phpSelf; ?>">
                    <input type=hidden name="update" id="update" value="1" required>
                    <label for="subFactID" style="display:none">subFactID</label>
                    <input type="text" class="form-control dialoginput" id="subFactID" name="subFactID"
                           style="display:none">
                    <label for="title">Title</label>
                    <input type="text" class="form-control dialoginput" id="title" name="title"
                           placeholder="Input Title" required>
                    <br>
                    <label for="content">Content</label>
                    <input type="text" class="form-control dialoginput" id="content" name="content"
                           placeholder="Input Content" required>
                    <br>
                    <label for="topicID" style="display:none">topicID</label>
                    <input type="text" class="form-control dialoginput" id="topicID" name="topicID" style="display:none"
                           value="<?php echo $topicID; ?>" required>
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
    $('.glyphicon-plus').on('click', function () {
		$("label").remove(".error");
        $('#dialogTitle').text("Add Verbose Fact");
        $('#update').val(1);
        for (i = 0; i < dialogInputArr.length - 1; i++) {
            dialogInputArr.eq(i).val('');
        }
    });
    $('td > .glyphicon-edit').on('click', function () {
        $('#dialogTitle').text("Edit Verbose Fact");
        $('#update').val(0);
        for (i = 0; i < dialogInputArr.length - 1; i++) {
            dialogInputArr.eq(i).val($(this).parent().parent().children('td').eq(i).text().trim());
        }
    });
    $('td > .glyphicon-remove').on('click', function () {
        $('#update').val(-1);
        for (i = 0; i < dialogInputArr.length - 1; i++) {
            dialogInputArr.eq(i).val($(this).parent().parent().children('td').eq(i).text().trim());
        }
        $('#submission').submit();
    });
    $('#btnSave').on('click', function () {
        $('#submission').validate();
        for (i = 0; i < dialogInputArr.length; i++) {
            console.log(dialogInputArr.eq(i).val());
        }
        $('#submission').submit();
    });

    $(document).ready(function () {
        var table = $('#datatables').DataTable({
            responsive: true,
            "order": [[1, "asc"]],
            "pageLength": 100,
            "aoColumnDefs": [
                {"bSearchable": false, "aTargets": [0]}
            ]
        });
        showNoVerboseFactReminder();
        $("#verboseFacts").change(function () {
            showNoVerboseFactReminder();
        });
    });

    function showNoVerboseFactReminder() {
        if ($('#verboseFacts').val() == 0) {
            $('#noVerboseFactReminder').show();
        } else {
            $('#noVerboseFactReminder').hide();
        }
    }

</script>
<script src="researcher-tts.js"></script>
</body>

</html>

