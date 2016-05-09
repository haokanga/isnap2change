<?php

	session_start();
	//$studentid = 1;
	$_SESSION["studentid"] = 1;

?>

<html>
<head>
<script>
function startWeeklyTask1(){
	
	document.getElementById("weeklytask1").submit();
	
}
function startWeeklyTask3(){
	
	document.getElementById("weeklytask3").submit();
	
}

</script>
</head>
<body>
<div id="a" align="center">
<form id="weeklytask1" action=weekly-task.php method=post>
<button type=button onclick="startWeeklyTask1()"> Week 1 </button>
<input  type=hidden name="week" value="1"></input>
</form>
<form id="weeklytask3" action=weekly-task.php method=post>
<button type=button onclick="startWeeklyTask3()"> Week 3 </button>
<input  type=hidden name="week" value="3"></input>
</form>
</div>
</body>
</html>


