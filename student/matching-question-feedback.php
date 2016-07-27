<?php
    require_once("../mysql-lib.php");
    require_once("../debug.php");
    $pageName = "matching-question-feedback";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["student_id"]) && isset($_POST["quiz_id"]) && isset($_POST["answer_arr"])) {
            $studentID = $_POST["student_id"];
            $quizID = $_POST["quiz_id"];
            $answerArr = json_decode($_POST["answer_arr"], true);
        } else {

        }
    } else {

    }

    $conn = null;

    try {
        $conn = db_connect();

        $conn->beginTransaction();

        $feedback["detail"] = array();

        $count = 0;

        foreach ($answerArr as $matchingID => $answer) {
            $wrongAns = checkMatchingAnswer($conn, intval($matchingID), $answer);

            if (count($wrongAns) == 0){
                $count++;
            } else {
                foreach ($wrongAns as $wrongOption) {
                    array_push($feedback["detail"], $wrongOption);
                }
            }
        }

        if ($count == count($answerArr)) {
            $feedback["result"] = "pass";

            //update quiz record
            updateQuizRecord($conn, $quizID, $studentID, "GRADED");

            //update student score
            updateStudentScore($conn, $studentID);
        } else {
            $feedback["result"] = "fail";
        }

        $conn->commit();

    } catch(Exception $e){
        if($conn != null) {
            $conn->rollback();
            db_close($conn);
        }

        debug_err($pageName, $e);
        $feedback["message"] = $e->getMessage();
        echo json_encode($feedback);
        exit;

    }

    db_close($conn);
    $feedback["message"] = "success";
    echo json_encode($feedback);
?>
