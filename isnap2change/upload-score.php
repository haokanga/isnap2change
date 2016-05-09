<?php 
    //if true, echo debug output in dev mode, else production mode
	$DEBUG_MODE = true;
    session_start();    
	require_once('connection.php');
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        if(isset($_GET["score"])){
            $score = $_GET["score"];
            $gameid = 1;
            if(isset($_SESSION['userid'])){
                $studentid = $_SESSION['userid'];
                echo "<script language=\"javascript\">  console.log(\"This is DEBUG_MODE with SESSION studentid = ".$studentid.".\"); </script>";
                $conn = db_connect();
                $update_stmt = "REPLACE INTO Game_Record(GameID,StudentID,Score)
                             VALUES (?,?,?);";			
                $update_stmt = $conn->prepare($update_stmt);                            
                if(! $update_stmt -> execute(array($gameid, $studentid, $score))){
                    echo "<script language=\"javascript\">  alert(\"Error occurred to submit game score. Report this bug to reseachers.\"); </script>";
                } else{            
                    echo "<script language=\"javascript\">  console.log(\"Game Record Submitted. gameid: $gameid  studentid: $studentid\"); </script>";
                }
            }else{
                echo "<script language=\"javascript\">  console.log(\"You have not logged in.\"); </script>";
                if($DEBUG_MODE) {
                    $studentid = 1;
                    echo "<script language=\"javascript\">  console.log(\"This is DEBUG_MODE with HARD-CODE studentid = ".$studentid.".\"); </script>";
                    $conn = db_connect();
                    $update_stmt = "REPLACE INTO Game_Record(GameID,StudentID,Score)
                                 VALUES (?,?,?);";			
                    $update_stmt = $conn->prepare($update_stmt);                            
                    if(! $update_stmt -> execute(array($gameid, $studentid, $score))){
                        echo "<script language=\"javascript\">  alert(\"Error occurred to submit game score. Report this bug to reseachers.\"); </script>";
                    } else{            
                        echo "<script language=\"javascript\">  console.log(\"Game Record Submitted. gameid: $gameid  studentid: $studentid\"); </script>";
                    }
                }
            }            
        }
    }
?>
<html>
<head>
</head>
<body>
<!--
<div id="a" align="center">
<form id="quiz" action="<?php echo $_SERVER['PHP_SELF']; ?>" method=post>
Score:<input name="score" value="40"></input>
<input type="submit" name='submit' value="Submit" class='submit'/>
</form>
</div>
-->
</body>
</html>
