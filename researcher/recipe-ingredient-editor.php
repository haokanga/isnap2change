<?php
session_start();
require_once("../mysql-lib.php");
require_once("../debug.php");
require_once("researcher-lib.php");
$parentPage = 'Location: recipe.php';

try {
    if (isset($_GET['recipeID'])) {
        $recipeID = $_GET['recipeID'];
        $phpSelf = $pageName . '.php?recipeID=' . $recipeID;
    } else
        throw new Exception('Invalid GET parameters: recipeID');

    $conn = db_connect();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['update'])) {
            $update = $_POST['update'];
            if ($update == 1) {
                $recipeID = $_POST['recipeID'];
                $content = $_POST['content'];
                createRecipeIngredient($conn, $recipeID, $content);
            } else if ($update == 0) {
                $ingredientID = $_POST['ingredientID'];
                $content = $_POST['content'];
                updateRecipeIngredient($conn, $ingredientID, $content);
            } else if ($update == -1) {
                $ingredientID = $_POST['ingredientID'];
                deleteRecipeIngredient($conn, $ingredientID);
            }
        }
    }

    if (isset($_GET['recipeID'])) {
        $ingredientResult = getRecipeIngredients($conn, $recipeID);
    }

    db_close($conn);
} catch (Exception $e) {
    debug_err($e);
}

?>
<!DOCTYPE html>
<html lang="en">

<!-- Header Library -->
<?php require_once('header-lib.php'); ?>

<body>
<div class="panel panel-default">
    <div class="panel-heading">
        Ingredients
        <span class="glyphicon glyphicon-plus pull-right" data-toggle="modal"
              data-target="#dialog"></span>
    </div>
    <!-- /.panel-heading -->
    <div class="panel-body">
        <div class="dataTable_wrapper">
            <table class="table table-striped table-bordered table-hover" id="datatables">
                <?php
                $columnName = array("IngredientID", "Content", "Edit");
                require('table-head.php'); ?>
                <tbody>
                <?php for ($i = 0; $i < count($ingredientResult); $i++) { ?>
                    <tr class="<?php if ($i % 2 == 0) {
                        echo "odd";
                    } else {
                        echo "even";
                    } ?>">
                        <td style="display:none"><?php echo $ingredientResult[$i]->IngredientID; ?></td>
                        <td><?php echo $ingredientResult[$i]->Content ?></td>
                        <td>
                                            <span class="glyphicon glyphicon-remove pull-right "
                                                  aria-hidden="true"></span>
                            <span class="pull-right" aria-hidden="true">&nbsp;</span>
                            <span class="glyphicon glyphicon-edit pull-right"
                                  aria-hidden="true"></span>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <!-- /.table-responsive -->
    </div>
    <!-- /.panel-body -->
</div>
<!-- /.panel -->
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
                <form id="submission" method="post" action="<?php echo $phpSelf; ?>">
                    <input type=hidden name="update" id="update" value="1" required>
                    <label for="ingredientID" style="display:none">ingredientID</label>
                    <input type="text" class="form-control dialoginput" id="ingredientID" name="ingredientID"
                           style="display:none">
                    <label for="content">Content</label>
                    <input type="text" class="form-control dialoginput" id="content" name="content"
                           placeholder="Input Content" required>
                    <br>
                    <label for="recipeID" style="display:none">recipeID</label>
                    <input type="text" class="form-control dialoginput" id="recipeID" name="recipeID"
                           style="display:none"
                           value="<?php echo $recipeID; ?>" required>
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
        for (i = 0; i < dialogInputArr.length - 1; i++) {
            dialogInputArr.eq(i).val('');
        }
    });
    $('td > .glyphicon-edit').on('click', function () {
        $('#dialogTitle').text("Edit <?php echo $pageNameForView; ?>");
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
            "order": [[0, "asc"]],
            "pageLength": 100,
            "aoColumnDefs": [
                {"bSearchable": false, "aTargets": [0]}
            ]
        });
    });
</script>
</body>
</html>
