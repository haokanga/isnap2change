 mbn<?php
//	session_start();
	require_once('connection.php');
	
//	if(!isset($_SESSION["studentid"])){
		
//	}
		
//	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		
//		if(isset($_POST["quizid"]) && isset($_POST["quiztype"]) && isset($_POST["week"])){
			
			$quizid = $_GET["quizid"];
			$quiztype = "MCQ";
//			$quiztype = $_POST["quiztype"];
//			$week = $_POST["week"];
				
//		} else {
			
//		}
	
//	} else {
		
//	}
	
	$conn = db_connect();
	
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

?>

<html>
    <head>
        <title>Quiz</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="css/info.css" />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
        <link href='https://fonts.googleapis.com/css?family=Raleway:400|Open+Sans' rel='stylesheet' type='text/css'>
        <link href='https://maxcdn.bootstrapcdn.com/font-awesome/4.6.2/css/font-awesome.min.css' rel='stylesheet' type='text/css'>
        <script type="text/javascript" src="js/jquery-1.12.3.js"></script>
    </head>
    <body>
         <script>
            function beginQuiz()
			{
				<?php if($quiztype == "MCQ"){ ?>
							document.getElementById("formQuizBegin").setAttribute("action", "multiple-choice-question.php");
				<?php }
					  
					  if($quiztype == "SAQ"){ ?>
						  document.getElementById("formQuizBegin").setAttribute("action", "short-answer-question.php");
				<?php } ?>
					
				document.getElementById("formQuizBegin").submit();
			}
        </script>
        
        <div class="content"> 
		<div class="contentHeader">
                <img src="css/n1.jpg" alt="logo" style='width:100%; height:70vh;'/>
				
				</div>
           <div class="info" style="top:58vh; padding-bottom:10px;">
            <div class="panel panel-default">
                    <div class="panel-body">

					 <div class="heading" style="color: black; max-height:10vh; text-align:center; border-bottom: 1px solid #eee;">
                                <h1 style='padding: 0px;'> 
								<i>
                                   <?php echo $materialRes->TopicName; ?>   </i>                          
                                </h1> 
                            </div>

            <div class="para" style="padding-left:15px; padding-right:15px; padding-top:8px; text-align:center;">
				<div style="color:black; display:flex; justify-content:center; align-items:center;">
                  <i><?php echo $materialRes->Content; ?></i>
				</div>
            </div>
                        <br>
                        <br>
						<form id="formQuizBegin" method=post>
                            <button type="button" onclick="beginQuiz()" class="btn btn-default btn-lg btn-block" style="background-color:darkseagreen;">BEGIN QUIZ </button>
							<input  type=hidden name="quizid" value=<?php echo $quizid; ?>></input>
                           <!--
						   implement logic 
						   -->
                        </form>
					</div>
                </div>
            </div>
        </div>
        <script>
           
        </script>
    </body>
</html>
