<?php	
	//if true, echo debug output in dev mode, else production mode
	$DEBUG_MODE = true;
    //session and import
	session_start();
    require_once("connection.php");
	
    if($DEBUG_MODE){
   //     echo "<script language=\"javascript\">  console.log(\"SUBMISSION.\"); </script>";
    }
	
	if(isset($_SESSION["studentid"])){
		$studentid = $_SESSION["studentid"];
	} else {
		
	}
	
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		
		if(isset($_POST['MCQIDArr']) && isset($_POST['answerArr']) && isset($_POST['quizid'])){
			$MCQIDArr = json_decode($_POST['MCQIDArr']);
			$answerArr = json_decode($_POST['answerArr']);	
			$quizid = $_POST['quizid'];
		} else {
			
		}
		
	} else {
		
	}
	
	$conn = db_connect();
	        
    $threshold = count($MCQIDArr)*0.2;
    $score = 0;
            
	//Calculate Score
	for($i=0; $i<count($MCQIDArr); $i++){
		$sql = $conn->prepare('SELECT COUNT(*) FROM MCQ_Question WHERE `MCQID` = BINARY :mcqid AND `CorrectChoice` = BINARY :correctchoice');
		$sql->bindParam(':mcqid', $MCQIDArr[$i]);
		$sql->bindParam(':correctchoice', $answerArr[$i]);
		$sql->execute();
		$score += $sql->fetchColumn();
	}		
	if($DEBUG_MODE){
	//	echo "<script language=\"javascript\">  console.log(\"Score: $score\"); </script>";
	}
	
	$feedback = array();
	$feedback['score'] = $score; 
	$feedback['quesNum'] = count($MCQIDArr);
	$feedback['detail'] = array();
            
	//SQL UPDATE STATEMENT
	if ($score >= $threshold) {
		
		$feedback['result'] = "pass";
		
		$score_sql = "SELECT Points 
					  FROM MCQ_Section
					  WHERE QuizID = ?;";
					  
		$score_query = $conn -> prepare($score_sql);  
		$score_query -> execute(array($quizid));	
		$score_res = $score_query -> fetch(PDO::FETCH_OBJ);		
		
		//UPDATE Quiz_Record
		$status = "GRADED";
		$update_stmt = "REPLACE INTO Quiz_Record(QuizID, StudentID, Status, Score)
						VALUES (?,?,?,?);";			
		$update_stmt = $conn->prepare($update_stmt);                            
		if(! $update_stmt -> execute(array($quizid, $studentid, $status, $score_res -> Points))){
		//	echo "<script language=\"javascript\">  alert(\"Error occurred to submit your answer. Report this bug to reseachers.\"); </script>";
		} else{ 
		//	echo "<script language=\"javascript\">  console.log(\"Quiz Passed\"); </script>";
		}
		
		$update_stmt = "REPLACE INTO MCQ_Question_Record(StudentID, MCQID, Choice)
							 VALUES (?,?,?);";			
		$update_stmt = $conn->prepare($update_stmt);   
			
		$mcqGradeSql = "SELECT CorrectChoice
						FROM   MCQ_Question
						WHERE  MCQID = ?";			
		$mcqGradeQuery = $conn->prepare($mcqGradeSql);    
	
		$mcqExplanationSql = "SELECT Explanation, Content
							  FROM   `Option`
							  WHERE   MCQID = ?";	
		$mcqExplanationQuery = $conn->prepare($mcqExplanationSql);
		
		//UPDATE MCQ_Question_Record
		for($i=0; $i<count($MCQIDArr); $i++){
	
			if(! $update_stmt -> execute(array($studentid, $MCQIDArr[$i], $answerArr[$i]))){
			//	echo "<script language=\"javascript\">  alert(\"Error occurred to submit your answer. Report this bug to reseachers.\"); </script>";
			}
			
			$mcqGradeQuery -> execute(array($MCQIDArr[$i]));	
			$mcqGradeRes = $mcqGradeQuery -> fetch(PDO::FETCH_OBJ);
		
			$mcqExplanationQuery -> execute(array($MCQIDArr[$i]));	
			$mcqExplanationRes = $mcqExplanationQuery -> fetchAll(PDO::FETCH_OBJ);

			$feedback['detail'][$i]['MCQID'] = $MCQIDArr[$i];
			$feedback['detail'][$i]['correctAns'] = $mcqGradeRes->CorrectChoice;
			$feedback['detail'][$i]['studentAns'] = $answerArr[$i];
			$feedback['detail'][$i]['option'] = array();
			$feedback['detail'][$i]['explanation'] = array();
			
			foreach($mcqExplanationRes as $row){
				array_push($feedback['detail'][$i]['option'], $row->Content);  
				array_push($feedback['detail'][$i]['explanation'], $row->Explanation);
			}   
			
		}
		
	} else {
		$feedback['result'] = "fail";
	} 
		
	echo json_encode($feedback);
	
	db_close($conn);	
?>