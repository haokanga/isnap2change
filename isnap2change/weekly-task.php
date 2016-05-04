<?php
	
	require_once('connection.php');
	
	if ($_SERVER["REQUEST_METHOD"] == "GET") {
		
		$conn = db_connect();
		
		$week = $_GET["week"];
		$studentid = $_GET["studentid"];
		
		
		$quizNumSql = "SELECT (SELECT COUNT(*)
					   FROM   Quiz WHERE  Week = ?) - COUNT(*) 
					   FROM   Quiz_Record NATURAL JOIN Quiz
					   WHERE  StudentID = ? 
					   AND	  Week = ?";
		
		$quizNumQuery = $conn->prepare($quizNumSql);
		$quizNumQuery->execute(array($week, $studentid, $week));
		
		$quizNum = $quizNumQuery->fetchColumn();
		
		if($quizNum!=0){
			$quizIDSql = "SELECT QuizID, QuizType
						  FROM   Quiz 
					      WHERE  Week = ?
					      AND NOT EXISTS
						 (SELECT QuizID, QuizType
						  FROM   Quiz_Record NATURAL JOIN Quiz
						  WHERE  StudentID = ? AND	Week = ?)
						  ORDER BY QuizID
					      LIMIT 1";
					
			$quizIDQuery = $conn->prepare($quizIDSql);
			$quizIDQuery->execute(array($week, $studentid, $week));
		
			$quizIDRes = $quizIDQuery->fetch(PDO::FETCH_OBJ);
					
		}
		
		db_close($conn);	
		
				
	}



?>

<html>
<head>
<script>
function startQuiz(){
	
	document.getElementById("quiz").submit();
	
	
}



</script>
</head>
<body>
<div id="a" align="center">
<form id="quiz" action=learning-material.php method=post>
<?php echo $quizNum ?>
<button type=button onclick="startQuiz()" onclick="startQuiz()"> Quiz </button>
<input  type=hidden name="quizid" value=<?php echo $quizIDRes->QuizID; ?>></input>
<input  type=hidden name="quiztype" value=<?php echo $quizIDRes->QuizType; ?>></input>
</form>
</div>
</body>
</html>
