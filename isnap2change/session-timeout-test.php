<?php
	session_start();        
    if(isset($_SESSION['userid'])){
        $studentID = $_SESSION['userid'];
        echo "<script language=\"javascript\">  console.log(\"This is DEBUG_MODE with SESSION studentID = ".$studentID.".\"); </script>";
    }else{
        echo "<script language=\"javascript\">  console.log(\"SESSION EXPIRED!\"); </script>";
        $studentID = 1;
    }
?>