<?php 
    //if true, echo debug output in dev mode, else production mode
	$DEBUG_MODE = true;
    session_start();    
	require_once('connection.php');
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        if(isset($_GET["score"]) && isset($_GET["gameid"])){
            $score = $_GET["score"];
            $gameid = $_GET["gameid"];
            if(isset($_SESSION['userid'])){
                $studentid = $_SESSION['userid'];
                echo "<script language=\"javascript\">  console.log(\"This is DEBUG_MODE with SESSION studentid = ".$studentid.".\"); </script>";
                $conn = db_connect();
                $update_stmt = "INSERT INTO Game_Record(GameID,StudentID,`Level`,Score)
                             VALUES (?,?,?,?) ON DUPLICATE KEY UPDATE Score = ?;";			
                $update_stmt = $conn->prepare($update_stmt);
                for($i=1; $i<=count($score); $i++){               
                    if(! $update_stmt -> execute(array($gameid, $studentid, $i, $score[$i-1], $score[$i-1]))){
                        echo "<script language=\"javascript\">  alert(\"Error occurred to submit game score. Report this bug to reseachers.\"); </script>";
                    } else{            
                        echo "<script language=\"javascript\">  console.log(\"Game Record Submitted. gameid: $gameid  studentid: $studentid\"); </script>";
                    }
                }
                db_close($conn);
            }else{
                echo "<script language=\"javascript\">  console.log(\"You have not logged in.\"); </script>";
                if($DEBUG_MODE) {
                    $studentid = 1;
                    echo "<script language=\"javascript\">  console.log(\"This is DEBUG_MODE with TEST studentid = ".$studentid.".\"); </script>";
                    $conn = db_connect();
                    $update_stmt = "INSERT INTO Game_Record(GameID,StudentID,`Level`,Score)
                             VALUES (?,?,?,?) ON DUPLICATE KEY UPDATE Score = ?;";			
                    $update_stmt = $conn->prepare($update_stmt);
                    for($i=1; $i<=count($score); $i++){               
                        if(! $update_stmt -> execute(array($gameid, $studentid, $i, $score[$i-1], $score[$i-1]))){
                            echo "<script language=\"javascript\">  alert(\"Error occurred to submit game score. Report this bug to reseachers.\"); </script>";
                        } else{            
                            echo "<script language=\"javascript\">  console.log(\"Game Record Submitted. gameid: $gameid  studentid: $studentid\"); </script>";
                        }
                    }
                    db_close($conn);
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
