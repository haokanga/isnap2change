<?php

	require_once("connection.php");
	
	$quizid = 1;
	$studentid = 1;
	
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
	
	$quesNumSql = "SELECT Count(*)
				   FROM   MCQ_Question
				   WHERE  QuizID = ?";
	
	$quesNumQuery = $conn->prepare($quesNumSql);
	$quesNumQuery->execute(array($quizid));
	$quesNum = $quesNumQuery->fetchColumn();
	
	$mcqSql = "SELECT MCQID, Question, Content, CorrectChoice, Choice, Explanation
			   FROM   MCQ_Section NATURAL JOIN MCQ_Question
				                  NATURAL JOIN `Option`
                                  NATURAL JOIN MCQ_Question_Record
               WHERE StudentID = ? AND QuizID = ?
               ORDER BY MCQID";
								
	$mcqQuery = $conn->prepare($mcqSql);
	$mcqQuery->execute(array($studentid, $quizid));
			
	$rows = $mcqQuery->fetchAll(PDO::FETCH_OBJ);
			
	$lastMCQID = -1;
	$questionIndex = 1;
	$MCQIDArray = "";
	
	db_close($conn);

?>

<html>
    <head>
        <title>Quiz</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="css/quiz.css" />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
        <link href='https://fonts.googleapis.com/css?family=Raleway:400|Open+Sans' rel='stylesheet' type='text/css'>
        <link href='https://maxcdn.bootstrapcdn.com/font-awesome/4.6.2/css/font-awesome.min.css' rel='stylesheet' type='text/css'>
        <script type="text/javascript" src="js/jquery-1.12.3.js"></script>
    </head>
    <body>
        <script>
            $(document).ready(function ()
            {
				$("#button1").addClass("highlight");

                $(".options").find(".btn").click(function () {
					var index = $("#hiddenIndex").val();
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
					$("#hiddenIndex").val(index);
					$("#button"+index).addClass("highlight");
                });

				$(".last").click(function () {
                    var index = $("#hiddenIndex").val();
					$("#panel"+index).addClass("hidden");
					$("#button"+index).removeClass("highlight");
					index--;
                    $("#panel"+index).removeClass("hidden");
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
					$("#hiddenIndex").val(index);

                });

            });
			
			
			
        </script>
		
		<header class="navbar navbar-static-top bs-docs-nav">

            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                </button>
                <a class="navbar-brand" href="#">QUIZ</a>
			</div>
			<div class="nav navbar-nav navbar-btn navbar-right" style="margin-right:22px;">				
				<button type="button" onclick="return submitQuiz();" class="btn btn-success">SUBMIT</button> 
			</div>
        </header>
		
		<div class="content"> 
		<div class="col-md-1 sidebar" style="margin-top:8px; margin-bottom:8px;">

                <ul class="list-group lg opt" style="max-height: 89vh; overflow-y: auto;">
                    <li class="list-group-item" style="color:turquoise;">
                        <button type="button" class="btn btn-default" style="color:turquoise;font-weight: bold;" value="0">i</button>
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

		<?php for($i=0; $i<count($rows); $i++) {
			
			$currentMCQID = $rows[$i] -> MCQID;
							
			if($currentMCQID != $lastMCQID){ 
				if($questionIndex == 1){ ?>
					<div class="myques" id="panel1">
	  <?php		} else { ?>
					<div class="myques hidden" id="<?php echo "panel".$questionIndex;?>">
	  <?php		} ?>
					<div class="panel panel-default">
						<div class="panel-body">
							<div class="ques" style="font-size: xx-large; font-weight: 600; color:black;">
								<?php echo $questionIndex.". ".$rows[$i]->Question; $questionIndex++; $MCQIDArray = $MCQIDArray.($rows[$i]->MCQID).',';?>
							</div>
							<br>
							<div class="options">
		<?php
			} $lastMCQID = $currentMCQID;?>
							
							<?php 
								if(!isset($rows[$i]->Choice)){ 
									if($rows[$i]->Content == $rows[$i]->CorrectChoice){
										echo "<button type=\"button\" class=\"btn btn-default btn-lg btn-block correct\"";
									} else { 
										echo "<button type=\"button\" class=\"btn btn-default btn-lg btn-block\"";
									} 
								} else { 
									if($rows[$i]->CorrectChoice == $rows[$i]->Choice){
										if($rows[$i]->Content == $rows[$i]->CorrectChoice){
											echo "<script>$(\"#button\"+".($questionIndex-1).").addClass(\"correct\");</script>";
											echo "<button type=\"button\" class=\"btn btn-default btn-lg btn-block correct\"";
										} else {
											echo "<button type=\"button\" class=\"btn btn-default btn-lg btn-block\"";
										}
									} else {
										if($rows[$i]->Content == $rows[$i]->CorrectChoice){ 
											echo "<button type=\"button\" class=\"btn btn-default btn-lg btn-block correct\"";
										} else if($rows[$i]->Content == $rows[$i]->Choice){
											echo "<script>$(\"#button\"+".($questionIndex-1).").addClass(\"wrong\");</script>";
											echo "<button type=\"button\" class=\"btn btn-default btn-lg btn-block wrong\"";
										} else {
											echo "<button type=\"button\" class=\"btn btn-default btn-lg btn-block\"";
										}
									}
								}
						?>
								
								name="<?php echo $rows[$i]->MCQID;?>" value="<?php echo $rows[$i]->Content;?>">
								<input type="radio"/><label><?php echo $rows[$i]->Content;?></label><br><?php echo $rows[$i]->Explanation; ?></button>
								
		<?php
			  if(($i+1)==sizeof($rows)){ ?>
							</div>
							<br>
							<div class="nav-options" style="text-align: center;">
								<a class="btn btn-default last" role="button" style="padding-top:8px; padding-bottom: 10px;"><span class="glyphicon glyphicon-chevron-left"></span></a>
							</div>
						</div>
					</div>
				</div>
		<?php } else {
				$nextMCQID = $rows[$i+1]->MCQID;
				
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
		</div>
        </div>
    </body>
</html>



