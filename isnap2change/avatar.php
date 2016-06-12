<?php
    //if true, echo debug output in dev mode, else production mode
	$DEBUG_MODE = false;
	session_start();
	//$studentID = 1;
    if($DEBUG_MODE){
        $_SESSION["studentID"] = 1;
    }
?>

<html>
<head>
<script type="text/javascript" src="js/jquery-1.12.3.js"></script>
<script>


function startWeeklyTask($week){
    $("#week").val($week);
	document.getElementById("weeklytask").submit();	
}

function scoreboard(){
	document.getElementById("scoreboard").submit();	
}

function checkprogress(){
		window.location.href = "check-progress.php";
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
<form id="weeklytask" action=weekly-task.php method=post>
    <input  type=hidden id="week" name="week" value="0"></input>
	<button type=button onclick="startWeeklyTask(1)"> Week 1 </button>
	<button type=button onclick="startWeeklyTask(2)"> Week 2 </button>
	<button type=button onclick="startWeeklyTask(3)"> Week 3 </button>
	<button type=button onclick="startWeeklyTask(4)"> Week 4 </button>
	<button type=button onclick="startWeeklyTask(5)"> Week 5 </button>
	<button type=button onclick="startWeeklyTask(6)"> Week 6 </button>
	<button type=button onclick="startWeeklyTask(7)"> Week 7 </button>
	<button type=button onclick="startWeeklyTask(8)"> Week 8 </button>
	<button type=button onclick="startWeeklyTask(9)"> Week 9 </button>
	<button type=button onclick="startWeeklyTask(10)"> Week 10 </button>
</form>
</div>
<div align="center">
	<button id="checkprogress" type=button onclick="checkprogress()"> Check Progress </button>
    <form id="scoreboard" action=scoreboard.php method=post>
	<button type=button onclick="scoreboard()"> Check Scoreboard </button>
    </form>
</div>
</body>
</html>


