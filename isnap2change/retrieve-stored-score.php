<?php
require_once("mysql-lib.php");
require_once("debug.php");
require_once("student-validation.php");
$pageName = "retrieve-stored-score";

$NUM_OF_LEVEL[] = 5;
$NUM_OF_LEVEL[] = 50;

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["command"]) && isset($_GET["gameID"])) {
        $gameID = $_GET["gameID"];
        $scoreArray = array_fill(0, $NUM_OF_LEVEL[$gameID - 1], 0);
        if ($_GET["command"] == "retrieve") {
            if (isset($_SESSION['studentID'])) {
                $studentID = $_SESSION['studentID'];

                $conn = db_connect();
                $scoreArray = getStudentGameScores($conn, $NUM_OF_LEVEL, $gameID, $studentID);
                db_close($conn);
            }
            echo "score array:" . join(',', $scoreArray);
        }
    }
}


?>
