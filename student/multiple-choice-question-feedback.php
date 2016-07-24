<?php
    require_once("../mysql-lib.php");
    require_once("../debug.php");
    $pageName = "multiple-choice-question-feedback";

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

        $threshold = count($answerArr)*0.2;

        //Calculate Score
        $score = getMCQSubmissionCorrectNum($conn, $answerArr);

        $feedback = array();
        $feedback["score"] = $score;
        $feedback["quesNum"] = count($answerArr);
        $feedback["detail"] = array();

        //if pass, update database.
        if ($score >= $threshold) {

            $feedback["result"] = "pass";

            foreach ($answerArr as $mcqID => $answer) {
                //update MCQ_Question_Record
                updateMCQQuestionRecord($conn, intval($mcqID), $studentID, $answer);

                $singleDetail = array();

                //get correcct answer and options
                $mcqDetail = getOptions($conn, intval($mcqID));

                $singleDetail["MCQID"] = intval($mcqID);
                $singleDetail["correctAns"] = $mcqDetail[0]->CorrectChoice;
                $singleDetail["explanation"] = array();

                foreach($mcqDetail as $row){
                    $singleDetail["explanation"][$row->OptionID] = $row->Explanation;
                }

                array_push($feedback["detail"], $singleDetail);
            }

            //update quiz record
            updateQuizRecord($conn, $quizID, $studentID, "GRADED");

            //update student score
            updateStudentScore($conn, $studentID);

            $conn->commit();
        } else {
            $feedback["result"] = "fail";
        }
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
