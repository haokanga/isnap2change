<?php

//    session_start();
	require_once('connection.php');
/*	
	if(!isset($_SESSION["studentid"])){
		
	}
*/	
//	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		
//		if(isset($_POST["quizid"]) && isset($_POST["quiztype"]) && isset($_POST["week"])){
			$quizid = $_GET["quizid"];
//			$quizid = $_POST["quizid"];
//			$quiztype = $_POST["quiztype"];
//			$week = $_POST["week"];
          
//		} else {
			
//		}
		
//	} else {
		
//	}
	
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
        <title>QUIZ</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href='https://fonts.googleapis.com/css?family=Raleway:400italic|Open+Sans' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="css/ver2.css" type="text/css">
        <script type="text/javascript" src="js/jquery-1.12.3.js"></script>
    </head>
    <body>
        <script>
            $(document).ready(function ()
            {
                $(".options").find(".btn").click(function () {
					var index = $("#hiddenIndex").val();
					$("#panel"+index).find(".btn").removeClass("active");
                    $(this).addClass("active");
                });
                
                 $(".next").find(".btn").click(function () {
                    var index = $("#hiddenIndex").val();
					$("#panel"+index).addClass("hidden");
					index++;
                    $("#panel"+index).removeClass("hidden");
					$("#hiddenIndex").val(index);
                });
				
				$(".last").find(".btn").click(function () {
                    var index = $("#hiddenIndex").val();
					$("#panel"+index).addClass("hidden");
					index--;
                    $("#panel"+index).removeClass("hidden");
					$("#hiddenIndex").val(index);
                });

            });
        </script>

        <div class="row" style="position: fixed;">              
            <div class="col-md-2 sidebar bar">
                <img src="css/l2.jpg" alt="" class="img-circle" style="height: 70px; display: block;
                     margin: 0 auto; padding-top:15px;">
                <ul class="list-group lg" >
                    <li class="list-group-item" style="margin-top:25px;">
                        <input type="radio" name="radio1" id="radio1" />
                        <label for="radio1">INFORMATION
                        </label>
                    </li>
                    <li class="list-group-item" style="margin-top:10px;">
                        <input type="radio" name="radio1" id="radio2" />
                        <label for="radio2">QUESTION 1
                        </label>
                    </li>
                    
                </ul>
            </div>

		<?php for($i=0; $i<count($rows); $i++) {
			
			$currentMCQID = $rows[$i] -> MCQID;
							
			if($currentMCQID != $lastMCQID){ 
				if($questionIndex == 1){ ?>
					<div class="col-md-offset-1 col-md-8 question" style="text-align:center;" id="panel1">
	  <?php		} else { ?>
					<div class="col-md-offset-1 col-md-8 question hidden" style="text-align:center;" id="<?php echo "panel".$questionIndex;?>">
	  <?php		} ?>
					<div class="panel panel-default">
						<div class="panel-body">
							<div class="ques" style="font-size: x-large; color:yellow;">
								<?php echo $questionIndex.". ".$rows[$i]->Question; $questionIndex++; $MCQIDArray = $MCQIDArray.($rows[$i]->MCQID).',';?>
							</div>
							<br>
							<div class="options">
		<?php
			} $lastMCQID = $currentMCQID;?>
								
								<button type="button" class="btn btn-default btn-lg btn-block" name="<?php echo $rows[$i]->MCQID;?>" value="<?php echo $rows[$i]->Content;?>"><?php echo $rows[$i]->Content;?></button>
			
		<?php
			  if(($i+1)==sizeof($rows)){ ?>
							</div>
							<br>
							<div class="last">
								<a class="btn btn-default" href="#" role="button" style="padding-top:8px;"><span class="glyphicon glyphicon-chevron-left"></span></a>
							</div>
						</div>
					</div>
				</div>
		<?php } else {
				$nextMCQID = $rows[$i+1]->MCQID;
				
				if($nextMCQID != $currentMCQID){ ?>
							</div>
							<br>
		<?php					
					if($questionIndex!=2){ ?>
							<div class="last">
								<a class="btn btn-default"  role="button" style="padding-top:8px;"><span class="glyphicon glyphicon-chevron-left"></span></a>
							</div>
		<?php		} ?>
							<div class="next">
								<a class="btn btn-default"  role="button" style="padding-top:8px;"><span class="glyphicon glyphicon-chevron-right"></span></a>
							</div>
						</div>
					</div>
				</div>
		<?php	}
			  }
		} ?>
            
            <div class="col-md-1"></div>
            <input type="hidden" id="hiddenIndex" value="1">
        </div>
    </body>
</html>

