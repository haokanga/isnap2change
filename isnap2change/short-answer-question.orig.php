<?php

	session_start();
    require_once("mysql-lib.php");	
    //set userid    
    if(isset($_SESSION['studentID'])){
        $studentID = $_SESSION['studentID'];
        echo "<script language=\"javascript\">  console.log(\"This is DEBUG_MODE with SESSION studentID = ".$studentID.".\"); </script>";
    }else{
        echo "<script language=\"javascript\">  console.log(\"This is DEBUG_MODE with hard-code studentID = 1.\"); </script>";
        $studentID = 1;
    }
    //if submission
    if(isset($_POST['answer']) && isset($_POST['saqid']) && isset($_POST['quizid'])){
        $quizid = $_POST["quizid"];
        $saqid = $_POST["saqid"];    
        $answer = $_POST["answer"];      
        $conn = db_connect();
        $score = 0;
        for($i=0; $i<count($saqid); $i++) {
            $updateSql = "INSERT INTO SAQ_Question_Record(StudentID, SAQID, Answer)
                                     VALUES (?,?,?) ON DUPLICATE KEY UPDATE Answer = ?";			
            $updateSql = $conn->prepare($updateSql);                
            if(! $updateSql -> execute(array($studentID, $saqid[$i], htmlspecialchars($answer[$i]), htmlspecialchars($answer[$i])))){
                echo "<script language=\"javascript\">  alert(\"Error occurred to submit your answer. Report this bug to reseachers.\"); </script>";
            }
            $scoreSql = "SELECT Points FROM SAQ_Question WHERE SAQID = ?";
            $scoreQuery = $conn->prepare($scoreSql);
            $scoreQuery->execute(array($saqid[$i]));
            $scoreResult = $scoreQuery->fetch(PDO::FETCH_OBJ);
            $score += $scoreResult->Points;
        }
        $updateSql = "INSERT INTO Quiz_Record(QuizID, StudentID, `Status`, Score)
                                     VALUES (?,?,?,?) ON DUPLICATE KEY UPDATE Score = ?";			
        $updateSql = $conn->prepare($updateSql);                
        if(! $updateSql -> execute(array($quizid, $studentID, "UNGRADED", $score, $score))){
            echo "<script language=\"javascript\">  alert(\"Error occurred to update your score. Report this bug to reseachers.\"); </script>";
        }
        db_close($conn);
        echo "<script language=\"javascript\">  console.log(\"SUBMISSION.\"); </script>";
        $saqresult = null;
    }
    //if Jump from weekly tasks/learning materials
    else if(!isset($_POST['answer']) && !isset($_POST['saqid']) && isset($_POST["status"])){
        echo "<script language=\"javascript\">  console.log(\"Jump from weekly tasks/learning materials.\"); </script>";
        $saqresult = "";
        $currentsaqid = "";    
        if ($_SERVER["REQUEST_METHOD"] == "POST") {            
            $conn = db_connect();
            $quizid = $_POST["quizid"];                  
            $status = $_POST["status"];
            $saqsql = "SELECT SAQID, Question
                       FROM   SAQ_Section NATURAL JOIN SAQ_Question
                       WHERE  QuizID = ?
                       ORDER BY SAQID";
            $saqquery = $conn->prepare($saqsql);
            $saqquery->execute(array($quizid));
            $saqresult = $saqquery->fetchAll(PDO::FETCH_OBJ);
            
            $lastsaqid = -1;
            if($status == "UNGRADED" || $status == "GRADED"){
                $saq_question_record_sql = "SELECT StudentID, SAQID, Answer, Feedback, Grading
                       FROM   SAQ_Question_Record NATURAL JOIN SAQ_Question
                       WHERE  QuizID = ?
                       ORDER BY SAQID";
                $saq_question_record_query = $conn->prepare($saq_question_record_sql);
                $saq_question_record_query->execute(array($quizid));
                $saq_question_record_result = $saq_question_record_query->fetchAll(PDO::FETCH_OBJ);
            }
            db_close($conn);
        }       
    } else {
        //todo: error handling
    }
?>

<html>
    <head>
        <title>Home</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script type="text/javascript" src="js/jquery-1.12.3.js">
        </script>
        <link href='https://fonts.googleapis.com/css?family=Raleway:400italic|Open+Sans' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link href='https://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css'>
        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
        <link rel="stylesheet" href="css/index.css" type="text/css">



        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
        <script>
            //timer
            $(document).ready(function () {
                var seconds_left = 60;
                var interval = setInterval(function () {
                    document.getElementById('timer_div').innerHTML = --seconds_left;
                    if (seconds_left <= 0)
                    {
                        document.getElementById('timer_div').innerHTML = "Time is up!";
                        clearInterval(interval);
                    }
                }, 1000);

                $("#panel6").hide();
                $("#scrollB").hide();
            });
            function fo()
            {
                document.getElementById("demo").style.color = "red";
            }
            // Nxt 
            function changePanel()
            {
                if (jQuery(".panel").is(':visible'))
                {
                    jQuery('li', this).addClass('dropHover');
                }
                $(".panel").not("#panel2").hide();
                $("#panel2").show();
            }
            // Previous
            function changePanel2()
            {
                $(".panel").not("#panel3").hide();
                $("#scrollB").hide();
                $("#panel3").show();
            }
        </script>
    </head>

    <body>

        <nav class="navbar navbar-default navbar-fixed-top">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <input  type=hidden name="quizid" value=<?php echo $quizid; ?>>
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#"><span class="glyphicon glyphicon-flash"></span>Quiz</a>
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1" style="padding-top:15px;">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="progress">
                                <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
                                    <span class="sr-only">40% Complete (success)</span>
                                    4/10
                                </div>
                            </div>
                        </div>
                        <div class ="col-md-1">
                            <span class="glyphicon glyphicon-time"></span>
                            <div id="timer_div" ></div>
                        </div>
                        <div class="col-md-1">
                            <!--
                            <button type="button" onclick="return changePanel2();" class="btn btn-success">SUBMIT</button>
                            -->
                            <input type="submit" name="submit" value="SUBMIT" id="submit"/>                            
                        </div>
                    </div>
                </div>

            </div>
        </nav>
        <div class="container" style="padding-top:100px;">
            <div class="row">
                <div class="col-md-2" style="padding-top:160px;">
                    <!--  <div class="btn-group-vertical" role="group" aria-label="...">
                          <button class="btn btn-primary" type="button" onclick="return changePanel2();" ><span class="glyphicon glyphicon-check"></span> Track Progress</button>
                      </div> -->
                </div>
				
                <div class="col-md-8" style="opacity:0.9;">
				<?php for($i=0; $i<count($saqresult); $i++) {							
							$currentsaqid = $saqresult[$i] -> SAQID;
							if($currentsaqid != $lastsaqid){ ?>
								<div class="panel panel-default">
									<div class="panel-heading"><b><i><?php echo ($i+1).". ".htmlspecialchars($saqresult[$i] -> Question); ?></i></b></div>
										<div class="panel-body">
						<?php
							} $lastsaqid = $currentsaqid;?>                                
                                <input type="hidden" name="saqid[]" value="<?php echo $currentsaqid ?>"/>
                                <!--Short Answer Question Input TextBox-->
                                <textarea <?php if($status == "UNGRADED" || $status == "GRADED"){echo "disabled";} ?> rows="4" cols="50" name="answer[]" placeholder='Please input your answer here'><?php if($status == "UNGRADED" || $status == "GRADED"){echo $saq_question_record_result[$i]->Answer;} ?></textarea>
                                <!--Feedback if graded-->
                                <?php if($status == "GRADED"){echo "<br><p>".$saq_question_record_result[$i]->Feedback."</p>";
                                echo "<p>Score: ".$saq_question_record_result[$i]->Grading."</p>";} ?>
						<?php
								if(($i+1)==sizeof($saqresult)){ ?>
										</div>	
								</div>
								
						<?php  	} else {
                                    { ?>
                                </div>	
                            </div>
						<?php		}
									
								} 
							
						}?>
                </div>
				
            </div>
        </div>
        </form>
    </body>
</html>