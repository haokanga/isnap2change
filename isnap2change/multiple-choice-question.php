<?php

    session_start();
	require_once('connection.php');
	
	if(!isset($_SESSION["studentid"])){
		
	}
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		
		if(isset($_POST["quizid"]) && isset($_POST["quiztype"]) && isset($_POST["week"])){
			
			$quizid = $_POST["quizid"];
			$quiztype = $_POST["quiztype"];
			$week = $_POST["week"];
          
		} else {
			
		}
		
	} else {
		
	}
	
	$conn = db_connect();
	
	$mcqSql = "SELECT MCQID, Question, Content 
			   FROM   MCQ_Section NATURAL JOIN MCQ_Question
								  NATURAL JOIN `Option`
			   WHERE  QuizID = ?
			   ORDER BY MCQID, Content";
								
	$mcqQuery = $conn->prepare($mcqSql);
	$mcqQuery->execute(array($quizid));
			
	$rows = $mcqQuery->fetchAll(PDO::FETCH_OBJ);
			
	$lastMCQID = -1;
	$questionIndex = 1;
	$MCQIDArray = "";
	
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
                var seconds_left = 20;
                var interval = setInterval(function () {
                    document.getElementById('timer_div').innerHTML = --seconds_left;
                    if (seconds_left <= 0)
                    {
						alert("Time is up!");
						submitQuiz();
                       // document.getElementById('timer_div').innerHTML = "Time is up!";
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
			
			function parseScript(strcode) {
				var scripts = new Array();         // Array which will store the script's code
  
				// Strip out tags
				while(strcode.indexOf("<script") > -1 || strcode.indexOf("</script") > -1) {
					var s = strcode.indexOf("<script");
					var s_e = strcode.indexOf(">", s);
					var e = strcode.indexOf("</script", s);
					var e_e = strcode.indexOf(">", e);
    
					// Add to scripts array
					scripts.push(strcode.substring(s_e+1, e));
					// Strip from strcode
					strcode = strcode.substring(0, s) + strcode.substring(e_e+1);
			  }
			  
			  // Loop through every script collected and eval it
			  for(var i=0; i<scripts.length; i++) {
				try {
				  eval(scripts[i]);
				}
				catch(ex) {
				  // do what you want here when a script fails
				}
			  }
			}
			
			function submitQuiz()
			{
				var answerArr = [];
				var MCQIDArr = document.getElementById("hiddenMCQIDArray").value.split(',');
				
				for(i = 0; i < MCQIDArr.length; i++){
					
					var options = document.getElementsByName(MCQIDArr[i]);
					
					for(j = 0; j < options.length; j++){
						if(options[j].checked == true){
							answerArr[i] = options[j].value;
						}
					}

				}
				
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						parseScript(xmlhttp.responseText);
					} else {
						
					}
				};
				
				xmlhttp.open("POST", "multiple-choice-question-feedback.php", true);
				xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xmlhttp.send("MCQIDArr="+JSON.stringify(MCQIDArr)+"&answerArr="+JSON.stringify(answerArr)+"&quizid="+<?php echo $quizid;?>);
		
			}
			
        </script>
    </head>

    <body>

        <nav class="navbar navbar-default navbar-fixed-top">
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

                            <button type="button" onclick="return submitQuiz();" class="btn btn-success">SUBMIT</button>                      
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
					
							$currentMCQID = $rows[$i] -> MCQID;
							
							if($currentMCQID != $lastMCQID){ ?>
								<div class="panel panel-default">
									<div class="panel-heading"><b><i><?php echo $questionIndex.". ".$rows[$i]->Question; $questionIndex++; $MCQIDArray = $MCQIDArray.($rows[$i]->MCQID).',';?></i></b></div>
										<div class="panel-body">
						<?php
							} $lastMCQID = $currentMCQID;?>
						
											<div class="radio">
											<label>
												<input type="radio" name="<?php echo $rows[$i] -> MCQID;?>" value="<?php echo $rows[$i]->Content;?>">
												<p name="<?php echo 'txt'.$rows[$i] -> MCQID;?>"><b><i><?php echo $rows[$i] -> Content;?></i></b></p>
											</label>
											</div>
							
						<?php
								if(($i+1)==sizeof($rows)){ ?>
										</div>	
								</div>
								
						<?php  	} else {
									$nextMCQID = $rows[$i+1] -> MCQID;
									
									if($nextMCQID != $currentMCQID){ ?>
												</div>	
										</div>
						<?php		}
									
								} 
							
						}?>
                </div>
				<input type=hidden id="hiddenMCQIDArray" value="<?php echo substr($MCQIDArray, 0, strlen($MCQIDArray)-1); ?>">
				<form id="hiddenReturnQuiz" action="learning-material.php" method=post>
					<input  type=hidden name="quizid" value=<?php echo $quizid; ?>></input>
					<input  type=hidden name="quiztype" value=<?php echo $quiztype; ?>></input>
					<input  type=hidden name="week" value=<?php echo $week; ?>></input>
				</form>
				<form id="hiddenReturnTask" action="weekly-task.php" method=post>
					<input  type=hidden name="week" value=<?php echo $week; ?>></input>
				</form>
            </div>
        </div>
    </body>
</html>