<?php
session_start();
require_once("../mysql-lib.php");
require_once("../debug.php");
require_once("researcher-lib.php");
$columnName = array('RecipeID', 'RecipeName', 'MealType', 'PreparationTime', 'CookingTime', 'Serves', 'Edit');


try {
    $conn = db_connect();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['update'])) {
            $update = $_POST['update'];
            if ($update == 1) {

                $cookingTime = $_POST['cookingTime'];
                $mealType = $_POST['mealType'];
                $preparationTime = $_POST['preparationTime'];
                $recipeName = $_POST['recipeName'];
                $serves = $_POST['serves'];

                createRecipe($conn, $cookingTime, $mealType, $preparationTime, $recipeName, $serves);

            } else if ($update == -1) {
                $recipeID = $_POST['recipeID'];
                deleteRecipe($conn, $recipeID);
            }
        }
    }
} catch (Exception $e) {
    debug_err($e);
}


try {
    $recipeResult = getRecipes($conn);
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
    <?php require_once('navigation.php'); ?>
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header"><?php echo $pageNameForView; ?> Overview
                </h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <?php echo $pageNameForView; ?> Information Table <span
                            class="glyphicon glyphicon-plus pull-right" data-toggle="modal"
                            data-target="#dialog"></span>
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="dataTable_wrapper">
                            <table class="table table-striped table-bordered table-hover" id="datatables">
                                <?php require_once('table-head.php'); ?>
                                <tbody>
                                <?php for ($i = 0; $i < count($recipeResult); $i++) { ?>
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
                                                    echo $recipeResult[$i]->$columnName[$j];
                                                else { ?>
                                                    <span class="glyphicon glyphicon-remove pull-right"
                                                          aria-hidden="true"></span>
                                                    <span class="pull-right" aria-hidden="true">&nbsp;</span>
                                                    <a href="recipe-editor.php?recipeID=<?php echo $recipeResult[$i]->RecipeID ?>">
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
                        <div class="well row">
                            <h4><?php echo $pageNameForView; ?> Overview Notification</h4>
                            <div class="alert alert-info">
                                <p>View recipes by filtering or searching. You can create/update/delete any recipe.</p>
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
                <h4 class="modal-title" id="dialogTitle"></h4>
            </div>
            <div class="modal-body">
                <form id="submission" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <!--if 1, insert; else if -1 delete;-->
                    <input type=hidden name="update" id="update" value="1" required>
                    <label for="recipeID" style="display:none">recipeID</label>
                    <input type="text" class="form-control dialoginput" id="recipeID" name="recipeID"
                           style="display:none">

                    <?php
                    $dialogInputArr = array('RecipeName', 'MealType', 'PreparationTime', 'CookingTime', 'Serves');
                    for ($i = 0; $i < count($dialogInputArr); $i++) { ?>
                        <label
                            for="<?php echo lcfirst($dialogInputArr[$i]) ?>"><?php echo $dialogInputArr[$i] ?></label>
                        <input type="text" class="form-control dialoginput"
                               id="<?php echo lcfirst($dialogInputArr[$i]) ?>"
                               name="<?php echo lcfirst($dialogInputArr[$i]) ?>"
                               placeholder="Input <?php echo $dialogInputArr[$i] ?>" required>
                        <br>
                    <?php } ?>

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
        if (confirm('[WARNING] Are you sure to remove this recipe?')) {
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
                preparationTime: {
                    required: true,
                    digits: true
                },
                cookingTime: {
                    required: true,
                    digits: true
                },
                serves: {
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
