<?php
	
	$studentID = $_POST["studentID"];

	$tmpid = rand();
	$target_path = "./tmp_poster_img/".$studentID."_".$tmpid."_tmp";
	
	while(file_exists($target_path)) {
		$tmpid = rand();
		$target_path = "./tmp_poster_img/".$studentID."_".$tmpid."_tmp";
	}
	
	$fileid = $studentID."_".$tmpid."_tmp";
		
	$result = array();
	
	if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
		$result['fileid'] = $fileid; 
		//echo "success";  
		$result['message'] = "success";
	}  else{  
		$result['message'] = "Fail to upload image";
	}  	
	
	echo json_encode($result);
	
?>