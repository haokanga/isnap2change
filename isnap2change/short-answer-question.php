<?php
    //if true, echo debug output in dev mode, else production mode
	$DEBUG_MODE = true;
    
	session_start();
    require_once("mysql-lib.php");	
           
    $conn = db_connect();
    
    //set userid    
    if(isset($_SESSION['studentID'])){
        $studentID = $_SESSION['studentID'];
        echo "<script language=\"javascript\">  console.log(\"This is DEBUG_MODE with SESSION studentID = ".$studentID.".\"); </script>";
    }else{
        if(DEBUG_MODE){
            echo "<script language=\"javascript\">  console.log(\"This is DEBUG_MODE with hard-code studentID = 1.\"); </script>";
            $studentID = 1;
        }
    }
    //POST parameters
    if ($_SERVER["REQUEST_METHOD"] == "POST") {		
		if(isset($_POST["quizid"]) && isset($_POST["week"]) && isset($_POST["status"])){
			$quizid = $_POST["quizid"];
			$week = $_POST["week"];
			$status = $_POST["status"];
		} else {
			
		}		
	} else {		
	}
    
    //get learning-material
    $materialPreSql = "SELECT COUNT(*) 
					   FROM   Learning_Material
					   WHERE  QuizID = ?";							
	$materialPreQuery = $conn->prepare($materialPreSql);
	$materialPreQuery->execute(array($quizid));			
	if($materialPreQuery->fetchColumn() == 1){
		$materialSql = "SELECT Content, TopicName 
                        FROM   Learning_Material NATURAL JOIN Quiz
                                                 NATURAL JOIN Topic
                        WHERE  QuizID = ?";							
        $materialQuery = $conn->prepare($materialSql);
        $materialQuery->execute(array($quizid));
        $materialRes = $materialQuery->fetch(PDO::FETCH_OBJ);		
	} else {       
    }    
    
    // get $saqresult[$i] -> SAQID;
    $saqsql = "SELECT SAQID, Question
               FROM   SAQ_Section NATURAL JOIN SAQ_Question
               WHERE  QuizID = ?
               ORDER BY SAQID";
    $saqquery = $conn->prepare($saqsql);
    $saqquery->execute(array($quizid));
    $saqresult = $saqquery->fetchAll(PDO::FETCH_OBJ);
    
    //if submitted
    if($status == "UNGRADED" || $status == "GRADED"){
        $saq_question_record_sql = "SELECT StudentID, SAQID, Answer, Feedback, Grading
               FROM   SAQ_Question_Record NATURAL JOIN SAQ_Question
               WHERE  QuizID = ?
               ORDER BY SAQID";
        $saq_question_record_query = $conn->prepare($saq_question_record_sql);
        $saq_question_record_query->execute(array($quizid));
        $saq_question_record_result = $saq_question_record_query->fetchAll(PDO::FETCH_OBJ);
    }
    
    // get score for each question    
    $score = 0;
    for($i=0; $i<count($saqresult); $i++) {
        $scoreSql = "SELECT Points FROM SAQ_Question WHERE SAQID = ?";
        $scoreQuery = $conn->prepare($scoreSql);
        $scoreQuery->execute(array($saqresult[$i] -> SAQID));
        $scoreResult = $scoreQuery->fetch(PDO::FETCH_OBJ);
        $score += $scoreResult->Points;
        $scoreArray[] = $scoreResult->Points;
        $score += $scoreResult->Points;
    }
    
    //if submission
    if(isset($_POST['answer']) && isset($_POST['saqid']) && isset($_POST['quizid'])){
        $quizid = $_POST["quizid"];
        $saqid = $_POST["saqid"];    
        $answer = $_POST["answer"];
        for($i=0; $i<count($saqid); $i++) {
            $updateSql = "INSERT INTO SAQ_Question_Record(StudentID, SAQID, Answer)
                                     VALUES (?,?,?) ON DUPLICATE KEY UPDATE Answer = ?";			
            $updateSql = $conn->prepare($updateSql);                
            if(! $updateSql -> execute(array($studentID, $saqid[$i], htmlspecialchars($answer[$i]), htmlspecialchars($answer[$i])))){
                echo "<script language=\"javascript\">  alert(\"Error occurred to submit your answer. Report this bug to reseachers.\"); </script>";
            }
        }
        $updateSql = "INSERT INTO Quiz_Record(QuizID, StudentID, `Status`, Score)
                                     VALUES (?,?,?,?) ON DUPLICATE KEY UPDATE Score = ?";			
        $updateSql = $conn->prepare($updateSql);                
        if(! $updateSql -> execute(array($quizid, $studentID, "UNGRADED", $score, $score))){
            echo "<script language=\"javascript\">  alert(\"Error occurred to update your score. Report this bug to reseachers.\"); </script>";
        }        
        $saqresult = null;
    }
    //if Jump from weekly tasks/learning materials
    else if(!isset($_POST['answer']) && !isset($_POST['saqid']) && isset($_POST["status"])){
        echo "<script language=\"javascript\">  console.log(\"Jump from weekly tasks/learning materials.\"); </script>";
    } else {
        //todo: error handling
    }
    db_close($conn);       
    $lastsaqid = -1;
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
            <!--Timer-->
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
						submitQuiz();
					}
				}				
				updateClock();
				timeinterval = setInterval(updateClock, 1000);				
			}
            
            <?php if(($status == "UNANSWERED" || $status == "UNGRADED") && !isset($_POST["goback"])){ ?>
						window.onload = function () {
						var deadline = new Date(Date.parse(new Date()) + 90 * 1000);
						initializeClock(deadline);
					};
			<?php } ?>
            
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
            
            function goBack()
			{
				document.getElementById("goBack").submit();
			}
            
            function submitQuiz()
			{                
                document.getElementById("submission").submit();
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
                <form id="goBack" method=post action=weekly-task.php>
                    <?php if($status == "GRADED" || isset($_POST["goback"])){ ?>
                    <button id="back-btn" type="button" onclick="goBack()" class="btn btn-success">GO BACK</button>
                    <?php } else if($status == "UNANSWERED" || $status == "UNGRADED"){ ?>
                    <button id="back-btn" type="button" onclick="return submitQuiz();" class="btn btn-success">SUBMIT</button>
                    <?php } ?>                                        
                    <input type=hidden name="week" value=<?php echo $week; ?>></input>
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
                    <?php for($i=0; $i<count($saqresult); $i++) {?>
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
        <form id="submission" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type=hidden name="goback" value=0 ></input> 
            <input type=hidden name="week" value=<?php echo $week; ?> ></input>        
            <input type=hidden name="quizid" value=<?php echo $quizid; ?> ></input>
            <input type=hidden name="status" value="UNGRADED" ></input>
            <!--start of saq for-loop-->
            <?php for($i=0; $i<count($saqresult); $i++) {
                $currentsaqid = $saqresult[$i] -> SAQID;
                if($currentsaqid != $lastsaqid){?>
                <div class="myques <?php if($questionIndex != 1){echo "hidden";} ?> " id="panel<?php echo $questionIndex;?>">
                    <!--heading-->
                    <div class="panel-heading" style="font-size: xx-large; font-weight: 600; color:black; height:35%; min-height: 35%; max-height: 35%; text-align:center;">
                        <div class="ques" >                        
                            <?php $questionIndex++; echo ($i+1).". ".htmlspecialchars($saqresult[$i] -> Question); ?>
                        </div> 
                    </div>
                    <!--body-->
                    <div class="panel-body" style="width: 85%; margin-left:7.5%;">
                    <?php  $lastsaqid = $currentsaqid;?>
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
                                        <strong> Score : <?php echo $saq_question_record_result[$i]->Grading ?> / <?php echo $scoreArray[$i] ?> </strong>
                                        <br>
                                        <br>
                                        <strong>Comments :</strong>
                                        <br>
                                        <?php echo $saq_question_record_result[$i]->Feedback ?>
                                    </div>
                                </div>
                                
                                <div class="tab-pane fade" id="myanswer<?php echo $i+1;?>">
                                    <div class="alert alert-warning" role="alert">
                                        <?php if($status == "UNGRADED" || $status == "GRADED"){echo $saq_question_record_result[$i]->Answer;} ?>
                                    </div>
                                </div>
                            </div>
                        <!--if UNANSWERED/UNGRADED-->    
                        <?php } else { ?>
                        <input type="hidden" name="saqid[]" value="<?php echo $currentsaqid ?>"/>
                        <textarea class="form-control" rows="10" name="answer[]" placeholder='Please input your answer here'><?php if($status == "UNGRADED"){echo $saq_question_record_result[$i]->Answer;} ?></textarea>                        
                        <?php } ?>
                        <br>
                        <br>
                        <!--Navigation Button-->
                        <div class="back2"  style="text-align: center;">                            
                            <a class="btn btn-default last <?php if($i<=0){echo "disabled";} ?>"  role="button" style="padding-top:8px; padding-bottom: 10px;"><span class="glyphicon glyphicon-chevron-left"></span></a>                           
                            <a class="btn btn-default next <?php if($i>=count($saqresult)-1){echo "disabled";} ?>"  role="button" style="padding-top:8px; padding-bottom: 10px;"><span class="glyphicon glyphicon-chevron-right"></span></a>
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

