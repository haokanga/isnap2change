<?php
require_once ("student-validation.php");
require_once("../mysql-lib.php");
require_once("../debug.php");

$pageName = "numeric-question-feedback";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST['student_id']) && isset($_POST['quiz_id']) && isset($_POST['answer_arr']) && isset($_POST['type'])){
        $studentID = $_POST['student_id'];
        $quizID = $_POST['quiz_id'];
        $answerArr = json_decode($_POST['answer_arr']);
        $type = $_POST['type'];
    } else{

    }
} else{

}

define("COST_CALCULATOR_CORRECT_COUNT", 3);
define("STANDARD_DRINKING_TOOL_CORRECT_COUNT", 4);

$costCalculatorCorrectAns = array("45625.00", "182500.00", "365000.00");
$drinkingToolCorrectAns = array("95", "355", "35", "110");

switch($type) {
    case "cost_calculator":
        processStuAns($answerArr, $costCalculatorCorrectAns, COST_CALCULATOR_CORRECT_COUNT);
        break;
    case "standard_drinking_tool":
        processStuAns($answerArr, $drinkingToolCorrectAns, STANDARD_DRINKING_TOOL_CORRECT_COUNT);
        break;
}

function processStuAns($answerArr, $correctAns, $fullMarkCount) {
    $feedback = array();
    $feedback["detail"] = array();

    $correctCount = 0;

    for($i = 0; $i < count($answerArr); $i++) {
        if($answerArr[$i] == $correctAns[$i]) {
            $correctCount++;
        } else {
            array_push($feedback["detail"], $i);
        }
    }

    if($correctCount == $fullMarkCount) {
        $feedback["result"] = "pass";

        $conn = null;

        try {
            $conn = db_connect();

            $conn->beginTransaction();

            //update Quiz_Record
            updateQuizRecord($conn, $GLOBALS['quizID'], $GLOBALS['studentID'], "GRADED");

            //update student score
            updateStudentScore($conn, $GLOBALS['studentID']);

            $conn->commit();
        } catch(Exception $e) {
            if($conn != null) {
                $conn->rollBack();
                db_close($conn);
            }

            debug_err($GLOBALS['pageName'], $e);
            $feedback["message"] = $e->getMessage();
            echo json_encode($feedback);
            exit;
        }

        db_close($conn);

    } else {
        $feedback["result"] = "fail";
    }

    $feedback["message"] = "success";
    echo json_encode($feedback);
}

?>