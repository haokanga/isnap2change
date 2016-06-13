<?php 
    //if true, echo debug output in dev mode, else production mode
	$DEBUG_MODE = true;
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    }    
	require_once('mysql-lib.php');
    require_once('retrieve-stored-score.php');
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        if(isset($_GET["score"]) && isset($_GET["gameid"])){
            $score = $_GET["score"];
            $gameid = $_GET["gameid"];
            if(isset($_SESSION['studentID'])){
                $studentID = $_SESSION['studentID'];
                echo "<script language=\"javascript\">  console.log(\"This is DEBUG_MODE with SESSION studentid = ".$studentID.".\"); </script>";
                $highscore = retrieve_data();
                upload_score();
            }else{
                echo "<script language=\"javascript\">  console.log(\"You have not logged in.\"); </script>";
                if($DEBUG_MODE) {
                    $studentID = 1;
                    echo "<script language=\"javascript\">  console.log(\"This is DEBUG_MODE with TEST studentid = ".$studentID.".\"); </script>";
                    $highscore = retrieve_data();                    upload_score();
                }
            }            
        }
    }
    function upload_score()
    {
        global $gameid, $studentID, $score, $highscore;
        $conn = db_connect();
        $updateSql = "INSERT INTO Game_Record(GameID,StudentID,`Level`,Score)
                     VALUES (?,?,?,?) ON DUPLICATE KEY UPDATE Score = ?";			
        $updateSql = $conn->prepare($updateSql);
        for($level=1; $level<=count($score); $level++){
            if($score[$level-1]>$highscore[$level-1]){
                if(! $updateSql -> execute(array($gameid, $studentID, $level, $score[$level-1], $score[$level-1]))){
                    echo "<script language=\"javascript\">  alert(\"Error occurred to submit game score. Report this bug to reseachers.\"); </script>";
                } else{            
                    echo "<script language=\"javascript\">  console.log(\"Game Record Submitted. gameid: $gameid  studentid: $studentID\"); </script>";
                }
            }
            else{
                echo "<script language=\"javascript\">  console.log(\"Score does not exceed highscore. highscore: ".$highscore[$level-1]."  score: ".$score[$level-1]."\"); </script>";
            }
        }
        db_close($conn);
    }
?>
<html>
<head>
</head>
<body>
<!--
<div id="a" align="center">
<form id="quiz" action="<?php echo $_SERVER['PHP_SELF']; ?>" method=get>
Score:<input name="score[]" value="15">
Score:<input name="score[]" value="15">
Score:<input name="score[]" value="15">
Score:<input name="score[]" value="15">
Score:<input name="score[]" value="15">
<input type="submit" name='submit' value="Submit" class='submit'/>
</form>
-->
</body>
</html>
