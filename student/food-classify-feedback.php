<?php
    require_once("../mysql-lib.php");
    require_once("../debug.php");

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

        $correctAns = array("1"=>array(2, 3), "2"=>array(4, 8), "3"=>array(9, 10), "4"=>array(5, 12), "5"=>array(1, 11), "6"=>array(6, 7));

        $count = 0;

        foreach ($answerArr as $matchingID => $answer) {
            $wrongAns = array_diff($correctAns[$matchingID], $answer);

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

        debug_err($e);
        $feedback["message"] = $e->getMessage();
        echo json_encode($feedback);
        exit;

    }

    db_close($conn);
    $feedback["message"] = "success";
    echo json_encode($feedback);
?>