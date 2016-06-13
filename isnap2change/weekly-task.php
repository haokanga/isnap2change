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
		$quizzesStatusRes = getQuizzesStatusByWeek($conn, $studentID, $week);


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

	for($i=0; $i<count($quizzesStatusRes); $i++){
		if(isset($quizzesStatusRes["Status"])){
			//if UNSUBMITTED/UNGRADED/GRADED
			$status = $quizzesStatusRes[$i]->Status;

			//list of question type
			switch($quizzesStatusRes[$i]->QuizType){
				case "MCQ":
					echo '<form id="quiz'.$quizzesStatusRes[$i]->QuizID.'" action=multiple-choice-question.php method=post>';
					break;
				case "SAQ":
					echo '<form id="quiz'.$quizzesStatusRes[$i]->QuizID.'" action=short-answer-question.php method=post>';
					break;
				case "Matching":
					echo '<form id="quiz'.$quizzesStatusRes[$i]->QuizID.'" action=matching-question.php method=post>';
					break;
				case "Poster":
					echo '<form id="quiz'.$quizzesStatusRes[$i]->QuizID.'" action=poster-editor.php method=post>';
					break;
				case "Calculator":
					echo '<form id="quiz'.$quizzesStatusRes[$i]->QuizID.'" action=cost-calculator.php method=post>';
					break;
				default:
					break;
			}

		} else {
			//if UNANSWERED
			$status = "UNANSWERED";

			echo '<form id="quiz'.$quizzesStatusRes[$i]->QuizID.'" action=learning-material.php method=post>';
			echo '<input type=hidden name="status" value='.$status.'>';
		}

		echo '<button type=button onclick="startQuiz('.$quizzesStatusRes[$i]->QuizID.')"> Quiz '.($i+1).'</button>
			  <input  type=hidden name="quizID" value='.$quizzesStatusRes[$i]->QuizID.'>
			  <input  type=hidden name="quizType" value='.$quizzesStatusRes[$i]->QuizType.'>
			  <input  type=hidden name="week" value='.$quizzesStatusRes[$i]->Week.'>
			  </form>';
	}


    /**  game query move somewhere else
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
	*/
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
