<?php
	
	require_once("connection.php");
	
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		
		if(isset($_POST['studentid']) && isset($_POST['zwibblerdoc'])){	
			$studentid = $_POST['studentid'];
			$zwibblerdoc = $_POST['zwibblerdoc'];
		} else {
			
		}
		
	}
	
	$conn = db_connect();
	
	$feedback = array();
	
	
	


?>