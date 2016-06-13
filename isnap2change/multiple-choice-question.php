<?php

    session_start();
	//check login status
	require_once('student-validation.php');

	require_once('mysql-lib.php');
	require_once('debug.php');
	$pageName = "multiple-choice-question";
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if(isset($_POST["quizID"]) && isset($_POST["week"])){
			$quizID = $_POST["quizID"];
			$week = $_POST["week"];
		} else {
			
		}
	} else {
		
	}

	$conn = null;

	try{
		$conn = db_connect();

		//get learning material
		$materialRes = getLearningMaterial($conn, $quizID);

		//get mcq question number
		$quesNum = getMCQQuestionNum($conn, $quizID);

		//check quiz status
		$status = getQuizStatus($conn, $quizID, $studentID);

		//if graded
		if($status == "GRADED"){
			$mcqRes = getMCQSubmission($conn, $quizID, $studentID);
		}

		//if unanswered
		if($status == "UNANSWERED"){
			$mcqRes = getMCQQuestions($conn, $quizID);
		}
	} catch(Exception $e){
		if($conn != null){
			db_close($conn);
		}

		debug_err($pageName, $e);
		//to do: handle sql error
		//...
		exit;
	}

	db_close($conn);

/*
	$quesNumSql = "SELECT Count(*)
				   FROM   MCQ_Question
				   WHERE  QuizID = ?";
	
	$quesNumQuery = $conn->prepare($quesNumSql);
	$quesNumQuery->execute(array($quizID));
	
	$quesNum = $quesNumQuery->fetchColumn();
*/

			
	$lastMCQID = -1;
	$questionIndex = 1;
	$MCQIDArray = "";
?>

<html>
    <head>
        <title>Quiz</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="css/quiz.css" />
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link href='https://fonts.googleapis.com/css?family=Raleway:400|Open+Sans' rel='stylesheet' type='text/css'>
        <link href='https://maxcdn.bootstrapcdn.com/font-awesome/4.6.2/css/font-awesome.min.css' rel='stylesheet' type='text/css'>
        <script type="text/javascript" src="js/jquery-1.12.3.js"></script>
    </head>
    <body>
        <script>
		
			var timeinterval;
		
			function getTimeRemaining(endtime){
				var t = Date.parse(endtime) - Date.parse(new Date());
				var seconds = Math.floor( (t/1000) % 60 );
				var minutes = Math.floor( (t/1000/60) % 60 );
				
				return {
					'total': t,
					'minutes': minutes,
					'seconds': seconds
				 };
			}
			
			function initializeClock(endtime){
				var clock = document.getElementById("clock");
				
				var timerSpan = clock.querySelector('.timer');
				
				function updateClock() {
					var t = getTimeRemaining(endtime);

					timerSpan.innerHTML = ('0' + t.minutes).slice(-2) + ":" + ('0' + t.seconds).slice(-2);
				
					if (t.total <= 0) {
						alert("Time is up!");
						submitQuiz(<?php echo $quizID; ?>, <?php echo $studentID; ?>);
					}
				}
				
				updateClock();
				timeinterval = setInterval(updateClock, 1000);
				
			}
			
			<?php if($status == "UNANSWERED"){ ?>
						window.onload = function () {
						var deadline = new Date(Date.parse(new Date()) + 90 * 1000);
						initializeClock(deadline);
					};
			<?php } ?>

            $(document).ready(function ()
            {
				$("#button1").addClass("highlight");

				$('#panel1').css ({
                   top: ($('.content').outerHeight()-$('#panel1').outerHeight())/2  
                });

                $(".options").find(".btn").click(function () {
					var index = $("#hiddenIndex").val();
					var num = $(this).attr('id');
					$("#radio_"+num).prop("checked", true);
					$("#panel"+index).find(".btn").removeClass("active");
                    $(this).addClass("active");
					$("#button"+index).addClass("completed");
                });
                
                 $(".next").click(function () {
                    var index = $("#hiddenIndex").val();
					$("#panel"+index).addClass("hidden");
					$("#button"+index).removeClass("highlight");
					index++;
                    $("#panel"+index).removeClass("hidden");
					$("#panel"+index).css ({
                     top: ($('.content').outerHeight()-$("#panel"+index).outerHeight())/2  
                    });
					$("#hiddenIndex").val(index);
					$("#button"+index).addClass("highlight");
                });

				$(".last").click(function () {
                    var index = $("#hiddenIndex").val();
					$("#panel"+index).addClass("hidden");
					$("#button"+index).removeClass("highlight");
					index--;
                    $("#panel"+index).removeClass("hidden");
					$("#panel"+index).css ({
                     top: ($('.content').outerHeight()-$("#panel"+index).outerHeight())/2  
                    });
					$("#hiddenIndex").val(index);					
					$("#button"+index).addClass("highlight");
                });
				
				$(".opt").find(".btn").click(function () {						
					var index = $(this).val();
					$(this).addClass("highlight");
					var currentIndex = $("#hiddenIndex").val();
					$("#button"+currentIndex).removeClass("highlight");
					$("#panel"+currentIndex).addClass("hidden");
                    $("#panel"+index).removeClass("hidden");
					$("#panel"+index).css ({
                     top: ($('.content').outerHeight()-$("#panel"+index).outerHeight())/2  
                    });
					$("#hiddenIndex").val(index);

                });

            });
			
			function parseFeedback(response) {
				var feedback = JSON.parse(response);

				if(feedback.message != "success"){
					alert(feedback.message + ". Please try again!");
					return;
				}
				
				var result = feedback.result;
				var score = feedback.score;
				var quesNumber = feedback.quesNum;
				
				if(result == "pass") {
					alert("Congratulations! You have passed this quiz. The result is: " + score + "/" + quesNumber +".");
					$("#back-btn").text('GO BACK');
					$("#back-btn").attr("onclick", "goBack()");
					
					var index = $("#hiddenIndex").val();
					$("#panel"+index).addClass("hidden");
					$("#button"+index).removeClass("highlight");
					$("#panel1").removeClass("hidden");
					$("#button1").addClass("highlight");
					$("#hiddenIndex").val(1);
					
					var detail = feedback.detail;
						
					for(i = 0; i < quesNumber; i++){
						
						var correctAns = detail[i].correctAns;
						var studentAns = detail[i].studentAns;
						var option = detail[i].option;
						var explanation = detail[i].explanation;
						
						$("button[name='" + detail[i].MCQID + "']").each(function(){
							
							for(j = 0; j < option.length; j++){
								if($(this).val() == option[j]){
									$(this).append("<br>");
									$(this).append(explanation[j]);
								}
							}
							
							if(studentAns == null){
								if($(this).val() == correctAns){
									$(this).addClass("correct");
									$(this).find(".glyphicon-ok").removeClass("hidden");
								} else {
									$(this).find(".glyphicon-remove").removeClass("hidden");
								}
							} else {
								if(correctAns == studentAns){
									if($(this).hasClass("active")){
										$(this).addClass("correct");
										$("#button"+(i+1)).addClass("correct");
										$(this).find(".glyphicon-ok").removeClass("hidden");
									} else {
										$(this).find(".glyphicon-remove").removeClass("hidden");
									}
								} else {
									if($(this).hasClass("active")){
										$(this).addClass("wrong");
										$(this).find(".glyphicon-remove").removeClass("hidden");
									} else if($(this).val() == correctAns){
												$(this).addClass("correct");
												$("#button"+(i+1)).addClass("wrong");
												$(this).find(".glyphicon-ok").removeClass("hidden");
										   } else {
												$(this).find(".glyphicon-remove").removeClass("hidden");
										   }
								}	
							}
						});
						
					}
					
				} else if(result == "fail") {
					if (confirm("Sorry! You have failed this quiz. The result is: " + score + "/" + quesNumber +". Do you want to try again?") == true) {
						document.getElementById("hiddenReturnQuiz").submit();
					} else {
						document.getElementById("hiddenReturnTask").submit();
					}
				}
				
				
			}
			
			function submitQuiz(quizID, studentID)
			{
				clearInterval(timeinterval);
			//	$(".btn-block").attr("disabled","disabled");
				$("input[type='radio']").remove();
				
				var MCQIDArr = document.getElementById("hiddenMCQIDArray").value.split(',');
				var answerArr = new Array(MCQIDArr.length);	
					
				$(".options").each(function(i) {
					$(this).find(".btn").each(function() {
						if($(this).hasClass("active")){
							answerArr[i] = $(this).val();
						}
					});
				});
				
				var xmlhttp = new XMLHttpRequest();
				
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						parseFeedback(xmlhttp.responseText);
					} 
				};
				
				xmlhttp.open("POST", "multiple-choice-question-feedback.php", true);
				xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xmlhttp.send("MCQIDArr="+JSON.stringify(MCQIDArr)+"&answerArr="+JSON.stringify(answerArr)+"&quizID="+quizID+"&studentID="+studentID);
			}
			
			function goBack()
			{
				document.getElementById("goBack").submit();
			}
			
        </script>
		
		<header class="navbar navbar-static-top bs-docs-nav">

            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                </button>
                <a class="navbar-brand" href="#">QUIZ</a>
			</div>
			<div class="nav navbar-nav navbar-btn navbar-right" style="margin-right:22px;">
			
				<?php
						if($status == "GRADED"){ ?>
						<form id="goBack" method=post action=weekly-task.php>
							 <button type="button" onclick="return goBack()" class="btn btn-success">GO BACK</button> 
							 <input type=hidden name="week" value=<?php echo $week; ?>>
						</form>
				<?php	}
					
						if($status == "UNANSWERED"){ ?>
						<form id="goBack" method=post action=weekly-task.php>
							<button id="back-btn" type="button" onclick="return submitQuiz(<?php echo $quizID; ?>, <?php echo $studentID; ?>);" class="btn btn-success">SUBMIT</button>
							<input type=hidden name="week" value=<?php echo $week; ?>>
						</form>
				<?php	} ?>
					
			</div>
			<div class="nav navbar-nav navbar-btn navbar-right" style="margin-right: 15px; font-size: x-large;">
				<div id="clock">
						<span class="timer"></span>
				</div>
			</div>
        </header>
		
		<div class="content"> 
		<div class="col-md-1 sidebar" style="margin-top:8px; margin-bottom:8px;">

                <ul class="list-group lg opt" style="max-height: 89vh; overflow-y: auto;">
                    <li class="list-group-item" style="color:turquoise;">
                        <button type="button" class="btn btn-default" id="button0" style="color:turquoise;font-weight: bold;" value="0">i</button>
                    </li>
					
			<?php 
					for($i=0; $i<$quesNum; $i++) { ?>
						<li class="list-group-item">
							<button type="button" class="btn btn-default" id="button<?php echo $i+1;?>" value="<?php echo $i+1;?>"><?php echo $i+1;?></button>
						</li>
						
			<?php   } ?>
					
               


                </ul>
            </div>

		<div class="info hidden" style="padding-top:10px; padding-bottom:10px;" id="panel0">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="myHeader" style="text-align:center;">
                            <div class="page-header" style="color: black;">
                                <h1> 
                                    <i><?php echo $materialRes->TopicName; ?></i>
                                </h1> 
                            </div>
                            <div class="para" style="padding-left:15px; padding-right:15px;">
                                <div style="color:black; text-align:center;">
                                     <?php echo $materialRes->Content; ?>
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
        </div>

		<?php for($i=0; $i<count($mcqRes); $i++) {
			
			$currentMCQID = $mcqRes[$i]->MCQID;
							
			if($currentMCQID != $lastMCQID){ 
				if($questionIndex == 1){ ?>
					<div class="myques" id="panel1">
	  <?php		} else { ?>
					<div class="myques hidden" id="<?php echo "panel".$questionIndex;?>">
	  <?php		} ?>
					<div class="panel panel-default">
                    <div class="panel-heading" style="font-size: xx-large; font-weight: 600; height:35%; text-align: center; justify-content:center; display:flex; align-items:center;">
                        <div class="ques" >
                            <?php echo $questionIndex.". ".$mcqRes[$i]->Question; $questionIndex++; $MCQIDArray = $MCQIDArray.($mcqRes[$i]->MCQID).',';?>
                        </div> 
                    </div>
                    <div class="panel-body">
							<div class="options" style="color:white; width: 85%; margin-left:7.5%;">
		<?php
			} $lastMCQID = $currentMCQID;?>
								
						<?php
								if($status == "GRADED"){
									if(!isset($mcqRes[$i]->Choice)){ 
										if($mcqRes[$i]->Content == $mcqRes[$i]->CorrectChoice){
											echo "<button type=\"button\" class=\"btn btn-default btn-lg btn-block correct\" name=\"".$mcqRes[$i]->MCQID."\" id=\"".$i."\" disabled> 
												  <span class=\"glyphicon glyphicon-ok\"></span>";
										} else { 
											echo "<button type=\"button\" class=\"btn btn-default btn-lg btn-block\" name=\"".$mcqRes[$i]->MCQID."\" id=\"".$i."\" disabled> 
												  <span class=\"glyphicon glyphicon-remove\"></span>";
										} 
									} else { 
										if($mcqRes[$i]->CorrectChoice == $mcqRes[$i]->Choice){
											if($mcqRes[$i]->Content == $mcqRes[$i]->CorrectChoice){
												echo "<script>$(\"#button\"+".($questionIndex-1).").addClass(\"correct\");</script>";
												echo "<button type=\"button\" class=\"btn btn-default btn-lg btn-block correct\" name=\"".$mcqRes[$i]->MCQID."\" id=\"".$i."\" disabled> 
													  <span class=\"glyphicon glyphicon-ok\"></span>";
											} else {
												echo "<button type=\"button\" class=\"btn btn-default btn-lg btn-block\" name=\"".$mcqRes[$i]->MCQID."\" id=\"".$i."\" disabled> 
												      <span class=\"glyphicon glyphicon-remove\"></span>";
											}
										} else {
											if($mcqRes[$i]->Content == $mcqRes[$i]->CorrectChoice){ 
												echo "<button type=\"button\" class=\"btn btn-default btn-lg btn-block correct\" name=\"".$mcqRes[$i]->MCQID."\" id=\"".$i."\" disabled> 
													  <span class=\"glyphicon glyphicon-ok\"></span>";
											} else if($mcqRes[$i]->Content == $mcqRes[$i]->Choice){
												echo "<script>$(\"#button\"+".($questionIndex-1).").addClass(\"wrong\");</script>";
												echo "<button type=\"button\" class=\"btn btn-default btn-lg btn-block wrong\" name=\"".$mcqRes[$i]->MCQID."\" id=\"".$i."\" disabled> 
												      <span class=\"glyphicon glyphicon-remove\"></span>";
											} else {
												echo "<button type=\"button\" class=\"btn btn-default btn-lg btn-block\" name=\"".$mcqRes[$i]->MCQID."\" id=\"".$i."\" disabled> 
												      <span class=\"glyphicon glyphicon-remove\"></span>";
											}
										}
									}  ?>
									
									<label><?php echo $mcqRes[$i]->Content;?></label><br><?php echo $mcqRes[$i]->Explanation; ?></button>
									
						<?php	}
						
								if($status == "UNANSWERED"){  ?>
										<button type="button" id="<?php echo $i?>" class="btn btn-default btn-lg btn-block" name="<?php echo $mcqRes[$i]->MCQID;?>" value="<?php echo $mcqRes[$i]->Content;?>">
										<span class="glyphicon glyphicon-remove hidden"></span> 
										<span class="glyphicon glyphicon-ok hidden"></span> 
										<input type="radio" name="R_<?php echo $mcqRes[$i]->MCQID;?>" class="" id="radio_<?php echo $i?>"/><label><?php echo $mcqRes[$i]->Content;?></label></button>
						<?php	} ?>
								
			
		<?php
			  if(($i+1)==sizeof($mcqRes)){ ?>
							</div>
							<br>
							<div class="nav-options" style="text-align: center;">
								<a class="btn btn-default last" role="button" style="padding-top:8px; padding-bottom: 10px;"><span class="glyphicon glyphicon-chevron-left"></span></a>
							</div>
						</div>
					</div>
				</div>
		<?php } else {
				$nextMCQID = $mcqRes[$i+1]->MCQID;
				
				if($nextMCQID != $currentMCQID){ ?>
							</div>
							<br>
							<div class="nav-options" style="text-align:center;">
		<?php					
					if($questionIndex!=2){ ?>
					   
							
								<a class="btn btn-default last"  role="button" style="padding-top:8px; padding-bottom: 10px;"><span class="glyphicon glyphicon-chevron-left"></span></a>
							
		<?php		} ?>
							
								<a class="btn btn-default next"  role="button" style="padding-top:8px; padding-bottom: 10px;"><span class="glyphicon glyphicon-chevron-right"></span></a>
							
							</div>
						</div>
					</div>
				</div>
		<?php	}
			  }
		} ?>
            
			  
            <input type="hidden" id="hiddenIndex" value="1">
			<input type=hidden id="hiddenMCQIDArray" value="<?php echo substr($MCQIDArray, 0, strlen($MCQIDArray)-1); ?>">
			
			<form id="hiddenReturnQuiz" action="learning-material.php" method=post>
					<input  type=hidden name="quizID" value=<?php echo $quizID; ?>>
					<input  type=hidden name="week" value=<?php echo $week; ?>>
			</form>
			
			<form id="hiddenReturnTask" action="weekly-task.php" method=post>
					<input  type=hidden name="week" value=<?php echo $week; ?>>
			</form>
		</div>
        </div>
    </body>
</html>

