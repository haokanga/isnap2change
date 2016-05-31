<?php
	
	$studentid = $_POST["studentid"];
	$tmpid = rand();
	$target_path = "./tmp_poster_image/".$studentid."_".$tmpid."_tmp";
	
	while(file_exists($target_path)) {
		$tmpid = rand();
		$target_path = "./tmp_poster_image/".$studentid."_".$tmpid."_tmp";
	}
	
	$fileid = $studentid."_".$tmpid."_tmp";
		
	$result = array();
	
	if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
		$result['fileid'] = $fileid; 
		//echo "success";  
		//$result['status'] = "success";
	}  else{  
	
	}  	
	
	echo json_encode($result);
	
?>