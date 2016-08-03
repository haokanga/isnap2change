<?php
session_start();
require_once("../mysql-lib.php");
require_once("../debug.php");
require_once("researcher-lib.php");
$parentPage = 'Location: recipe.php';

try {
    $conn = db_connect();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['metadataUpdate'])) {
            $metadataUpdate = $_POST['metadataUpdate'];
            if ($metadataUpdate == 0) {
                try {
                    $recipeID = $_POST['recipeID'];
                    $cookingTime = $_POST['cookingTime'];
                    $mealType = $_POST['mealType'];
                    $preparationTime = $_POST['preparationTime'];
                    $recipeName = $_POST['recipeName'];
                    $serves = $_POST['serves'];
                    $source = $_POST['source'];
                    updateRecipe($conn, $recipeID, $cookingTime, $mealType, $preparationTime, $recipeName, $serves, $source);
                } catch (Exception $e) {
                    debug_err($e);
                }
            } else if ($metadataUpdate == -1) {
                $recipeID = $_POST['recipeID'];
                deleteRecipe($conn, $recipeID);
                header($parentPage);
            }
        }
    }
} catch (Exception $e) {
    debug_err($e);
}

try {
    if (isset($_GET['recipeID'])) {
        $recipeID = $_GET['recipeID'];
        $recipeResult = getRecipe($conn, $recipeID);
        $ingredientResult = getRecipeIngredients($conn, $recipeID);
        $stepResult = getRecipeSteps($conn, $recipeID);
        $nutritionResult = getRecipeNutritions($conn, $recipeID);
        $phpSelf = $pageName . '.php?recipeID=' . $recipeID;
    }
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
                <h1 class="page-header"><?php echo $pageNameForView; ?> Editor</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">

                <!-- MetaData -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Recipe MetaData
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <form id="metadata-submission" method="post" action="<?php echo $phpSelf; ?>">
                            <!--if 0 update; else if -1 delete;-->
                            <input type=hidden name="metadataUpdate" id="metadataUpdate" value="1" required>
                            <label for="recipeID" style="display:none">RecipeID</label>
                            <input type="text" class="form-control" id="recipeID" name="recipeID" style="display:none"
                                   value="<?php echo $recipeResult->RecipeID; ?>">
                            <br>

                            <?php
                            $dialogInputArr = array('RecipeName', 'Source', 'MealType', 'PreparationTime', 'CookingTime', 'Serves');
                            for ($i = 0; $i < count($dialogInputArr); $i++) { ?>
                                <label
                                    for="<?php echo lcfirst($dialogInputArr[$i]) ?>"><?php echo $dialogInputArr[$i] ?></label>
                                <input type="text" class="form-control"
                                       id="<?php echo lcfirst($dialogInputArr[$i]) ?>"
                                       name="<?php echo lcfirst($dialogInputArr[$i]) ?>"
                                       placeholder="Input <?php echo $dialogInputArr[$i] ?>"
                                       value="<?php echo $recipeResult->$dialogInputArr[$i]; ?>"
                                       required>
                                <br>
                            <?php }
                            if (strlen($recipeResult->Source) == 0) { ?>
                                <div class="alert alert-danger">
                                    <p><strong>Reminder</strong> : You have not added source!
                                </div>
                            <?php } ?>
                            <!--
                            <label for="points">Points</label>
                            <input type="text" class="form-control" id="points" name="points" placeholder="0"
                                   value="<?php echo $recipeResult->Points; ?>" required>
                            <br>
                            -->
                        </form>
                        <!--edit metadata-->
                        <span class="glyphicon glyphicon-remove pull-right" id="metadata-remove"
                              aria-hidden="true"></span><span class="pull-right" aria-hidden="true">&nbsp;</span><span
                            class="glyphicon glyphicon-floppy-saved pull-right" id="metadata-save"
                            aria-hidden="true"></span>
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->

                <?php require_once('recipe-image-editor-iframe.php'); ?>

                <!-- Ingredients -->
                <?php require_once('recipe-ingredient-editor-iframe.php'); ?>

            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /#page-wrapper -->
</div>
<!-- /#wrapper -->

<!-- SB Admin Library -->
<?php require_once('sb-admin-lib.php'); ?>
<!-- Page-Level Scripts -->
<script>
    //DO NOT put them in $(document).ready() since the table has multi pages
    $('div > .glyphicon-remove').on('click', function () {
        if (confirm('[WARNING] Are you sure to remove this recipe? If you remove one recipe. All the questions and submission of this recipe will also get deleted (not recoverable). It includes learning material, questions, their submissions and your grading/feedback, not only the recipe itself.')) {
            $('#metadataUpdate').val(-1);
            $('#metadata-submission').submit();
        }
    });
    $(document).ready(function () {
        $('#metadata-save').on('click', function () {
            $('#metadataUpdate').val(0);
            $('#metadata-submission').validate({
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
            $('#metadata-submission').submit();
        });
    });
</script>
</body>
</html>
