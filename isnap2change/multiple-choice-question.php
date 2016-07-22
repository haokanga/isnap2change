<?php

    //session_start();
	//check login status
/*	require_once('student-validation.php');

	require_once("../mysql-lib.php");
	require_once("../debug.php");
	$pageName = "multiple-choice-question";
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if(isset($_POST["quizID"]) && isset($_POST["week"])){
			$quizID = $_POST["quizID"];
			$week = $_POST["week"];
		} else {
			
		}
	} else {
		
	}
*/
	require_once("../mysql-lib.php");
	$studentID = 1;
	$quizID = 1;

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

		debug_err($e);
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
                    var t_height = ($('.content').outerHeight()-$("#panel"+index).outerHeight())/2;
                    if(t_height >= 0)
                    {
					$("#panel"+index).css ({
                     top: t_height
                    });
				}

					$("#hiddenIndex").val(index);
					$("#button"+index).addClass("highlight");
                });

				$(".last").click(function () {
                    var index = $("#hiddenIndex").val();
					$("#panel"+index).addClass("hidden");
					$("#button"+index).removeClass("highlight");
					index--;
                    $("#panel"+index).removeClass("hidden");
					var t_height = ($('.content').outerHeight()-$("#panel"+index).outerHeight())/2;
                    if(t_height >= 0)
                    {
					$("#panel"+index).css ({
                     top: t_height
                    });
				}
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
					var t_height = ($('.content').outerHeight()-$("#panel"+index).outerHeight())/2;
                    if(t_height >= 0)
                    {
					$("#panel"+index).css ({
                     top: t_height
                    });
				}
					$("#hiddenIndex").val(index);

                });
            });
			
			function parseFeedback(response)
			{
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
		
		<nav class="navbar navbar-inverse navbar-static-top" id="nav">
            <div class="container">
                <!-- .btn-navbar is used as the toggle for collapsed navbar content -->
                <a class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="glyphicon glyphicon-bar"></span>
                    <span class="glyphicon glyphicon-bar"></span>
                    <span class="glyphicon glyphicon-bar"></span>
                </a>
                <div class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li class="active">
                            <a class="navbar-brand" href="#">
                                <img alt="Brand" src="css/image/Snap_Single_Wordform_White.png" style="height: 100%;">
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="#">Snap Facts</a></li>
                        <li><a href="#">Game Home</a></li>
                        <li><a href="#">Resources</a></li>
                    </ul>
                    <ul class="nav pull-right navbar-nav">
                        <li>
                            <a href="#" data-toggle="modal" data-target="#myModal"><i class="glyphicon glyphicon-off"></i> LOGIN</a>
                        </li>
                    </ul>
                </div>		
            </div>
        </nav>
		
		<div class="content"> 
		<div class="col-md-1 sidebar" style="margin-top:8px; margin-bottom:8px;">
                <ul class="list-group lg opt" style="max-height: 89vh; overflow-y: auto;">
                <li class="list-group-item">
                    <button type="button" class="btn btn-default" id="button0" style=" border: none;
                            background: none;
                            cursor: pointer;" value="0">
                        <img src="img/information_icon.png" style="width:48px; height: 48px;">
                    </button>
                </li>
                <li class="list-group-item">
							<button type="button" class="btn btn-default" id="button1" value="1" style=" border: none;
                            background: none;
                            cursor: pointer;">
                             <img src="img/one_icon.png" style="width:48px; height: 48px;">                           	
                            </button>
						</li>

						<li class="list-group-item">
							<button type="button" class="btn btn-default" id="button2" value="2"style=" border: none;
                            background: none;
                            cursor: pointer;">
                             <img src="img/two_icon.png" style="width:48px; height: 48px;">                           	
                            </button>
						</li>

						<li class="list-group-item">
							<button type="button" class="btn btn-default" id="button3" value="3"style=" border: none;
                            background: none;
                            cursor: pointer;">
                             <img src="img/three_icon.png" style="width:48px; height: 48px;">                            	
                            </button>
						</li>

						<li class="list-group-item">
							<button type="button" class="btn btn-default" id="button4" value="4"style=" border: none;
                            background: none;
                            cursor: pointer;">
                             <img src="img/four_icon.png" style="width:48px; height: 48px;">
                            </button>
						</li>

						<li class="list-group-item">
							<button type="button" class="btn btn-default" id="button5" value="5"style=" border: none;
                            background: none;
                            cursor: pointer;">
                             <img src="img/five_icon.png" style="width:48px; height: 48px;">                           	
                            </button>
						</li>
                    
				<!-- please add the for-loop logic here based on images -->
			<?php 
					for($i=0; $i<$quesNum; $i++) { ?>
						
						
			<?php   } ?>
					
               


                </ul>
            </div>

		<div class="info hidden" style="padding-top:10px; padding-bottom:10px;" id="panel0">
		<div style="text-align: center; color: white;">
		<h4>Read the following information about nutrition then answer the questions provided.</h4>
		</div>
                <div class="panel panel-default">               
                    <div class="panel-body" style="overflow-y: scroll; border-radius: 20px;">                                                  
                            <div class="para" style="padding-left:55px; padding-right:55px;">
                                <div style="color:black; text-align:left;">
                                     <?php echo $materialRes->Content; ?>
                                </div> 
                            </div>
                        </div>
                    </div>
                    <div class="footer" style="display:flex;justify-content:center;align-items:center;width:100%;height:20%; margin-top: 4%;">              
                        <img src="img/start_icon.png" style="height: 36px; width: 36px;">               
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
                    <div class="panel-heading" style="font-size: x-large; font-weight: 600; height:25%; margin-top:7%; text-align: left; vertical-align: center;">
                        <div class="ques" >
                            Q<?php echo $questionIndex.". ".$mcqRes[$i]->Question; $questionIndex++; $MCQIDArray = $MCQIDArray.($mcqRes[$i]->MCQID).',';?>
                        </div> 
                    </div>
                    <div class="panel-body">
							<div class="options" style="color:black; width: 85%; margin-left:7.5%;">
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
										<?php echo $mcqRes[$i]->Content;?></button>
						<?php	} ?>
								
			
		<?php
			  if(($i+1)==sizeof($mcqRes)){ ?>
							</div>
							<br>
							<div class="nav-options" style="text-align: center;">
								<a class="btn btn-default last" role="button" style="padding-top:8px; padding-bottom: 10px;border: none;
                            background: none; cursor: pointer;""><img src="img/direction_icon.png" style="width:32px; height: 32px;"></a>
                            <br>
                            <?php
						if($status == "GRADED"){ ?>
						<form id="goBack" method=post action=weekly-task.php>
							 <button type="button" style="border-radius: 0px !important; color: white !important; border-color: white; background-color: rgb(0,0,0) !important;" onclick="return goBack()" class="btn btn-default btn-lg">GO BACK</button>
						</form>
				<?php	}
					
						if($status == "UNANSWERED"){ ?>
						<form id="goBack" method=post action=weekly-task.php>
							<button id="back-btn" type="button" style="border-radius: 0px !important; color: white !important; border-color: white; background-color: rgb(0,0,0) !important;" onclick="return submitQuiz(<?php echo $quizID; ?>, <?php echo $studentID; ?>);" class="btn btn-default btn-lg">SUBMIT</button>

						</form>
				<?php	} ?>
                           
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
					   
							
								<a class="btn btn-default last"  role="button" style="padding-top:8px; padding-bottom: 10px; border: none;
                            background: none; cursor: pointer;""><img src="img/direction_icon.png" style="width:32px; height: 32px;"></a>
							
		<?php		} ?>
							
								<a class="btn btn-default next"  role="button" style="padding-top:8px; padding-bottom: 10px; border: none;
                            background: none; cursor: pointer;"><img src="img/direction_icon.png" style="width:32px; height: 32px;"></a>
							
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
			</form>
			
			<form id="hiddenReturnTask" action="weekly-task.php" method=post>

			</form>
		</div>
		
        </div>

    </body>
</html>

