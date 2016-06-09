<?php
	$studentid = 1;
	$quizid = 5;
?>
<html>
<head>
<style type = "text/css">
#dataSlider1 .slider-selection {
	background: #BABABA;
}

#dataSlider2 .slider-selection {
	background: #BABABA;
}

#dataSlider3 .slider-selection {
	background: #BABABA;
}

#dataSlider4 .slider-selection {
	background: #BABABA;
}
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/7.1.0/css/bootstrap-slider.css">
<script src="js/jquery-1.12.3.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/7.1.0/bootstrap-slider.js"></script>
<script>
	function goBack() {
			document.getElementById("goBack").submit();
	}

	function parseFeedback(response) {
		var feedback = JSON.parse(response);
		
		if(feedback.message != "success") {
			alert(feedback.message + ". Please try again!");
			return;
		}
		
		if(feedback.result == "pass") {
			alert("Congratulation! You have passed this quiz.");
			$("#submitBtn").attr("disabled","disabled");
		} else if(feedback.result == "fail") {
			alert("Sorry! You have failed this quiz.");
		}
		
	}

	function submitQuiz() {		
		var answerArr = new Array(3);	
		
		$("input:text[name='studentAns']").each(function(i) {
			answerArr[i] = $(this).val();
		});

	
		var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					parseFeedback(xmlhttp.responseText);
				} 
			};
				
			xmlhttp.open("POST", "cost-calculator-feedback.php", true);
			xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xmlhttp.send("studentid="+<?php echo $studentid;?>+"&quizid="+<?php echo $quizid;?>+"&answerArr="+JSON.stringify(answerArr));
	}
	
</script>

</head>
<body>
	<div id="costCalculator" style="width:800px">
		<div id="calField" style="float:left; width:70%">
			<h4>How much do you pay for a pack of cigarettes?</h4>
			<input id="slider1" data-slider-id='dataSlider1' type="text" data-slider-min="0" data-slider-max="50" data-slider-step="1" data-slider-value="1"/>
			<span>$<span id="sliderVal1">1</span></span>
			<h4>How many cigarettes are in each pack?</h4>
			<input id="slider2" data-slider-id='dataSlider2' type="text" data-slider-min="1" data-slider-max="50" data-slider-step="1" data-slider-value="20"/>
			<span><span id="sliderVal2">20</span></span>
			<h4>How many cigarettes do you smoke each day?</h4>
			<input id="slider3" data-slider-id='dataSlider3' type="text" data-slider-min="1" data-slider-max="100" data-slider-step="1" data-slider-value="1"/>
			<span><span id="sliderVal3">1</span></span>
			<h4>How many years have you been smoking?</h4>
			<input id="slider4" data-slider-id='dataSlider4' type="text" data-slider-min="0" data-slider-max="100" data-slider-step="1" data-slider-value="5"/>
			<span><span id="sliderVal4">5</span></span>
		</div>
		<div id="txtField" style="float:left; width:30%">
			<h2>Weekly cost</h2>
			<span id="weeklyCostTxt"></span>
			<h4>Monthly cost</h4>
			<span id="monthlyCostTxt"></span>
			<h4>Yearly cost</h4>
			<span id="yearlyCostTxt"></span>
			<h4>Five year cost</h4>
			<span id="fiveYrCostTxt"></span>
			<h4>Ten year cost</h4>
			<span id="tenYrCostTxt"></span>
			<h4>Cost to date</h4>
			<span id="dateCostTxt"></span>
		</div>
	</div>
	</br>
	<div>
		<h4>What would the cost be of smoking 10 cigarettes a day for 10 years if a packet of 20 cigarettes costs $25?</h4>
		<input type="text" name="studentAns">
		<h4>What would the cost be of smoking 20 cigarettes a day for 20 years if a packet of 20 cigarettes costs $25?</h4>
		<input type="text" name="studentAns">
		<h4>What would the cost be of smoking 40 cigarettes a day for 20 years if a packet of 20 cigarettes costs $25?</h4>
		<input type="text" name="studentAns">
		<br><br>
		<button id="submitBtn" type="button" onclick="submitQuiz()">Submit</button>
		<form id="goBack" method=post action=weekly-task.php>
			<button type="button" onclick="goBack()">GO BACK</button> 
			<input type=hidden name="week" value=<?php echo $week; ?>></input>
		</form>
	</div>
<script>
	
	var slider1 = $("#slider1").slider();
	$("#slider1").on("slide", function(slideEvt) {
		$("#sliderVal1").text(slideEvt.value);
		calculateCost();
	});
	
	var slider2 = $("#slider2").slider();
	$("#slider2").on("slide", function(slideEvt) {
		$("#sliderVal2").text(slideEvt.value);
		calculateCost();
	});

	var slider3 = $("#slider3").slider();
	$("#slider3").on("slide", function(slideEvt) {
		$("#sliderVal3").text(slideEvt.value);
		calculateCost();
	});

	var slider4 = $("#slider4").slider();
	$("#slider4").on("slide", function(slideEvt) {
		$("#sliderVal4").text(slideEvt.value);
		calculateCost();
	});
	
	calculateCost();
	
	function calculateCost() {
		var formatCost = function(cost){
			cost = cost.toFixed(2);
			cost += '';
			var costParts = cost.split('.');
			cost1 = costParts[0].replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
			cost2 = costParts.length > 1 ? '.' + costParts[1] : '';

			return '$' + cost1 + cost2;
		}

		var costPerPack = $("#slider1").slider('getValue');
		var cigarettesPerPack = $("#slider2").slider('getValue');
		var cigarettesPerDay = $("#slider3").slider('getValue');
		var numberOfYears = $("#slider4").slider('getValue');

		var costPerDay, costPerWeek, costPerYear, costPerMonth, costPer5Years, costPer10Years, costToDate;

		if(numberOfYears > 0 && cigarettesPerDay > 0){
			costPerDay = (costPerPack / cigarettesPerPack) * cigarettesPerDay;
			costPerWeek = costPerDay * 7;
			costPerYear = costPerDay * 365;
			costPerMonth = costPerYear / 12;
			costPer5Years = costPerYear * 5;
			costPer10Years = costPerYear * 10;
			costToDate = costPerYear * numberOfYears;
		}
		else{
			costPerDay = costPerWeek = costPerYear = costPerMonth = costPer5Years = costPer10Years = costToDate = gramsTarPerYear = percentageTar = 0;
		}
		
		$('#weeklyCostTxt').html(formatCost(costPerWeek));
		$('#monthlyCostTxt').html(formatCost(costPerMonth));
		$('#yearlyCostTxt').html(formatCost(costPerYear));
		$('#fiveYrCostTxt').html(formatCost(costPer5Years));
		$('#tenYrCostTxt').html(formatCost(costPer10Years));
		$('#dateCostTxt').html(formatCost(costToDate));
	}
</script>
</body>
</html>