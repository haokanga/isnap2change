<?php
	
	require_once('connection.php');
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		
			$conn = db_connect();
			
			$quizid = $_POST["quizid"];
			$quiztype = $_POST["quiztype"];
			
			$materialPreSql = "SELECT COUNT(*) 
							   FROM   Learning_Material
							   WHERE  QuizID = ?";
							
			$materialPreQuery = $conn->prepare($materialPreSql);
			$materialPreQuery->execute(array($quizid));
			
			if($materialPreQuery->fetchColumn() != 1){
				
			}
			
			$materialSql = "SELECT Content, TopicName 
							FROM   Learning_Material NATURAL JOIN Quiz
									  NATURAL JOIN Topic
							WHERE  QuizID = ?";
							
			$materialQuery = $conn->prepare($materialSql);
			$materialQuery->execute(array($quizid));
			$materialRes = $materialQuery->fetch(PDO::FETCH_OBJ);
			
			db_close($conn);	
			
			
			
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
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

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
			
			function beginQuiz()
			{
				<?php if($quiztype == "MCQ"){ ?>
							document.getElementById("formQuizBegin").setAttribute("action", "multiple-choice-question.php");
				<?php	  }
					  
					  if($quiztype == "SAQ"){ ?>
						  document.getElementById("formQuizBegin").setAttribute("action", "short-answer-question.php");
				<?php	 } ?>
					
				document.getElementById("formQuizBegin").submit();
				
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
                    <a class="navbar-brand" href="#"><span class="glyphicon glyphicon-flash"></span> Quiz</a>
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1" style="padding-top:10px;">
                    <div class="row">
                        <!--          <div class="col-md-9">
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
                                </div> -->
                        <div class="col-md-offset-11">
						<form id="formQuizBegin" method=post>
                            <button type="button" onclick="beginQuiz()" class="btn btn-success">BEGIN</button>   
							<input  type=hidden name="quizid" value=<?php echo $quizid; ?>></input>
                        </form>
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
                <div class="col-md-8">
                    <div class="panel panel-default" id="panel1" style="opacity:0.8;color:firebrick;">
                         <div class="panel-body">
                            
                             <blockquote class="blockquote-reverse" style="border-right: 5px solid #58a6da;">
                             <h2><i><?php echo htmlspecialchars($materialRes->TopicName) ?></i></h2>
                             
                            <i><?php echo htmlspecialchars($materialRes->Content) ?></i>
                             
                             </blockquote>
                            
                        </div>
                    </div>                     
                </div>
            </div>
        </div>
    </body>
</html>
