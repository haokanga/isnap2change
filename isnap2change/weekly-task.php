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
		if(isset($_POST["week"])) {
			$week = $_POST["week"];
		} else {
			
		}		
	} else {
		
	}

	$conn = null;

	try{
		$conn = db_connect();

		//get all quizzes and status by studentID and week
		$quizzesStatusRes = getQuizzesStatusByWeek($conn, $studentID, $week);


	} catch(Exception $e) {
		if($conn != null) {
			db_close($conn);
		}

		debug_err($pageName, $e);
		//to do: handle sql error
		//...
		exit;
	}

	for($i=0; $i<count($quizzesStatusRes); $i++){
		if(isset($quizzesStatusRes[$i]->Status)){
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
				case "Misc":
					try {
						$miscQuizType = getMiscQuizType($conn, $quizzesStatusRes[$i]->QuizID);
					} catch (Exception $e){
						if($conn != null) {
							db_close($conn);
						}

						debug_err($pageName, $e);
						//to do: handle sql error
						//...
						exit;
					}

					switch($miscQuizType){
						case "Calculator":
							echo '<form id="quiz'.$quizzesStatusRes[$i]->QuizID.'" action=cost-calculator.php method=post>';
							break;
						default:
							break;
					}
					break;
				default:
					break;
			}

		} else {
			//if UNANSWERED
			$status = "UNANSWERED";

			echo '<form id="quiz'.$quizzesStatusRes[$i]->QuizID.'" action=learning-material.php method=post>';
		}

		echo '<button type=button onclick="startQuiz('.$quizzesStatusRes[$i]->QuizID.')"> Quiz '.($i+1).'</button>
			  <input  type=hidden name="quizID" value='.$quizzesStatusRes[$i]->QuizID.'>
			  <input  type=hidden name="week" value='.$week.'>
			  </form>';
	}

	db_close($conn);
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
	<style>
		#clockdiv{
			font-family: sans-serif;
			color: #fff;
			display: inline-block;
			font-weight: 100;
			text-align: center;
			font-size: 30px;
		}

		#clockdiv > div{
			padding: 10px;
			border-radius: 3px;
			background: #00BF96;
			display: inline-block;
		}

		#clockdiv div > span{
			padding: 15px;
			border-radius: 3px;
			background: #00816A;
			display: inline-block;
		}

		.smalltext{
			padding-top: 5px;
			font-size: 16px;
		}
	</style>
	<script src="js/timer.js"></script>
</head>
<body>
<div id="clockdiv">
	<div>
		<span class="hours"></span>
		<div class="smalltext">Hours</div>
	</div>
	<div>
		<span class="minutes"></span>
		<div class="smalltext">Minutes</div>
	</div>
	<div>
		<span class="seconds"></span>
		<div class="smalltext">Seconds</div>
	</div>
</div>
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
<script>
	//get deadline if exists, otherwise creats a new deadline
	getDeadline(<?php echo $week; ?>);



	function startQuiz(quizid) {
		document.getElementById("quiz"+quizid).submit();
	}
</script>
</html>
