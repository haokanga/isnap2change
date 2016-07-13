<?php
	require_once ("student-validation.php");
	require_once("../mysql-lib.php");
	require_once("../debug.php");

	$pageName = "cost-calculator-feedback";
	
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		if(isset($_POST['studentID']) && isset($_POST['quizID']) && isset($_POST['answerArr'])){
			$studentID = $_POST['studentID'];
			$quizID = $_POST['quizID'];
			$answerArr = json_decode($_POST['answerArr']);
		} else{
			
		}
	} else{
		
	}
	
	$feedback = array();
	$feedback["detail"] = array();
	
	$correctAns = array("45625.00", "182500.00", "365000.00");
	
	$correctCount = 0;
	
	for($i = 0; $i < count($answerArr); $i++) {
		if($answerArr[$i] == $correctAns[$i]) {
			$correctCount++;
		} else {
			array_push($feedback["detail"], $i);
		}
	}
	
	if($correctCount == 3) {
		$feedback["result"] = "pass";
		
		$conn = null;
		
		try {
			$conn = db_connect();
			
			$conn->beginTransaction();
			
			//update Quiz_Record	
			updateQuizRecord($conn, $quizID, $studentID, "GRADED");
			
			//update student score
			updateStudentScore($conn, $studentID);
			
			$conn->commit();
		} catch(Exception $e) {
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
		
	} else {
		$feedback["result"] = "fail";
	}
	
	$feedback["message"] = "success";
	echo json_encode($feedback);
	
?>