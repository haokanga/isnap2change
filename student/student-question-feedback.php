<?php
    require_once("../mysql-lib.php");
    require_once("../debug.php");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['action'])) {
            $action = $_POST['action'];

            if ($action == "UPDATE") {
                if (isset($_POST['student_id']) && isset($_POST['subject']) && isset($_POST['content']) && isset($_POST['send_time'])) {
                    $studentID = $_POST['student_id'];
                    $subject = $_POST['subject'];
                    $content = $_POST['content'];
                    $sendTime = $_POST['send_time'];
                } else {

                }
            }

            if ($action == "DELETE") {
                if (isset($_POST['question_id'])) {
                    $questionID = $_POST['question_id'];
                } else {

                }
            }

            if ($action == "VIEW") {
                if (isset($_POST['question_id'])) {
                    $questionID = $_POST['question_id'];
                } else {

                }
            }
        } else {

        }
    } else {

    }

    $feedback = array();
    $conn = null;

    try {
        $conn = db_connect();

        $conn->beginTransaction();

        if ($action == "UPDATE") {
            //update Student Question
            updateStudentQuestion($conn, $studentID, $subject, $content, $sendTime);
        }

        if ($action == "DELETE") {
            //delete Student Question
            deleteStudentQuestion($conn, $questionID);
            $feedback["questionID"] = $questionID;
        }

        if ($action == "VIEW") {
            updateStudentQuesViewedStatus($conn, $questionID);
        }

        $conn->commit();
    } catch(Exception $e) {
        if ($conn != null) {
            $conn->rollBack();
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