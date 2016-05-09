<?php

	session_start();
	//$studentid = 1;
	$_SESSION["studentid"] = 1;




?>

<html>
<head>
<script>
function startWeeklyTask(){
	
	document.getElementById("weeklytask").submit();
	
}

</script>
</head>
<body>
<div id="a" align="center">
<form id="weeklytask" action=weekly-task.php method=post>
<button type=button onclick="startWeeklyTask()"> Week 1 </button>
<input  type=hidden name="week" value="1"></input>
</form>
</div>
</body>
</html>


