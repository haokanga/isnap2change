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
        <title>Quiz</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="css/feedback.css" />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
        <link href='https://fonts.googleapis.com/css?family=Raleway:400|Open+Sans' rel='stylesheet' type='text/css'>
        <link href='https://maxcdn.bootstrapcdn.com/font-awesome/4.6.2/css/font-awesome.min.css' rel='stylesheet' type='text/css'>
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
				var MCQIDArr = document.getElementById("hiddenMCQIDArray").value.split(',');
				var answerArr = new Array(MCQIDArr.length);	
					
				$(".options").each(function(i) {
					$(this).find(".btn").each(function() {
						if($(this).hasClass("active")){
							answerArr[i]=$(this).val();
						}
					});
				});
				
				var xmlhttp = new XMLHttpRequest();
				
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						parseScript(xmlhttp.responseText);
					} else {
						
					}
				};
				
				xmlhttp.open("POST", "multiple-choice-question-feedback-v1.php", true);
				xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xmlhttp.send("MCQIDArr="+JSON.stringify(MCQIDArr)+"&answerArr="+JSON.stringify(answerArr)+"&quizid="+<?php echo $quizid;?>);

			}
			
			
        </script>
		
		<header class="navbar navbar-static-top bs-docs-nav">

            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                </button>
                <a class="navbar-brand" href="#">QUIZ</a>

            </div>

        </header>
		
		<div class="content"> 

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
								
								<button type="button" class="btn btn-default btn-lg btn-block" name="<?php echo $rows[$i]->MCQID;?>" value="<?php echo $rows[$i]->Content;?>">
								<input type="radio"/><label><?php echo $rows[$i]->Content;?></label></button>
			
		<?php
			  if(($i+1)==sizeof($rows)){ ?>
							</div>
							<br>
							<div class="last">
								<a class="btn btn-default" role="button" style="padding-top:8px;"><span class="glyphicon glyphicon-chevron-left"></span></a>
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
            
			<button type="button" onclick="return submitQuiz();" class="btn btn-success">SUBMIT</button>    
            <input type="hidden" id="hiddenIndex" value="1">
			<input type=hidden id="hiddenMCQIDArray" value="<?php echo substr($MCQIDArray, 0, strlen($MCQIDArray)-1); ?>">
		</div>
        </div>
    </body>
</html>

