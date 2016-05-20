<?php
    //if true, echo debug output in dev mode, else production mode
	$DEBUG_MODE = false;
	session_start();
    if($DEBUG_MODE && !isset($_SESSION["studentid"])){
        $_SESSION["studentid"] = 1;
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
<p>Eating a balanced diet is vital for your health and wellbeing. The food we eat is responsible for providing us with the energy to do all the tasks of daily life. For optimum performance and growth a balance of protein, essential fats, vitamins and minerals are required. We need a wide variety of different foods to provide the right amounts of nutrients for good health. The different types of food and how much of it you should be aiming to eat is demonstrated on the pyramid below. (my own words)</p>
<p><img style="display: block; margin-left: auto; margin-right: auto;" src="https://cmudream.files.wordpress.com/2016/05/0.jpg" alt="" width="632" height="884" /></p>
<p>There are three main layers of the food pyramid. The bottom layer is the most important one for your daily intake of food. It contains vegetables, fruits, grains and legumes. You should be having most of your daily food from this layer. These foods are all derived or grow on plants and contain important nutrients such as vitamins, minerals and antioxidants. They are also responsible for being the main contributor of carbohydrates and fibre to our diet.<br />The middle layer is comprised of dairy based products such as milk, yoghurt, cheese. These are essential to providing our bodies with calcium and protein and important vitamins and minerals.<br />They layer also contains lean meat, poultry, fish, eggs, nuts, seeds, legumes. These foods are our main source of protein and are also responsible for providing other nutrients to us including iodine, iron, zinc, B12 vitamins and healthy fats.<br />The top layer, which is the smallest layer, is the layer you should me eating the least off. This layer is made up of food which has unsaturated fats such as sugar, butter, margarine and oils; small amounts of these unsaturated fats are needed for healthy brain and hear function.<br />(my own words)<br />Source: The Healthy Living Pyramid. Nutrition Australia. [Accessed 28/04/2016 http://www.nutritionaustralia.org/national/resource/healthy-living-pyramid]</p>

</body>
</html>


