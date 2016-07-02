<?php
require_once("../mysql-lib.php");
require_once("../debug.php");
require_once("student-validation.php");
$pageName = "retrieve-stored-score";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["command"]) && isset($_GET["gameID"])) {
        $gameID = $_GET["gameID"];
        if ($_GET["command"] == "retrieve") {
            $conn = db_connect();
            $scoreArray = getStudentGameScores($conn, $gameID, $studentID);
            db_close($conn);
            echo "score array:" . join(',', $scoreArray);
        }
    }
}


?>
