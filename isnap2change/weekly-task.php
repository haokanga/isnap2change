<?php
    //if true, echo debug output in dev mode, else production mode
	$DEBUG_MODE = true;
	session_start();

	//check login status
	require_once('student-validation.php');

	require_once('mysql-lib.php');
	require_once('debug.php');
	$pageName = "weekly-task";

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if(isset($_POST["week"])){
			$week = $_POST["week"];
		} else{
			
		}		
	} else {
		
	}

	$conn = null;

	try{
		$conn = db_connect();

		//get all quizzes and status by studentID and week
		getQuizzesStatusByWeek($conn, $studentID, $week);


	} catch(Exception $e){
		if($conn != null) {
			db_close($conn);
		}

		debug_err($pageName, $e);
		//to do: handle sql error
		//...
		exit;
	}

	db_close($conn);

    $count = 0;
	
    while($quizResult = $quizQuery->fetch(PDO::FETCH_ASSOC)){
        $count++;
		
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
			  <input  type=hidden name="quizID" value='.$quizResult["QuizID"].'>
			  <input  type=hidden name="quizType" value='.$quizResult["QuizType"].'>
			  <input  type=hidden name="week" value='.$week.'>
			  <input type=hidden name="status" value='.$status.'>
			  </form>';
    }
    //game
    $gameSql = "SELECT * FROM Game";    
    $gameQuery = $conn->prepare($gameSql);
    $gameQuery->execute(); 
    while($gameResult = $gameQuery->fetch(PDO::FETCH_ASSOC)){
        echo '<form id="game" method=post>
        <button type=button onclick=""> '.$gameResult["Description"].'</button>
        <input  type=hidden name="gameid" value='.$gameResult["GameID"].'>
        <input  type=hidden name="week" value='.$week.'>
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
<input  type=hidden name="quizid" value=<?php echo $quizIDRes->QuizID; ?>>
<input  type=hidden name="quiztype" value=<?php echo $quizIDRes->QuizType; ?>>
<input  type=hidden name="week" value=<?php echo $week; ?>>
</form>
</div>
-->
</body>
</html>
