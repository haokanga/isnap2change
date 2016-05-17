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

function scoreboard(){	
	document.getElementById("scoreboard").submit();	
}

</script>
</head>
<body>
<!--
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
-->
<div align="center">
	<button type=button onclick="startWeeklyTask()"> Week 1 </button>
	<button type=button onclick="startWeeklyTask()"> Week 2 </button>
	<button type=button onclick="startWeeklyTask()"> Week 3 </button>
	<button type=button onclick="startWeeklyTask()"> Week 4 </button>
	<button type=button onclick="startWeeklyTask()"> Week 5 </button>
	<button type=button onclick="startWeeklyTask()"> Week 6 </button>
	<button type=button onclick="startWeeklyTask()"> Week 7 </button>
	<button type=button onclick="startWeeklyTask()"> Week 8 </button>
	<button type=button onclick="startWeeklyTask()"> Week 9 </button>
	<button type=button onclick="startWeeklyTask()"> Week 10 </button>
</div>
<br>
<div align="center">
	<button type=button> Check Progress </button>
    <form id="scoreboard" action=scoreboard.php method=post>
	<button type=button onclick="scoreboard()"> Check Scoreboard </button>
    </form>
</form>
</div>
</body>
</html>


