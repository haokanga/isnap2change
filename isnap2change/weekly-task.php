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

		//get due time for this week
		$dueTime = getStuWeekRecord($conn, $studentID, $week);

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
	<script src="js/jquery-1.12.3.js"></script>
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
	<?php
		 if($dueTime != null) { ?>

			if((Date.parse(new Date()) - Date.parse(new Date("<?php echo $dueTime?>"))) <= 0) {
				initializeClock(new Date("<?php echo $dueTime?>"), true);
			} else {

			}
	<?php } else { ?>
		  	newDue = new Date(Date.parse(new Date()) +  60 * 1000);
	      	initializeClock(newDue, true);
	
			var dd = newDue.getDate();
			var mm = newDue.getMonth() + 1;
			var yyyy = newDue.getFullYear();

			if(dd<10) {
				dd="0"+dd;
			}

			if(mm<10) {
				mm="0"+mm;
			}

			newDue = yyyy+"-"+mm+"-"+dd+ " " +newDue.getHours() + ":" + newDue.getMinutes()+":" + newDue.getSeconds();

			saveDueTime(<?php echo $studentID ?>, <?php echo $week ?>, newDue);
    <?php	} ?>

	function startQuiz(quizid) {
		document.getElementById("quiz"+quizid).submit();
	}

	function saveDueTime(studentID, week, dueTime) {
		var xmlhttp = new XMLHttpRequest();

		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				parseFeedback(xmlhttp.responseText);
			}
		};

		xmlhttp.open("POST", "save-due-time.php", true);
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlhttp.send("studentID="+studentID +"&week="+week+"&dueTime="+ dueTime);
	}

	function parseFeedback(response){
		var feedback = JSON.parse(response);

		if(feedback.message != "success"){
			alert(feedback.message + ". Please try again!");
			//jump to error page
		}
	}


</script>
</html>
