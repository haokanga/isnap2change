<?php
require_once("../mysql-lib.php");
require_once("../debug.php");
session_start();

if (isset($_SESSION['studentID'])) {
    $studentID = $_SESSION['studentID'];
} else {
    if ($DEBUG_MODE) {
        $studentID = 1;
    }
}
$pageName = "retrieve-stored-score";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $conn = db_connect();
    if (isset($_GET["command"]) && isset($_GET["gameID"]) && isset($_GET["secretKey"])) {
        if ($_GET["secretKey"] == "ISNAPSecretKey") {
            $gameID = $_GET["gameID"];

            if ($_GET["command"] == "get_week") {
                // return current week to unlock levels
                echo getStudentWeek($conn, $studentID);
            } else if ($_GET["command"] == "retrieve") {
                // retrieve high score
                $scoreArray = getStudentGameScores($conn, $gameID, $studentID);
                echo join(',', $scoreArray);

            } else if ($_GET["command"] == "upload") {
                // update high score
                if (isset($_GET["score"])) {
                    $score = $_GET["score"];
                    updateStudentGameScores($conn, $gameID, $studentID, $score);
                }
            }
        }
    }
    db_close($conn);
}
?>