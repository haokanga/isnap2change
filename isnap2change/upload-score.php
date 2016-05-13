<?php 
    //if true, echo debug output in dev mode, else production mode
	$DEBUG_MODE = true;
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    }    
	require_once('connection.php');
    require_once('retrieve-stored-score.php');
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        if(isset($_GET["score"]) && isset($_GET["gameid"])){
            $score = $_GET["score"];
            $gameid = $_GET["gameid"];
            if(isset($_SESSION['userid'])){
                $studentid = $_SESSION['userid'];
                echo "<script language=\"javascript\">  console.log(\"This is DEBUG_MODE with SESSION studentid = ".$studentid.".\"); </script>";
                $highscore = retrieve_data();
                upload_score();
            }else{
                echo "<script language=\"javascript\">  console.log(\"You have not logged in.\"); </script>";
                if($DEBUG_MODE) {
                    $studentid = 1;
                    echo "<script language=\"javascript\">  console.log(\"This is DEBUG_MODE with TEST studentid = ".$studentid.".\"); </script>";
                    $highscore = retrieve_data();
                }
            }            
        }
    }
    function upload_score()
    {
        global $gameid, $studentid, $score, $highscore;
        $conn = db_connect();
        $update_stmt = "INSERT INTO Game_Record(GameID,StudentID,`Level`,Score)
                     VALUES (?,?,?,?) ON DUPLICATE KEY UPDATE Score = ?;";			
        $update_stmt = $conn->prepare($update_stmt);
        for($level=1; $level<=count($score); $level++){
            if($score[$level-1]>$highscore[$level-1]){
                if(! $update_stmt -> execute(array($gameid, $studentid, $level, $score[$level-1], $score[$level-1]))){
                    echo "<script language=\"javascript\">  alert(\"Error occurred to submit game score. Report this bug to reseachers.\"); </script>";
                } else{            
                    echo "<script language=\"javascript\">  console.log(\"Game Record Submitted. gameid: $gameid  studentid: $studentid\"); </script>";
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
Score:<input name="score[]" value="15"></input>
Score:<input name="score[]" value="15"></input>
Score:<input name="score[]" value="15"></input>
Score:<input name="score[]" value="15"></input>
Score:<input name="score[]" value="15"></input>
<input type="submit" name='submit' value="Submit" class='submit'/>
</form>
-->
</body>
</html>