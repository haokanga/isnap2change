<?php	
	//if true, echo debug output in dev mode, else production mode
	$DEBUG_MODE = true;
    //session and import
	session_start();
    require_once("connection.php");
	
    if($DEBUG_MODE){
        echo "<script language=\"javascript\">  console.log(\"SUBMISSION.\"); </script>";
    }
	
	if(isset($_SESSION["studentid"])){
		$studentid = $_SESSION["studentid"];
	} else {
		
	}
	
	$DEBUG_MODE = true;
	require_once("connection.php");
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
		echo "<script language=\"javascript\">  console.log(\"Score: $score\"); </script>";
	}
            
	//SQL UPDATE STATEMENT
	if ($score >= $threshold) {
		
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
			echo "<script language=\"javascript\">  alert(\"Error occurred to submit your answer. Report this bug to reseachers.\"); </script>";
		} else{ 
			echo "<script language=\"javascript\">  console.log(\"Quiz Passed\"); </script>";
		}
		//UPDATE MCQ_Question_Record
		for($i=0; $i<count($MCQIDArr); $i++){
			if(!isset($answerArr[$i])){
				//SQL UPDATE STATEMENT with Choice (assigned)
				$update_stmt = "REPLACE INTO MCQ_Question_Record(StudentID, MCQID, Choice)
							 VALUES (?,?,?);";			
				$update_stmt = $conn->prepare($update_stmt);                            
				if(! $update_stmt -> execute(array($studentid, $MCQIDArr[$i], null))){
					echo "<script language=\"javascript\">  alert(\"Error occurred to submit your answer. Report this bug to reseachers.\"); </script>";
				}                    
			} else{ 
				//SQL UPDATE STATEMENT with Choice (null)
				$update_stmt = "REPLACE INTO MCQ_Question_Record(StudentID, MCQID, Choice)
							 VALUES (?,?,?);";			
				$update_stmt = $conn->prepare($update_stmt);                            
				if(! $update_stmt -> execute(array($studentid, $MCQIDArr[$i], $answerArr[$i]))){
					echo "<script language=\"javascript\">  alert(\"Error occurred to submit your answer. Report this bug to reseachers.\"); </script>";
				}
			}                
		}
		
		$mcqGradeSql = "SELECT CorrectChoice
						FROM   MCQ_Question
						WHERE  MCQID = ?";			
			
		$mcqGradeQuery = $conn->prepare($mcqGradeSql);    
		
		$mcqExplanationSql = "SELECT Explanation, Content
							  FROM   `Option`
							  WHERE   MCQID = ?";	
		
		$mcqExplanationQuery = $conn->prepare($mcqExplanationSql);   
		
		echo "<script> alert(\"Congratulations! You have passed this quiz. The result is: ".$score."/".count($MCQIDArr).".\")  </script>";
		
		echo "<script>";
		
		for($i=0; $i<count($MCQIDArr); $i++){
						
			$mcqGradeQuery->execute(array($MCQIDArr[$i]));	
			$mcqGradeRes = $mcqGradeQuery->fetch(PDO::FETCH_OBJ);
			
			$mcqExplanationQuery->execute(array($MCQIDArr[$i]));	
			$mcqExplanationRes = $mcqExplanationQuery->fetchAll(PDO::FETCH_OBJ);
			
			echo "
				
				$(\"button[name='".$MCQIDArr[$i]."']\").each(function(){ ";
				
				foreach($mcqExplanationRes as $row){
						echo "
							if($(this).val() == \"".$row->Content."\"){
								$(this).append(\"<br>\");
								$(this).append(\"".$row->Explanation."\");
							}
						";
				}
					
				if(!isset($answerArr[$i])){
					echo "
						if($(this).val() == \"".$mcqGradeRes->CorrectChoice."\"){
							$(this).addClass(\"correct\");
						}
						";
						
				} else {							
						if($mcqGradeRes->CorrectChoice == $answerArr[$i]){
							
							echo "
									if($(this).hasClass(\"active\")){
										$(this).addClass(\"correct\");
										$(\"#button\"+".($i+1).").addClass(\"correct\");
									}
								 ";
								 
						} else {
						
							echo "
									if($(this).hasClass(\"active\")){
										$(this).addClass(\"wrong\");
									}
						
									if($(this).val() == \"".$mcqGradeRes->CorrectChoice."\"){
										$(this).addClass(\"correct\");
										$(\"#button\"+".($i+1).").addClass(\"wrong\");
									}
								";
						}	
					}
					
				echo "});";	
		}
		
		echo "</script>";
		
		
	}else{
		//todo: if failed the quiz
		
		echo "<script> 
			if (confirm(\"Sorry! You have failed this quiz. The result is: ".$score."/".count($MCQIDArr).". Do you want to try again?\") == true) {
				document.getElementById(\"hiddenReturnQuiz\").submit();
			} else {
				document.getElementById(\"hiddenReturnTask\").submit();
			}
			</script>";

	} 
	
	db_close($conn);	

?>