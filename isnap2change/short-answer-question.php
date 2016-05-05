<?php

	session_start();
    require_once("connection.php");
	
	$rows = "";
	$currentsaqid = "";
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		
		$conn = db_connect();
	    $quizid = $_POST["quizid"];
		$saqsql = "SELECT SAQID, Question
				   FROM   SAQ_Section NATURAL JOIN SAQ_Question
				   WHERE  QuizID = ?
				   ORDER BY SAQID";
		$saqquery = $conn->prepare($saqsql);
		$saqquery->execute(array($quizid));
		$rows = $saqquery->fetchAll(PDO::FETCH_OBJ);
	}

	$lastsaqid = -1;
    
    if(isset($_SESSION['userid'])){
		$studentID = $_SESSION['userid'];
	}else{
        echo "This is DEBUG_MODE with hard-code studentID = 1";
        $studentID = 1;
    }

    $saqid = $_POST["saqid"];    
    $answer = $_POST["answer"];
    
    $conn = db_connect();   

    for($i=0; $i<count($saqid); $i++) {
        //echo $saqid[$i]."<br>";   
        //echo $answer[$i]."<br>";
        $insertStudentSql = "REPLACE INTO SAQ_Question_Record(StudentID, SAQID, Answer)
							     VALUES (?,?,?);";			
		$insertStudentSql = $conn->prepare($insertStudentSql);
			
		if(! $insertStudentSql -> execute(array($studentID, $saqid[$i], htmlspecialchars($answer[$i])))){
			echo "<script language=\"javascript\">  alert(\"Error occurred to submit your answer. Report this bug to reseachers.\"); </script>";
		}
    }    
    db_close($conn);
?>

<html>
    <head>
        <title>Home</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script type="text/javascript" src="js/jquery-1.12.3.js">
        </script>
        <link href='https://fonts.googleapis.com/css?family=Raleway:400italic|Open+Sans' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
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
				<?php for($i=0; $i<count($rows); $i++) {							
							$currentsaqid = $rows[$i] -> SAQID;
							if($currentsaqid != $lastsaqid){ ?>
								<div class="panel panel-default">
									<div class="panel-heading"><b><i><?php echo ($i+1).". ".security_check_text($rows[$i] -> Question); ?></i></b></div>
										<div class="panel-body">
						<?php
							} $lastsaqid = $currentsaqid;?>
                                <!--Short Answer Question Input TextBox-->
                                <input type="hidden" name="saqid[]" value="<?php echo $currentsaqid ?>"/>
                                <textarea rows="4" cols="50" name="answer[]">Please input your answer here</textarea>
						<?php
								if(($i+1)==sizeof($rows)){ ?>
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