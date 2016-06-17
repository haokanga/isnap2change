<?php
    require_once("mysql-lib.php");
    require_once('debug.php');
    $pageName = "short-answer-question-feedback";

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(isset($_POST['SAQIDArr']) && isset($_POST['answerArr']) && isset($_POST['quizID']) && isset($_POST['studentID']) && isset($_POST['action'])){
            $SAQIDArr = json_decode($_POST['SAQIDArr']);
            $answerArr = json_decode($_POST['answerArr']);
            $quizID = $_POST['quizID'];
            $studentID = $_POST['studentID'];
            $action = $_POST['action'];
        } else {

        }
    } else {

    }

    $feedback = array();
    $conn = null;

    try{
        $conn = db_connect();

        $conn->beginTransaction();

        //update SAQ_Question_Record
        for ($i = 0; $i < count($SAQIDArr); $i++){
            updateSAQQuestionRecord($conn, $SAQIDArr[$i], $studentID, $answerArr[$i]);
        }

        //update Quiz_Record
        if($action == "SAVE"){
            $status = "UNSUBMITTED";
        }

        if($action == "SUBMIT"){
            $status = "UNGRADED";
        }

        updateQuizRecord($conn, $quizID, $studentID, $status);

        $conn->commit();

    } catch(Exception $e){
        if($conn != null) {
            $conn->rollBack();
            db_close($conn);
        }

        debug_err($pageName, $e);
        $feedback["message"] = $e->getMessage();
        echo json_encode($feedback);
        exit;
    }

    db_close($conn);
    $feedback["message"] = "success";
    $feedback["action"] = $action;
    echo json_encode($feedback);
?>