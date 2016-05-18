<?php

	require_once("connection.php");
	
	$conn = db_connect();
	
	$studentid = 1;
	
	$progressSql = "SELECT TopicName, QuizType, `Status`, Score
					FROM Quiz NATURAL JOIN Topic
							  LEFT    JOIN (SELECT * FROM Quiz_Record WHERE StudentID = ?) Quiz_Record_1 ON Quiz.QuizID = Quiz_Record_1.QuizID
					WHERE Week = ?
					ORDER BY TopicID";
								
	//db_close($conn);

?>

<html>
<head>
<style>
table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
}
th, td {
    padding: 5px;
    text-align: left;
}
</style>
</head>
<body>
<?php for($i=0; $i<10; $i++){ ?>
	
	<table style="width:60%">
	<caption><?php echo "Week ".($i+1); ?></caption>
		  <tr>
			<th>Task</th>
			<th>Content</th>
			<th>Type</th>
			<th>Status</th>
			<th>Score</th>
		  </tr>
			
		<?php
			$week = $i+1;
			$progressQuery = $conn->prepare($progressSql);
			$progressQuery->execute(array($studentid, $week));
			$rows = $progressQuery->fetchAll(PDO::FETCH_OBJ);  
		   
		   for($j=0; $j<count($rows); $j++) { ?>
				<tr>
					<td>Quiz</td>
					<td><?php echo $rows[$j]->TopicName;?></td>
					<td><?php echo $rows[$j]->QuizType;?></td>
					<td><?php 
							if(isset($rows[$j]->Status)){
								echo $rows[$j]->Status; 
							} else echo "Not Answered";?>
					</td>
					<td><?php echo $rows[$j]->Score;?></td>
				</tr>
	<?php } ?>
	
	
	</table>
	<br>
<?php } ?>
</body>
</html>



