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
		.countdown-container {
			position: relative;
			top: 50%;
			-webkit-transform: translateY(-50%);
			-moz-transform: translateY(-50%);
			transform: translateY(-50%);
		}
		.clock-item .inner {
			height: 0px;
			padding-bottom: 100%;
			position: relative;
			width: 100%;
		}
		.clock-canvas {
			background-color: rgba(255, 255, 255, .1);
			border-radius: 50%;
			height: 0px;
			padding-bottom: 100%;
		}
		.text {
			color: #fff;
			font-size: 30px;
			font-weight: bold;
			margin-top: -50px;
			position: absolute;
			top: 50%;
			text-align: center;
			text-shadow: 1px 1px 1px rgba(0, 0, 0, 1);
			width: 100%;
		}
		.text .val {
			font-size: 50px;
		}
		.text .type-time {
			font-size: 20px;
		}

		@media (min-width: 768px) and (max-width: 991px) {
			.clock-item {
				margin-bottom: 30px;
			}
		}
		@media (max-width: 767px) {
			.clock-item {
				margin: 0px 30px 30px 30px;
			}
		}
	</style>
	<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
	<link href="http://fonts.googleapis.com/css?family=Raleway:400,700" rel="stylesheet" type="text/css">
</head>
<body>

<div class="countdown countdown-container container">
<div class="clock row">
	<div class="clock-item clock-days countdown-time-value col-sm-6 col-md-3">
		<div class="wrap">
			<div class="inner">
				<div id="canvas-days" class="clock-canvas"></div>

				<div class="text">
					<p class="val">0</p>
					<p class="type-days type-time">DAYS</p>
				</div><!-- /.text -->
			</div><!-- /.inner -->
		</div><!-- /.wrap -->
	</div><!-- /.clock-item -->

	<div class="clock-item clock-hours countdown-time-value col-sm-6 col-md-3">
		<div class="wrap">
			<div class="inner">
				<div id="canvas-hours" class="clock-canvas"></div>

				<div class="text">
					<p class="val">0</p>
					<p class="type-hours type-time">HOURS</p>
				</div><!-- /.text -->
			</div><!-- /.inner -->
		</div><!-- /.wrap -->
	</div><!-- /.clock-item -->

	<div class="clock-item clock-minutes countdown-time-value col-sm-6 col-md-3">
		<div class="wrap">
			<div class="inner">
				<div id="canvas-minutes" class="clock-canvas"></div>

				<div class="text">
					<p class="val">0</p>
					<p class="type-minutes type-time">MINUTES</p>
				</div><!-- /.text -->
			</div><!-- /.inner -->
		</div><!-- /.wrap -->
	</div><!-- /.clock-item -->

	<div class="clock-item clock-seconds countdown-time-value col-sm-6 col-md-3">
		<div class="wrap">
			<div class="inner">
				<div id="canvas-seconds" class="clock-canvas"></div>

				<div class="text">
					<p class="val">0</p>
					<p class="type-seconds type-time">SECONDS</p>
				</div><!-- /.text -->
			</div><!-- /.inner -->
		</div><!-- /.wrap -->
	</div><!-- /.clock-item -->
</div><!-- /.clock -->
</div><!-- /.countdown-wrapper -->

<script src="js/timer.js"></script>
<script src="js/jquery-1.12.3.js"></script>
<script type="text/javascript" src="js/kinetic.js"></script>
<script type="text/javascript" src="js/jquery.final-countdown.js"></script>
<script>
	<?php
		 if($dueTime != null) { ?>

			if((Date.parse(new Date()) - Date.parse(new Date("<?php echo $dueTime?>"))) <= 0) {
				$('.countdown').final_countdown({
					start: new Date().getTime() / 1000,
					end: Date.parse(new Date("<?php echo $dueTime?>"))/1000,
					now: new Date().getTime() / 1000
				});
			} else {

			}
	<?php } else { ?>
		  	newDue = new Date(Date.parse(new Date()) +  60 * 1000);

			$('.countdown').final_countdown({
				start: new Date().getTime() / 1000,
				end: Date.parse(newDue) / 1000,
				now: new Date().getTime() / 1000
			}, function() {
					alert("Time is up!");
			});

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
</body>
</html>
