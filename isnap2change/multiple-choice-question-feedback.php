<?php
    require_once("mysql-lib.php");
	require_once('debug.php');
	$pageName = "multiple-choice-question-feedback";

	if($_SERVER["REQUEST_METHOD"] == "POST"){
		if(isset($_POST['MCQIDArr']) && isset($_POST['answerArr']) && isset($_POST['quizID']) && isset($_POST['studentID'])){
			$MCQIDArr = json_decode($_POST['MCQIDArr']);
			$answerArr = json_decode($_POST['answerArr']);	
			$quizID = $_POST['quizID'];
			$studentID = $_POST['studentID'];
		} else {
			
		}
		
	} else {
		
	}

	$conn = null;

	try {
		$conn = db_connect();

		$conn->beginTransaction();

		$threshold = count($MCQIDArr)*0.2;

		//Calculate Score
		$score = getMCQSubmissionCorrectNum($conn, $MCQIDArr, $answerArr);

		$feedback = array();
		$feedback['score'] = $score;
		$feedback['quesNum'] = count($MCQIDArr);
		$feedback['detail'] = array();

		//if pass, update database.
		if ($score >= $threshold) {

			$feedback['result'] = "pass";

			for($i=0; $i<count($MCQIDArr); $i++){
				//update MCQ_Question_Record
				updateMCQQuestionRecord($conn, $MCQIDArr[$i], $studentID, $answerArr[$i]);

				//get correcct answer and options
				$mcqDetail = getOptions($conn, $MCQIDArr[$i]);

				$feedback['detail'][$i]['MCQID'] = $MCQIDArr[$i];
				$feedback['detail'][$i]['correctAns'] = $mcqDetail[0]->CorrectChoice;
				$feedback['detail'][$i]['studentAns'] = $answerArr[$i];
				$feedback['detail'][$i]['option'] = array();
				$feedback['detail'][$i]['explanation'] = array();

				foreach($mcqDetail as $row){
					array_push($feedback['detail'][$i]['option'], $row->Content);
					array_push($feedback['detail'][$i]['explanation'], $row->Explanation);
				}
			}

			//update quiz record
			updateQuizRecord($conn, $quizID, $studentID, "GRADED");

			//update student score
			updateStudentScore($conn, $studentID);

			$conn->commit();
		} else {
			$feedback['result'] = "fail";
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