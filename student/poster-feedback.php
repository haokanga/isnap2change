<?php
	require_once("../mysql-lib.php");
	require_once("../debug.php");

	$pageName = "poster-feedback";

	//check whether a request is GET or POST
	if($_SERVER["REQUEST_METHOD"] == "POST"){

		if(isset($_POST['quiz_id']) && isset($_POST['student_id']) && isset($_POST['action'])){

			$quizID = $_POST['quiz_id'];
			$studentID = $_POST['student_id'];
			$action = $_POST['action'];

			if($action == "SAVE"){
				if(isset($_POST['zwibbler_doc'])){
					$zwibblerDoc = $_POST['zwibbler_doc'];
				} else{

				}
			}

			if($action == "SUBMIT"){
				if(isset($_POST['zwibbler_doc']) && isset($_POST['data_url'])){
					$zwibblerDoc = $_POST['zwibbler_doc'];
					$dataUrl = $_POST['data_url'];
				}else{

				}
			}

		} else{

		}

	} else{

	}

	$feedback = array();
	$conn = null;

	try {
		$conn = db_connect();

		$conn->beginTransaction();

		//if save, update poster document in the Poster_Record
		if($action == "SAVE"){
			updatePosterDraft($conn, $quizID, $studentID, $zwibblerDoc);
		}

		//if submit, update poster document and image url in the Poster_Record
		if($action == "SUBMIT"){
			//convert Base64 TO Image
			$tmpid = rand();
			$imageUrl = "../poster_img/".$studentID."_".$quizID."_".$tmpid.".png";

			while(file_exists($imageUrl)){
				$tmpid = rand();
				$imageUrl = "../poster_img/".$studentID."_".$quizID."_".$tmpid.".png";
			}

			base64_to_img($dataUrl, $imageUrl);

			updatePosterSubmission($conn, $quizID, $studentID, $zwibblerDoc, $imageUrl);
		}

		//update Quiz_Record
		if($action == "SAVE"){
			$status = "UNSUBMITTED";
		}

		if($action == "SUBMIT"){
			$status = "UNGRADED";
		}

		updateQuizRecord($conn, $quizID, $studentID, $status);

		$conn->commit();


	} catch(Exception $e) {
		if($conn != null) {
			$conn->rollBack();
			db_close($conn);
		}

		debug_err($e);
		$feedback["message"] = $e->getMessage();
		echo json_encode($feedback);
		exit;
	}

	db_close($conn);
	$feedback["action"] = $action;
	$feedback["message"] = "success";
	echo json_encode($feedback);

	function base64_to_img($base64_string, $output_file) {
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