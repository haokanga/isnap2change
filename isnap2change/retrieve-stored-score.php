<?php
require_once("mysql-lib.php");
require_once("debug.php");
require_once("student-validation.php");
$pageName = "retrieve-stored-score";

$NUM_OF_LEVEL[] = 5;
$NUM_OF_LEVEL[] = 50;
if (!isset($_SESSION)) {
    session_start();
}
require_once('mysql-lib.php');
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["command"]) && isset($_GET["gameID"])) {
        $gameID = $_GET["gameID"];
        if ($_GET["command"] == "retrieve" && $gameID == 1) {
            if (isset($_SESSION['studentID'])) {
                $studentID = $_SESSION['studentID'];
                $scoreArray = retrieve_data();
            }
            echo "score array:" . join(',', $scoreArray);
        }
    }
}
function retrieve_data()
{
    global $NUM_OF_LEVEL, $gameID, $studentID;
    $conn = db_connect();
    for ($i = 1; $i <= $NUM_OF_LEVEL[$gameID - 1]; $i++) {
        $retrieveScorePreSql = "SELECT COUNT(*) FROM Game_Record WHERE `GameID` = ? AND `StudentID` = ? AND `Level` = ?";
        $retrieveScorePreQuery = $conn->prepare($retrieveScorePreSql);
        $retrieveScorePreQuery->execute(array($gameID, $studentID, $i));
        if ($retrieveScorePreQuery->fetchColumn() > 0) {
            $retrieveScoreSql = "SELECT GameID,StudentID,`Level`,Score FROM Game_Record WHERE `GameID` = ? AND `StudentID` = ? AND `Level` = ?";
            $retrieveScoreQuery = $conn->prepare($retrieveScoreSql);
            $retrieveScoreQuery->execute(array($gameID, $studentID, $i));
            $retrieveScoreResult = $retrieveScoreQuery->fetch(PDO::FETCH_OBJ);
            echo "<script language=\"javascript\">  console.log(\"[SUCCESS] Game Record Found. gameID: $gameID  studentid: $studentID level: $i score:" . $retrieveScoreResult->Score . "\"); </script>";
            $scoreArray[] = $retrieveScoreResult->Score;
        } else {
            echo "<script language=\"javascript\">  console.log(\"[INFO] No Game Record Found. gameID: $gameID  studentid: $studentID level: $i score:null(set to 0)\"); </script>";
            $scoreArray[] = 0;
        }
    }
    db_close($conn);
    return $scoreArray;
}

?>
