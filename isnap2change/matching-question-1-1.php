<?php
    //if true, echo debug output in dev mode, else production mode
	$DEBUG_MODE = true;    
	session_start();
    require_once("connection.php");	
    require_once("encrypt.php");      
    $conn = db_connect();
    
    //set userid    
    if(isset($_SESSION['studentid'])){
        $studentid = $_SESSION['studentid'];
        echo "<script language=\"javascript\">  console.log(\"This is DEBUG_MODE with SESSION studentID = ".$studentid.".\"); </script>";
    }else{
        if(DEBUG_MODE){
            echo "<script language=\"javascript\">  console.log(\"This is DEBUG_MODE with hard-code studentID = 1.\"); </script>";
            $studentid = 1;
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
    
    //[unused] get learning-material
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
    
    // get $matchingResult[$i] -> MatchingQuestionID;
    $matchingSql = "SELECT MatchingQuestionID, Explanation, Question, Content 
               FROM   Matching_Section NATURAL JOIN Matching_Question NATURAL JOIN Matching_Option
               WHERE  QuizID = ?
               ORDER BY MatchingQuestionID";
    $matchingQuery = $conn->prepare($matchingSql);
    $matchingQuery->execute(array($quizid));
    $matchingResult = $matchingQuery->fetchAll(PDO::FETCH_OBJ);
    
    //if submitted
    if($status == "UNGRADED" || $status == "GRADED"){
        $matching_question_record_sql = "SELECT StudentID, MatchingQuestionID, Answer, Feedback, Grading
               FROM   Matching_Question_Record NATURAL JOIN Matching_Question
               WHERE  QuizID = ?
               ORDER BY MatchingQuestionID";
        $matching_question_record_query = $conn->prepare($matching_question_record_sql);
        $matching_question_record_query->execute(array($quizid));
        $matching_question_record_result = $matching_question_record_query->fetchAll(PDO::FETCH_OBJ);
    }
    
    //if submission
    if(isset($_POST['answer']) && isset($_POST['MatchingQuestionID']) && isset($_POST['quizid'])){
        $quizid = $_POST["quizid"];
        $matchingQuestionID = $_POST["MatchingQuestionID"];    
        $answer = $_POST["answer"];
        for($i=0; $i<count($matchingQuestionID); $i++) {
            $update_stmt = "INSERT INTO Matching_Question_Record(StudentID, MatchingQuestionID, Answer)
                                     VALUES (?,?,?) ON DUPLICATE KEY UPDATE Answer = ?";			
            $update_stmt = $conn->prepare($update_stmt);                
            if(! $update_stmt -> execute(array($studentid, $matchingQuestionID[$i], htmlspecialchars($answer[$i]), htmlspecialchars($answer[$i])))){
                echo "<script language=\"javascript\">  alert(\"Error occurred to submit your answer. Report this bug to reseachers.\"); </script>";
            }
        }
        $update_stmt = "INSERT INTO Quiz_Record(QuizID, StudentID, `Status`, Score)
                                     VALUES (?,?,?,?) ON DUPLICATE KEY UPDATE Score = ?";			
        $update_stmt = $conn->prepare($update_stmt);                
        if(! $update_stmt -> execute(array($quizid, $studentid, "UNGRADED", $score, $score))){
            echo "<script language=\"javascript\">  alert(\"Error occurred to update your score. Report this bug to reseachers.\"); </script>";
        }        
        $matchingResult = null;
    }
    //if Jump from weekly tasks/learning materials
    else if(!isset($_POST['answer']) && !isset($_POST['MatchingQuestionID']) && isset($_POST["status"])){
        echo "<script language=\"javascript\">  console.log(\"Jump from weekly tasks/learning materials.\"); </script>";
    } else {
        //todo: error handling
    }
    db_close($conn); 
    
?>


<!doctype html>
<html>
    <head>
    <meta charset='utf-8'>
    <!--dragula plugin css-->
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link href='css/dragula.css' rel='stylesheet' type='text/css' />
    <link href='css/example.css' rel='stylesheet' type='text/css' />
    <script type="text/javascript" src="js/jquery-1.12.3.js"></script>
    <!--md5-->
    <script src="js/md5.min.js"></script>
    <title>1:1 matching</title>
    <style>
    .parent { display: -ms-flex; display: -webkit-flex; display: flex; }
    .parent>div { flex:1; }
    .choices { display: -ms-flex; display: -webkit-flex; display: flex; flex-direction:column; }
    .choices>div { flex:1; text-align: center; }
    .rotated {
      -webkit-transform: rotate(180deg);     /* Chrome and other webkit browsers */
      -moz-transform: rotate(180deg);        /* FF */
      -o-transform: rotate(180deg);          /* Opera */
      -ms-transform: rotate(180deg);         /* IE9 */
      transform: rotate(180deg);             /* W3C compliant browsers */

      /* IE8 and below */
      filter: progid:DXImageTransform.Microsoft.Matrix(M11=-1, M12=0, M21=0, M22=-1, DX=0, DY=0, SizingMethod='auto expand');
    } 
    </style>
    </head>
    <body>
    <script>
    function goBack()
    {
        document.getElementById("goBack").submit();
    }
    
    function submitQuiz()
    {   
        var passed = true;
        var count = 0;
        $(".choice").each(function(){
            //match md5 values
            if($(this).attr('id') !=  md5(count++)) {
                passed = false;
            }
        });
        //passed/failed feedback
        if (passed) {            
            alert("Congratulations! You have finished this quiz.");
            $("#back-btn").val("GO BACK");
            
            <button id="back-btn" type="button" onclick="goBack()" class="btn btn-success">GO BACK</button>
                <?php } else { ?>
                <button id="back-btn" type="button" onclick="return submitQuiz();" class="btn btn-success">SUBMIT</button>
            }; 
        else  alert("Failed! Try again!");
        
    }
    </script>        
    <header class="navbar navbar-static-top bs-docs-nav">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
            </button>
            <a class="navbar-brand" href="#"> Matching Quiz</a>
        </div>
        
        <!--Sumbit/Go Back Button-->
        <div class="nav navbar-nav navbar-btn navbar-right" style="margin-right:22px;">
            <form id="goBack" method=post action=weekly-task.php>
                <?php if($status == "GRADED"){ ?>
                <button id="back-btn" type="button" onclick="goBack()" class="btn btn-success">GO BACK</button>
                <?php } else { ?>
                <button id="back-btn" type="button" onclick="submitQuiz();" class="btn btn-success">SUBMIT</button>
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
    <form id="submission" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <input type=hidden name="week" value=<?php echo $week; ?> ></input>        
        <input type=hidden name="quizid" value=<?php echo $quizid; ?> ></input>
        <input type=hidden name="status" value="UNGRADED" ></input>
        <div class='examples'>      
            <div class='wrapper'>   
                <label><?php echo $matchingResult[0]->Explanation ?></label>   
                <div class="row parent">        
                    <div class='container choices'>
                        <div class="choices">
                            <?php for($i=0; $i<count($matchingResult); $i++) { ?>
                            <div class="parent">
                                <!--Questions and Arrows-->
                                <div><span><?php echo $matchingResult[$i]->Question ?> </span></div><div><img class="rotated" src="img/arrow-19-64x64.png" width="25%"/></div>
                            </div>
                            <?php } ?>
                        </div>
                    </div> 
                    <div id='sortable' class='container choices'>
                        <?php                         
                        $randomOptionArray = range(0, count($matchingResult)-1);
                        //shuffle options
                        if($status != "GRADED")
                            shuffle($randomOptionArray);
                        foreach ($randomOptionArray as $value) { ?>
                        <div class="choice" id="<?php echo encryptMD5($value) ?>" ><?php echo $matchingResult[$value]->Content ?></div>
                         <?php } ?>
                    </div>       
                </div>
          </div>
        </div>
    </form>    
    <!--dragula plugin js-->
    <script src='js/dragula.js'></script>
    <script src='js/example.min.js'></script> 
    </body>
</html>