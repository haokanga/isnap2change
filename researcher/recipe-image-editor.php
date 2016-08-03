<?php
session_start();
require_once("../mysql-lib.php");
require_once("../debug.php");
require_once("researcher-lib.php");

try {
    $conn = db_connect();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['richContentTextArea'])) {
            $conn = db_connect();
            $content = $_POST['richContentTextArea'];
            $recipeID = $_POST['recipeID'];
            updateRecipeImage($conn, $recipeID, $content);
        }
    }
} catch (Exception $e) {
    debug_err($e);
}

try {
    if (isset($_GET['recipeID'])) {
        $recipeID = $_GET['recipeID'];
        $recipeImage = getRecipeImage($conn, $recipeID);
        $phpSelf = $pageName . '.php?recipeID=' . $recipeID;
    }
} catch (Exception $e) {
    debug_err($e);
}

?>

<!DOCTYPE html>
<html>
<head>
    <!-- Bootstrap Core CSS -->
    <link href="../bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
    <script>
        tinymce.EditorManager.editors = []; //remove the old instances
        tinymce.init({
            selector: 'textarea',
            height: 500,
            theme: 'modern',
            plugins: [
                'advlist autolink lists link image charmap preview hr anchor pagebreak',
                'searchreplace wordcount visualblocks visualchars code fullscreen',
                'insertdatetime media nonbreaking save table contextmenu directionality',
                'emoticons template paste textcolor colorpicker textpattern imagetools'
            ],
            toolbar1: 'insertfile undo redo | styleselect | bold italic forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | emoticons link image media | preview',
            imagetools_toolbar: "rotateleft rotateright | flipv fliph | editimage imageoptions",
            image_advtab: true,
            browser_spellcheck: true,
            templates: [
                /**
                 * infograph list
                 * Add new infograph:
                 * 1.   put the file under /infograph
                 * 2.   {title: '[TITLE]', url: '../infograph/[FILENAME].html'}
                 * (remember to add comma after right bracket if needed to keep sane JSON format)
                 */
                {title: 'Infograph Demo', url: '../infograph/demo.html'},
                {title: 'Yet Another Demo', url: '../infograph/yet-another-demo.html'}
            ],
            content_css: [
                '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                '//www.tinymce.com/css/codepen.min.css'
            ]
        });
    </script>
</head>
<body>
<form method="post" action="<?php echo $phpSelf ?>">
    <label for="RecipeID" style="display:none">RecipeID</label>
    <input type="text" class="form-control" id="RecipeID" name="recipeID" style="display:none"
           value="<?php echo $recipeID; ?>" required>
    <textarea name="richContentTextArea">
        <?php echo $recipeImage; ?>
    </textarea>
    <input type="submit" name='submitbutton' value="Save" class='submit'/> <span
        class="glyphicon glyphicon-info-sign"></span><b> Ctrl + S</b><br>
</form>

<?php
db_close($conn);
?>

</body>
</html>