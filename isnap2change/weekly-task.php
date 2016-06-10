<?php
    //if true, echo debug output in dev mode, else production mode
	$DEBUG_MODE = true;
	session_start();
	require_once('mysql-lib.php');	
	if(isset($_SESSION["studentid"])){
		$studentid = $_SESSION["studentid"];
	} else {
		
	}
    	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {		
		if(isset($_POST["week"])){
			$week = $_POST["week"];
		} else {
			
		}		
	} else {
		
	}
	
	$conn = db_connect();
    /**
    $quizIDSql = "SELECT QuizID, QuizType
                  FROM   Quiz 
                  WHERE  Week = ?
                  AND QuizID NOT IN
                 (SELECT QuizID
                  FROM   Quiz_Record NATURAL JOIN Quiz
                  WHERE  StudentID = ? AND	Week = ?)
                  ORDER BY QuizID
                  LIMIT 1";
    */
    
    //quiz
    $quizSql = "SELECT Quiz.QuizID, QuizType, `Status` FROM Quiz LEFT JOIN (SELECT * FROM Quiz_Record WHERE StudentID = ?) Student_Quiz_Record ON Quiz.QuizID = Student_Quiz_Record.QuizID WHERE Week = ? ORDER BY Quiz.QuizID";    
    $quizQuery = $conn->prepare($quizSql);
    $quizQuery->execute(array($studentid, $week)); 
    $count = 0;
	
    while($quizResult = $quizQuery->fetch(PDO::FETCH_ASSOC)){
        $count++;
        if($DEBUG_MODE){
            echo "<script language=\"javascript\">  console.log(\"[SUCCESS] studentid: $studentid week:$week QuizID:".$quizResult["QuizID"]." QuizType:".$quizResult["QuizType"]."\"); </script>";
        }
		
		if(isset($quizResult["Status"])){
			//if UNGRADED/GRADED
			$status = $quizResult["Status"];
			
			//list of question type
			switch($quizResult["QuizType"]) {
				case "MCQ":
					echo '<form id="quiz'.$quizResult["QuizID"].'" action=multiple-choice-question.php method=post>';
					break;
				case "SAQ":
					echo '<form id="quiz'.$quizResult["QuizID"].'" action=short-answer-question.php method=post>';
					break;
				case "Matching":
					echo '<form id="quiz'.$quizResult["QuizID"].'" action=matching-question.php method=post>';
					break;
				case "Poster":
					echo '<form id="quiz'.$quizResult["QuizID"].'" action=poster-editor.php method=post>';
					break;
				default:
					break;
			}
			
		} else {
			//if UNANSWERED
			$status = "UNANSWERED";
				
			echo '<form id="quiz'.$quizResult["QuizID"].'" action=learning-material.php method=post>';
		}
		
        echo '<button type=button onclick="startQuiz('.$quizResult["QuizID"].')"> Quiz '.$count.'</button>
			  <input  type=hidden name="quizid" value='.$quizResult["QuizID"].'></input>
			  <input  type=hidden name="quiztype" value='.$quizResult["QuizType"].'></input>
			  <input  type=hidden name="week" value='.$week.'></input>
			  <input type=hidden name="status" value='.$status.'></input>
			  </form>';
    }
    //game
    $gameSql = "SELECT * FROM Game";    
    $gameQuery = $conn->prepare($gameSql);
    $gameQuery->execute(); 
    while($gameResult = $gameQuery->fetch(PDO::FETCH_ASSOC)){
        echo '<form id="game" method=post>
        <button type=button onclick=""> '.$gameResult["Description"].'</button>
        <input  type=hidden name="gameid" value='.$gameResult["GameID"].'></input>
        <input  type=hidden name="week" value='.$week.'></input>
        </form>';
    }
	db_close($conn);	
	
?>

<html>
<head>
<script>
function startQuiz(quizid){	
	document.getElementById("quiz"+quizid).submit();	
}
</script>
</head>
<body>
<!--
<div id="a" align="center">
<form id="quiz" action=learning-material.php method=post>
<button type=button onclick="startQuiz()"> Quiz </button>
<input  type=hidden name="quizid" value=<?php echo $quizIDRes->QuizID; ?>></input>
<input  type=hidden name="quiztype" value=<?php echo $quizIDRes->QuizType; ?>></input>
<input  type=hidden name="week" value=<?php echo $week; ?>></input>
</form>
</div>
-->
</body>
</html>
