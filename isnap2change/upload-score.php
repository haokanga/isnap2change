<?php
require_once("../mysql-lib.php");
require_once("../debug.php");
require_once("student-validation.php");
$pageName = "retrieve-stored-score";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["score"]) && isset($_GET["gameID"])) {
        $score = $_GET["score"];
        $gameID = $_GET["gameID"];
        $conn = db_connect();
        updateStudentGameScores($conn, $gameID, $studentID, $score);
        db_close($conn);
    }
}


?>
<html>
<head>
</head>
<body>
<!--Test Code-->
<!--
<div id="a" align="center">
<form id="quiz" action="<?php echo $_SERVER['PHP_SELF']; ?>" method=get>
Score:<input name="score[]" value="15">
Score:<input name="score[]" value="15">
Score:<input name="score[]" value="15">
Score:<input name="score[]" value="15">
Score:<input name="score[]" value="15">
<input type="submit" name='submit' value="Submit" class='submit'/>
</form>
-->
</body>
</html>
