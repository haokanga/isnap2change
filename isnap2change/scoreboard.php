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

</body>
</html>
<?php 
    //if true, echo debug output in dev mode, else production mode
	$DEBUG_MODE = false;
    session_start(); 
	require_once('connection.php');
    if ($_SERVER["REQUEST_METHOD"] == "POST") {    
        if(isset($_SESSION['userid'])){
            $studentid = $_SESSION['userid'];
            if($DEBUG_MODE){
                echo "<script language=\"javascript\">  console.log(\"This is DEBUG_MODE with SESSION studentid = ".$studentid.".\"); </script>";
            }
            $scoreArray = retrieve_scoreboard_data(); 
        }else{
            if($DEBUG_MODE) {                
                echo "<script language=\"javascript\">  console.log(\"You have not logged in.\"); </script>";
                $studentid = 1;
                echo "<script language=\"javascript\">  console.log(\"This is DEBUG_MODE with TEST studentid = ".$studentid.".\"); </script>";
                $scoreArray = retrieve_scoreboard_data(); 
            } else {
                echo "You have not logged in.";
            }                
        }          
    } else {
        echo "Only POST request is allowed.";
    }
    function retrieve_scoreboard_data()
    {
        global $studentid, $DEBUG_MODE;
        $conn = db_connect();
        $retrieveScoreSql = "SELECT Username, Score FROM Student WHERE ClassID = (SELECT ClassID FROM STUDENT WHERE StudentID = ?) ORDER BY Score DESC, Username";
        $retrieveScoreQuery = $conn->prepare($retrieveScoreSql);
        $retrieveScoreQuery->execute(array($studentid));
        $count = 0;
        echo '<h2>Scoreboard</h2> <div class="rTable"> 
        <div class="rTableRow"> <div class="rTableHead"><strong>Rank</strong></div> <div class="rTableHead"><span style="font-weight: bold;">Username</span></div> <div class="rTableHead">Score</div> </div> ';
        while($retrieveScoreResult = $retrieveScoreQuery->fetch(PDO::FETCH_ASSOC)){
            $count++;
            if($DEBUG_MODE){
                echo "<script language=\"javascript\">  console.log(\"[SUCCESS] Score Record Found. studentid: $studentid username:".$retrieveScoreResult['Username']." score:".$retrieveScoreResult['Score']."\"); </script>";
            }
            echo '<div class="rTableRow"><div class="rTableCell">'.$count.'</div> <div class="rTableCell">'.$retrieveScoreResult["Username"].'</div> <div class="rTableCell">'.$retrieveScoreResult["Score"].'</div> </div>';
        }
        echo '</div>';        
        db_close($conn);
    }
?>