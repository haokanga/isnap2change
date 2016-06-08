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
		
		
		
		
	} else {
		$feedback["result"] = "fail";
	}
	
	echo json_encode($feedback);
	
?>