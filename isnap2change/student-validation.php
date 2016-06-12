<?php   
    require_once('/debug.php');    
    if(isset($_SESSION['studentid'])){
        $studentID = $_SESSION['studentid'];
        debug_log("This is DEBUG_MODE with SESSION studentID = ".$studentID.".");
    }else{
        if($DEBUG_MODE){
            debug_log("This is DEBUG_MODE with hard-code studentID = 1.");
            $studentID = 1;
        } else {            header("location:login.php");
        }
    }
?>