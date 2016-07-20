<?php
generatePosterRecordInFile();

function generatePosterRecordInFile()
{
    $conn = null;
    try {
        $conn = db_connect();

        foreach (glob("../poster_img/*.png") as $imageUrl) {
            $col = explode("_", explode(".", explode("/", $imageUrl, 3)[2])[0], 3);
            $studentID = $col[0];
            $quizID = $col[1];
            $status = "UNGRADED";

            $conn->beginTransaction();
            updatePosterSubmission($conn, $quizID, $studentID, '', $imageUrl);
            updateQuizRecord($conn, $quizID, $studentID, $status);

            $conn->commit();
            echo "[SUCCESS]";
            echo "INSERT INTO `isnap2changedb`.`poster_record` (`StudentID`, `QuizID`, 'ZwibblerDoc',`ImageURL`) VALUES ($studentID,$quizID,'', $imageUrl);";
            echo "<br>";
            echo "[SUCCESS]";
            echo "INSERT INTO `isnap2changedb`.`quiz_record` (`StudentID`, `QuizID`, `Status`) VALUES ($studentID,$quizID, '$status');";
            echo "<br>";

        }
    } catch (Exception $e) {
        if ($conn != null) {
            $conn->rollBack();
            db_close($conn);
        }
        debug_err($e);
    }

    db_close($conn);
}


?>