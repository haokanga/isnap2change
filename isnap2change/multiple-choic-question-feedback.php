<?php
	
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		if(isset($_POST['MCQIDArr']) && isset($_POST['AnswerArr'])){
			echo json_decode($_POST['MCQIDArr']);
		}
	}

?>