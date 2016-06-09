<?php
	
	require_once("connection.php");
	require_once("mysql-lib.php");

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
	
	$correctAns = [];
	
	$count = 0;
	
	for($i = 0; $i < count($answerArr); $i++) {
		if($ansArr[$i] == $correctAns[$i]) {
			count++;
		} else {
			array_push($feedback["detail"], ($i+1));
		}
	}
	
	if(count == 3) {
		$feedback["result"] = "pass";
		
		$conn = db_connect();
		
		if($conn == null) {
			$feedback["message"] = "Failed to connect database";
			exit;
		}
		
		try {
			$conn->beginTransaction();
			
			//UPDATE Quiz_Record	
			$status = "GRADED";
			
			$costCalSubmitSql = "INSERT INTO Quiz_Record(QuizID, StudentID, Status)
								 VALUES (?,?,?) ON DUPLICATE KEY UPDATE Status = ?;";			
		
			$costCalSubmitQuery = $conn->prepare($posterQuizSaveSql);                            
			$costCalSubmitQuery->execute(array($quizid, $studentid, $status, $status));
			
			$conn->commit();
			
		} catch(Exception $e) {
			$conn->rollback();
			$feedback["message"] = "Failed to update database";
			exit;
		}
		
		db_close($conn);
		
	} else {
		$feedback["result"] = "fail";
	}
	
	echo json_encode($feedback);
	
?>