<?php
    //if true, echo debug output in dev mode, else production mode
	$DEBUG_MODE = true;
    
    function debug_log($message){
        echo "<script language=\"javascript\">  alert(\"".$message."\"); </script>";
    }
    function debug_pdo_err($pageName, $e){
        // duplicate entry
        if ($e->errorInfo[1] == 1062) {          
            debug_log("Duplicate names are not allowed!");
        } 
        // unclassfied error occurred
        else {          
            debug_log("Unexpected MySQL Error occurred in ".$pageName.". Contact with developers.");        
            echo $e->getMessage();
        }
    }
?>