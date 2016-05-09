<?php
    /**
    session_set_cookie_params(5);
	session_start();        
    if(isset($_SESSION['userid'])){
        $studentID = $_SESSION['userid'];
        echo "<script language=\"javascript\">  console.log(\"This is DEBUG_MODE with SESSION studentID = ".$studentID.".\"); </script>";
    }else{
        echo "<script language=\"javascript\">  console.log(\"SESSION EXPIRED!\"); </script>";
        $studentID = 1;
    }
    */
    
    session_start();
    $idletime=36000;//after n seconds the user gets logged out    
    if(isset($_SESSION['timestamp'])){
        if (isset($_SESSION['timestamp']) && time()-$_SESSION['timestamp']>$idletime){
            session_destroy();
            session_unset();
            echo "<script language=\"javascript\">  console.log(\"SESSION EXPIRED AS EXPECTED!\"); </script>";
            header("location: login.php");
        } else {
            $studentID = $_SESSION['userid'];
            echo "<script language=\"javascript\">  console.log(\"This is DEBUG_MODE with SESSION studentID = ".$studentID.".\"); </script>";
            $_SESSION['timestamp']=time();
        }       
    }else{
        echo "<script language=\"javascript\">  console.log(\"UNEXPECTED SESSION EXPIRATION\"); </script>";
    }
    //on session creation
    $_SESSION['timestamp']=time();
?>