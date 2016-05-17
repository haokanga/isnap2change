<?php 
    //if true, echo debug output in dev mode, else production mode
	$DEBUG_MODE = true;
    session_start(); 
	require_once('connection.php');
    if ($_SERVER["REQUEST_METHOD"] == "POST") {    
        if(isset($_SESSION['userid'])){
            $studentid = $_SESSION['userid'];
            echo "<script language=\"javascript\">  console.log(\"This is DEBUG_MODE with SESSION studentid = ".$studentid.".\"); </script>";
            $scoreArray = retrieve_scoreboard_data(); 
        }else{
            echo "<script language=\"javascript\">  console.log(\"You have not logged in.\"); </script>";
            if($DEBUG_MODE) {
                $studentid = 1;
                echo "<script language=\"javascript\">  console.log(\"This is DEBUG_MODE with TEST studentid = ".$studentid.".\"); </script>";
                $scoreArray = retrieve_scoreboard_data(); 
            }                
        }
        echo "score array:".join(',', $scoreArray);           
    }
    function retrieve_scoreboard_data()
    {
        global $studentid;
        $conn = db_connect();
        $retrieveScoreSql = "SELECT Username, Score FROM Student WHERE ClassID = (SELECT ClassID FROM STUDENT WHERE StudentID = ?) ORDER BY Score DESC, Username;";
        $retrieveScoreQuery = $conn->prepare($retrieveScoreSql);
        $retrieveScoreQuery->execute(array($studentid));
        $retrieveScoreResult = $retrieveScoreQuery->fetch(PDO::FETCH_OBJ);
        echo "<script language=\"javascript\">  console.log(\"[SUCCESS] Game Record Found. gameid: $gameid  studentid: $studentid level: $i score:".$retrieveScoreResult->Score."\"); </script>";
        $scoreArray[] = $retrieveScoreResult->Score;              
        db_close($conn);
    }
?>

<html>
<head>
<style>
<!--table via div-->
.rTable {   	display: block;   	width: 100%; } .rTableHeading, .rTableBody, .rTableFoot, .rTableRow{   	clear: both; } .rTableHead, .rTableFoot{   	background-color: #DDD;   	font-weight: bold; } .rTableCell, .rTableHead {   	border: 1px solid #999999;   	float: left;   	height: 17px;   	overflow: hidden;   	padding: 3px 1.8%;   	width: 28%; } .rTable:after {   	visibility: hidden;   	display: block;   	font-size: 0;   	content: " ";   	clear: both;   	height: 0; }
</style>
<script>
</script>
</head>
<body>
<h2>Scoreboard</h2> <div class="rTable"> <div class="rTableRow"> <div class="rTableHead"><strong>Name</strong></div> <div class="rTableHead"><span style="font-weight: bold;">Telephone</span></div> <div class="rTableHead">&nbsp;</div> </div> <div class="rTableRow"> <div class="rTableCell">John</div> <div class="rTableCell"><a href="tel:0123456785">0123 456 785</a></div> <div class="rTableCell">checked</div> </div> <div class="rTableRow"> <div class="rTableCell">Cassie</div> <div class="rTableCell"><a href="tel:9876532432">9876 532 432</a></div> <div class="rTableCell">checked</div> </div> </div>
</body>
</html>