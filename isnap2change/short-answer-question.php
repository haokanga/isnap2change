<?php
    session_start();
    require_once("student-validation.php");

    require_once("mysql-lib.php");
    require_once("debug.php");
    $pageName = "short-answer-question";

    //POST parameters
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
        //check quiz status
        $status = getQuizStatus($conn, $quizID, $studentID);

        //get learning-material
        $materialRes = getLearningMaterial($conn, $quizID);    
        
        // get saq questions;
        $saqResult = getSAQQuestions($conn, $quizID);
        
        //if answered
        if($status != "UNANSWERED"){
            $saqQuesRecordResult = getSAQRecords($conn, $quizID, $studentID);

            if($status == "GRADED"){
                // get score for each question
                for($i=0; $i<count($saqResult); $i++) {
                    $saqID = $saqResult[$i] -> SAQID;
                    $scoreResult = getSAQQuestion($conn, $saqID);
                    $scoreArray[] = $scoreResult->Points;
                }
            }
        }

        db_close($conn);       

    } catch(Exception $e) {
        debug_err($pageName, $e);
    }

    db_close($conn);
    $lastsaqID = -1;
    $questionIndex = 1;
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
            $(document).ready(function (){
                $("#button0").addClass("highlight");
                $('#panel0').css({
                    top: ($('.content').outerHeight() - $('#panel0').outerHeight()) / 2
                });

                $(".next").click(function () {
                    var index = $("#hiddenIndex").val();
                    $("#panel" + index).addClass("hidden");
                    $("#button" + index).removeClass("highlight");
                    index++;
                    $("#panel" + index).removeClass("hidden");
                    $("#panel" + index).css({
                        top: ($('.content').outerHeight() - $("#panel" + index).outerHeight()) / 2
                    });
                    $("#hiddenIndex").val(index);
                    $("#button" + index).addClass("highlight");
                });

                $(".last").click(function () {
                    var index = $("#hiddenIndex").val();
                    $("#panel" + index).addClass("hidden");
                    $("#button" + index).removeClass("highlight");
                    index--;
                    $("#panel" + index).removeClass("hidden");
                    $("#panel" + index).css({
                        top: ($('.content').outerHeight() - $("#panel" + index).outerHeight()) / 2
                    });
                    $("#hiddenIndex").val(index);
                    $("#button" + index).addClass("highlight");
                });

                $(".opt").find(".btn").click(function () {
                    var index = $(this).val();
                    $(this).addClass("highlight");
                    var currentIndex = $("#hiddenIndex").val();
                    $("#button" + currentIndex).removeClass("highlight");
                    $("#panel" + currentIndex).addClass("hidden");
                    $("#panel" + index).removeClass("hidden");
                    $("#panel" + index).css({
                        top: ($('.content').outerHeight() - $("#panel" + index).outerHeight()) / 2
                    });
                    $("#hiddenIndex").val(index);

                });
            });

            function parseFeedback(response){
                var feedback = JSON.parse(response);

                if(feedback.message != "success"){
                    alert(feedback.message + ". Please try again!");
                    return;
                }

                if(feedback.action == "SAVE"){
                    alert("Saved Successfully!");
                }

                if(feedback.action == "SUBMIT"){
                    alert("Submitted Successfully!");
                    $("#saveBtn").attr("disabled","disabled");
                    $("#submitBtn").attr("disabled","disabled");
                }
            }

            function submitQuiz(quizID, studentID, action){
                //collect SAQID
                var SAQIDArr = [];

                $("input:hidden[name='SAQIDArr']").each(function() {
                    SAQIDArr.push($(this).val());
                });

                //collect student answer
                var answerArr = [];

                $("textarea[name='answerArr']").each(function() {
                    answerArr.push($(this).val());
                });

                //send request
                var xmlhttp = new XMLHttpRequest();

                xmlhttp.onreadystatechange = function() {
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                        parseFeedback(xmlhttp.responseText);
                    }
                };

                xmlhttp.open("POST", "short-answer-question-feedback.php", true);
                xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xmlhttp.send("SAQIDArr="+JSON.stringify(SAQIDArr)+"&answerArr="+JSON.stringify(answerArr)+"&quizID="+quizID+"&studentID="+studentID+"&action="+action);
			}

            function goBack(){
                document.getElementById("goBack").submit();
            }

        </script>
        <header class="navbar navbar-static-top bs-docs-nav">

            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                </button>
                <a class="navbar-brand" href="#">QUIZ</a>
            </div>
            <!--Sumbit/Go Back Button-->
            <div class="nav navbar-nav navbar-btn navbar-right" style="margin-right:22px;">
                    <button id="saveBtn" type="button" <?php if($status == "GRADED" || $status == "UNGRADED") echo 'disabled="disabled"';?> onclick="submitQuiz(<?php echo $quizID; ?>, <?php echo $studentID; ?>, 'SAVE');" class="btn btn-success">SAVE</button>
                    <button id="submitBtn" type="button" <?php if($status == "GRADED" || $status == "UNGRADED") echo 'disabled="disabled"';?> onclick="submitQuiz(<?php echo $quizID; ?>, <?php echo $studentID; ?>, 'SUBMIT');" class="btn btn-success">SUBMIT</button>
                    <button id="back-btn" type="button" onclick="goBack()" class="btn btn-success">GO BACK</button>
                <form id="goBack" method=post action=weekly-task.php>
                    <input type=hidden name="week" value=<?php echo $week; ?>>
                </form>
            </div>
            <div class="nav navbar-nav navbar-btn navbar-right" style="margin-right: 15px; font-size: x-large;">
                <div id="clock">
						<span class="timer"></span>
				</div>
            </div>
        </header>
        <!--Sidebar-->
        <div class="content"> 
            <div class="col-md-1 sidebar" style="margin-top:8px; margin-bottom:8px;">

                <ul class="list-group lg opt" style="max-height: 89vh; overflow-y: auto;">
                    <li class="list-group-item" style="color:turquoise;">
                        <button type="button" class="btn btn-default" id="button0" style="color:turquoise;font-weight: bold;" value="0">i</button>
                    </li>
                    <?php for($i=0; $i<count($saqResult); $i++) {?>
						<li class="list-group-item">
							<button type="button" class="btn btn-default" id="button<?php echo $i+1;?>" value="<?php echo $i+1;?>"><?php echo $i+1;?></button>
						</li>						
                    <?php   } ?>
                </ul>
            </div>

            <!--Learning_Material-->
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
        <!--form submission-->    
        <form id="submission" method="post">
            <input type=hidden name="goback" value=0 > 
            <input type=hidden name="week" value=<?php echo $week; ?> >        
            <input type=hidden name="quizID" value=<?php echo $quizID; ?> >
            <input type=hidden name="status" value="UNGRADED" >
            <!--start of saq for-loop-->
            <?php for($i=0; $i<count($saqResult); $i++) {
                $currentsaqID = $saqResult[$i] -> SAQID;
                if($currentsaqID != $lastsaqID){?>
                <div class="myques <?php if($questionIndex != 1){echo "hidden";} ?> " id="panel<?php echo $questionIndex;?>">
                    <!--heading-->
                    <div class="panel-heading" style="font-size: xx-large; font-weight: 600; color:black; height:35%; min-height: 35%; max-height: 35%; text-align:center;">
                        <div class="ques" >                        
                            <?php $questionIndex++; echo ($i+1).". ".$saqResult[$i] -> Question; ?>
                        </div> 
                    </div>
                    <!--body-->
                    <div class="panel-body" style="width: 85%; margin-left:7.5%;">
                    <?php  $lastsaqID = $currentsaqID;?>
                        <!--if GRADED-->
                        <?php if($status == "GRADED"){ ?>                        
                            <div class="well-large">
                                <ul class="nav nav-tabs nav-justified">
                                    <li role="presentation" class="active"><a data-toggle="tab" href="#feedback<?php echo $i+1;?>">FEEDBACK</a></li>
                                    <li role="presentation"><a data-toggle="tab" href="#myanswer<?php echo $i+1;?>">MY ANSWER</a></li>
                                </ul>
                            </div>
                            <div class="tab-content">
                                <div id="feedback<?php echo $i+1;?>" class="tab-pane fade in active">
                                    <div class="alert alert-success" role="alert"> 
                                        <strong> Score : <?php echo $saqQuesRecordResult[$i]->Grading ?> / <?php echo $scoreArray[$i] ?> </strong>
                                        <br>
                                        <br>
                                        <strong>Comments :</strong>
                                        <br>
                                        <?php echo $saqQuesRecordResult[$i]->Feedback ?>
                                    </div>
                                </div>
                                
                                <div class="tab-pane fade" id="myanswer<?php echo $i+1;?>">
                                    <div class="alert alert-warning" role="alert">
                                        <?php echo $saqQuesRecordResult[$i]->Answer; ?>
                                    </div>
                                </div>
                            </div>
                        <!--if UNANSWERED/UNGRADED-->    
                        <?php } else { ?>
                        <input type="hidden" name="SAQIDArr" value="<?php echo $currentsaqID ?>"/>
                        <textarea class="form-control" rows="10" name="answerArr" placeholder='Please input your answer here'><?php if($status == "UNGRADED" || $status == "UNSUBMITTED"){echo $saqQuesRecordResult[$i]->Answer;} ?></textarea>
                        <?php } ?>
                        <br>
                        <br>
                        <!--Navigation Button-->
                        <div class="back2"  style="text-align: center;">                            
                            <a class="btn btn-default last <?php if($i<=0){echo "disabled";} ?>"  role="button" style="padding-top:8px; padding-bottom: 10px;"><span class="glyphicon glyphicon-chevron-left"></span></a>                           
                            <a class="btn btn-default next <?php if($i>=count($saqResult)-1){echo "disabled";} ?>"  role="button" style="padding-top:8px; padding-bottom: 10px;"><span class="glyphicon glyphicon-chevron-right"></span></a>
                        </div>
                    </div>
                </div>                
            <?php }
            //end of saq for-loop
            } ?> 
            
			<input type="hidden" id="hiddenIndex" value="1">  
            </div>
        </div>
        </form>
    <!--notification of submission-->
    <?php if(isset($_POST["goback"])) echo "<script> alert(\"Congratulations! You have finished this quiz. \")  </script>"; ?>   
    </body>
</html>

