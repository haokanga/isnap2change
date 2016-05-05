<?php	
	session_start();
    require_once("connection.php");	
    //echo "<script language=\"javascript\">  console.log(\"SUBMISSION.\"); </script>";    
    if(isset($_SESSION['userid'])){
        $studentID = $_SESSION['userid'];
        //echo "<script language=\"javascript\">  console.log(\"This is DEBUG_MODE with SESSION studentID = ".$studentID.".\"); </script>";
    }else{
        //echo "<script language=\"javascript\">  console.log(\"This is DEBUG_MODE with hard-code studentID = 1.\"); </script>";
        $studentID = 1;
    }
	
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		
		$conn = db_connect();
		
		if(isset($_POST['MCQIDArr']) && isset($_POST['answerArr'])){
			$MCQIDArr = json_decode($_POST['MCQIDArr']);
			$answerArr = json_decode($_POST['answerArr']);
			
			$feedbackArr = [];
			
			$mcqGradeSql = "SELECT CorrectChoice
							FROM   MCQ_Question
							WHERE  MCQID = ?";
			
			$mcqGradeQuery = $conn->prepare($mcqGradeSql);
			
			for($i=0; $i<count($MCQIDArr); $i++){
							
				$mcqGradeQuery->execute(array($MCQIDArr[$i]));	
				$mcqGradeRes = $mcqGradeQuery->fetch(PDO::FETCH_OBJ);
				
				$MCQContents = 'txt'.$MCQIDArr[$i];
				
				echo "<script> 
					
					var options = document.getElementsByName(\"".$MCQIDArr[$i]."\");
					
					var contents = document.getElementsByName(\"".$MCQContents."\");
					
					for(j = 0; j < options.length; j++){ ";
						
					if(!isset($answerArr[$i])){
                        
                        //SQL UPDATE STATEMENT
						$update_stmt = "REPLACE INTO MCQ_Question_Record(StudentID, MCQID, Choice)
                                     VALUES (?,?,?);";			
                        $update_stmt = $conn->prepare($update_stmt);                            
                        if(! $update_stmt -> execute(array($studentID, $MCQIDArr[$i], $answerArr[$i]))){
                            echo "<script language=\"javascript\">  alert(\"Error occurred to submit your answer. Report this bug to reseachers.\"); </script>";
                        }
						echo "
							if(options[j].value == ".$mcqGradeRes->CorrectChoice."){
								contents[j].style.background=\"#00ff00\";
							}
							";
							
					} else {
							
							if($mcqGradeRes->CorrectChoice == $answerArr[$i]){
							
								echo "
										if(options[j].checked == true){
											contents[j].style.background=\"#00ff00\";
										}
									 ";
									 
							} else {
							
								echo "
										if(options[j].checked == true){
											contents[j].style.background=\"#ff0000\";
										}
							
										if(options[j].value == ".$mcqGradeRes->CorrectChoice."){
											contents[j].style.background=\"#00ff00\";
										}
									";
							}	
						}
						
					echo "} </script>";
						
					
						
			}
					
				
		}
	}

?>