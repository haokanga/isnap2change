<?php
	
	require_once("connection.php");
	require_once("mysql-lib.php");
	require_once("debug.php");

	$pageName = "cost-calculator-feedback";
	
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		if(isset($_POST['studentid']) && isset($_POST['quizid']) && isset($_POST['answerArr'])){
			$studentid = $_POST['studentid'];
			$quizid = $_POST['quizid'];
			$answerArr = json_decode($_POST['answerArr']);
		} else {
			
		}
	} else {
		
	}
	
	$feedback = array();
	$feedback["detail"] = array();
	
	$correctAns = array("12.12", "13.13", "14.14");
	
	$correctCount = 0;
	
	for($i = 0; $i < count($answerArr); $i++) {
		if($answerArr[$i] == $correctAns[$i]) {
			$correctCount++;
		} else {
			array_push($feedback["detail"], ($i+1));
		}
	}
	
	if($correctCount == 3) {
		$feedback["result"] = "pass";
		
		$conn = null;
		
		try {
			$conn = db_connect();
			
			$conn->beginTransaction();
			
			//UPDATE Quiz_Record	
			$status = "GRADED";
			
			$costCalSubmitSql = "INSERT INTO Quiz_Record(QuizID, StudentID, Status)
								 VALUES (?,?,?) ON DUPLICATE KEY UPDATE Status = ?;";			
		
			$costCalSubmitQuery = $conn->prepare($costCalSubmitSql);                            
			$costCalSubmitQuery->execute(array($quizid, $studentid, $status, $status));
			
			//UPDATE Student Score
			updateStudentScore($conn, $studentid);
			
			$conn->commit();
		} catch(PDOException $e) {
			if($conn != null) {
				$conn->rollback();
				db_close($conn);
			}
			
			debug_pdo_err($pageName, $e);
			$feedback["message"] = "Failed to update database";
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