<?php
session_start();
require_once("../mysql-lib.php");
require_once("../debug.php");
require_once("researcher-lib.php");
$columnName = array('QuizID', 'Week', 'TopicName', 'Points', 'Edit');

try {
    $conn = db_connect();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['update'])) {
            $update = $_POST['update'];
            if ($update == 1) {
                try {
                    $week = $_POST['week'];
                    $quizType = 'Poster';
                    $topicName = $_POST['topicName'];
                    $conn->beginTransaction();
                    $question = '';
                    $points = 0;

                    $topicID = getTopicByName($conn, $topicName)->TopicID;
                    $quizID = createQuiz($conn, $topicID, $quizType, $week);
                    createPosterSection($conn, $quizID, $question, $points);
                    createEmptyLearningMaterial($conn, $quizID);

                    $conn->commit();

                    header('Location: poster-editor.php?quizID=' . $quizID);
                } catch (Exception $e) {
                    debug_err($e);
                    $conn->rollBack();
                }
            } else if ($update == -1) {
                $quizID = $_POST['quizID'];
                deleteQuiz($conn, $quizID);
            }
        }
    }
} catch (Exception $e) {
    debug_err($e);
}

try {
    $quizResult = getPosterQuizzes($conn);
    $topicResult = getTopics($conn);
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
                <h1 class="page-header"><?php echo $pageNameForView; ?> Overview</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <?php echo $pageNameForView; ?> Information Table <span
                            class="glyphicon glyphicon-plus pull-right"
                            data-toggle="modal" data-target="#dialog"></span>
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="dataTable_wrapper">
                            <table class="table table-striped table-bordered table-hover" id="datatables">
                                <?php require_once('table-head.php'); ?>
                                <tbody>
                                <?php for ($i = 0; $i < count($quizResult); $i++) { ?>
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
                                                    echo $quizResult[$i]->$columnName[$j];
                                                else { ?>
                                                    <span class="glyphicon glyphicon-remove pull-right"
                                                          aria-hidden="true"></span>
                                                    <span class="pull-right" aria-hidden="true">&nbsp;</span>
                                                    <a href="poster-editor.php?quizID=<?php echo $quizResult[$i]->QuizID ?>">
                                                    <span class="glyphicon glyphicon-edit pull-right"
                                                          aria-hidden="true"></span>
                                                    </a>
                                                <?php } ?>
                                            </td>
                                        <?php } ?>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                        <?php require_once('quiz-overview-notification.php'); ?>
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
                <h4 class="modal-title" id="dialogTitle"></h4>
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
    $('.glyphicon-plus').on('click', function () {
        $("label").remove(".error");
        $('#dialogTitle').text("Add <?php echo $pageNameForView; ?>");
        $('#update').val(1);
        for (i = 0; i < dialogInputArr.length; i++) {
            dialogInputArr.eq(i).val('');
        }
    });
    $('.glyphicon-remove').on('click', function () {
        if (confirm('[WARNING] Are you sure to remove this quiz? If you remove one quiz. All the questions and submission of this quiz will also get deleted (not recoverable). It includes learning material, questions and options, their submissions and your grading/feedback, not only the quiz itself.')) {
            $('#update').val(-1);
            for (i = 0; i < dialogInputArr.length; i++) {
                dialogInputArr.eq(i).val($(this).parent().parent().children('td').eq(i).text().trim());
            }
            $('#submission').submit();
        }
    });
    $('#btnSave').on('click', function () {
        $('#submission').validate({
            rules: {
                week: {
                    required: true,
                    digits: true
                }
            }
        });
        $('#submission').submit();
    });
    $(document).ready(function () {
        var table = $('#datatables').DataTable({
            responsive: true,
            "order": [[1, "asc"]],
            "pageLength": 50,
            "aoColumnDefs": [
                {"bSearchable": false, "aTargets": [0]}
            ]
        })
    });
</script>
</body>

</html>
