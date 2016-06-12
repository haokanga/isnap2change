<?php   
    require_once('/debug.php');    
    if(isset($_SESSION['studentid'])){
        $studentid = $_SESSION['studentid'];
        debug_log("This is DEBUG_MODE with SESSION studentID = ".$studentid.".");
    }else{
        if($DEBUG_MODE){
            debug_log("This is DEBUG_MODE with hard-code studentID = 1.");
            $studentid = 1;
        } else {            header("location:login.php");
        }
    }
?>