<?php



?>
<html>
<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/7.1.0/css/bootstrap-slider.css">
<script src="js/jquery-1.12.3.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/7.1.0/bootstrap-slider.js"></script>
</head>
<body>
<div id="calField1">
<h4>How much do you pay for a pack of cigarettes?</h4>
<input id="slider1" type="text" data-slider-min="0" data-slider-max="50" data-slider-step="1" data-slider-value="1"/>
<span>$: <span id="sliderVal1">1</span></span>
</div>
<script>
$("#slider1").slider();
$("#slider1").on("slide", function(slideEvt) {
	$("#sliderVal1").text(slideEvt.value);
});
</script>
</body>
</html>