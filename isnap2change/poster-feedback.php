<?php
	
	require_once("connection.php");
	
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		
		if(isset($_POST['quizid']) && isset($_POST['studentid']) && isset($_POST['action'])) {
			
			$quizid = $_POST['quizid'];
			$studentid = $_POST['studentid'];
			$action = $_POST['action'];
			
			if($action == "SAVE") {
				if(isset($_POST['zwibblerdoc'])){
					$zwibblerdoc = $_POST['zwibblerdoc'];
				} else {
					
				}
			}
				
			if($action == "SUBMIT") {
				if(isset($_POST['zwibblerdoc']) && isset($_POST['dataurl'])) {
					$zwibblerdoc = $_POST['zwibblerdoc'];
					$dataurl = $_POST['dataurl'];
				} else {
					
				}
			}
			
		} else {
			
		}
		
	} else {
		
	}
		
	$conn = db_connect();
	$conn->beginTransaction();
	
	try {	
		//UPDATE Quiz_Record
		if($action == "SAVE") {
			$status = "UNSUBMITTED";
		}
		
		if($action == "SUBMIT") {
			$status = "UNGRADED";
		}
		
		$posterQuizSaveSql = "INSERT INTO Quiz_Record(QuizID, StudentID, Status)
							  VALUES (?,?,?) ON DUPLICATE KEY UPDATE Status = ?;";			
		
		$posterQuizSaveQuery = $conn->prepare($posterQuizSaveSql);                            
		$posterQuizSaveQuery->execute(array($quizid, $studentid, $status, $status));
		
		//UPDATE Poster_Record
		if($action == "SAVE") {
			$posterRecordSaveSql = "INSERT INTO Poster_Record(QuizID, StudentID, ZwibblerDoc)
									VALUES (?,?,?) ON DUPLICATE KEY UPDATE ZwibblerDoc= ?;";
		
			$posterRecordSaveQuery = $conn->prepare($posterRecordSaveSql);
			$posterRecordSaveQuery->execute(array($quizid, $studentid, $zwibblerdoc, $zwibblerdoc));
		}
		
		if($action == "SUBMIT") {
			//CONVERT Base64 TO PNG IMAGE
			$tmpid = rand();
			$posterurl = "./poster_img/".$studentid."_".$quizid."_".$tmpid.".png";
		
			while(file_exists($posterurl)) {
				$tmpid = rand();
				$posterurl = "./poster_img/".$studentid."_".$quizid."_".$tmpid.".png";
			}
		
			base64_to_jpeg($dataurl, $posterurl);
			
			$posterRecordSaveSql = "INSERT INTO Poster_Record(QuizID, StudentID, ZwibblerDoc, ImageURL)
									VALUES (?,?,?,?) ON DUPLICATE KEY UPDATE ZwibblerDoc = ? , ImageURL = ?;";
			
			$posterRecordSaveQuery = $conn->prepare($posterRecordSaveSql);
			$posterRecordSaveQuery -> execute(array($quizid, $studentid, $zwibblerdoc, $posterurl, $zwibblerdoc, $posterurl));
			
		}
		
		$conn->commit();
			
		//FEEDBACK
		echo "success";
	} catch(Exception $e) {
		echo $e->getMessage();
		$conn->rollback();
	}
  	
	db_close($conn);	
		
	function base64_to_jpeg($base64_string, $output_file) {
		$ifp = fopen($output_file, "wb");
		
		if($ifp == false) {
			throw new Exception('Fail to open file');
		}

		$data = explode(',', $base64_string);
		$data = str_replace(' ', '+', $data[1]);

		if(fwrite($ifp, base64_decode($data)) == false) {
			throw new Exception('Fail to write file');
		} 
		
		fclose($ifp);
	}
		
?>