<?php
    //if true, echo debug output in dev mode, else production mode
	$DEBUG_MODE = true;
    
    function debug_log($message){
        if($GLOBALS['DEBUG_MODE']){
            echo "<script language=\"javascript\">  console.log(\"".$message."\"); </script>";
        }
    }
    
    function debug_alert($message){
        echo "<script language=\"javascript\">  alert(\"".$message."\"); </script>";
    }
    
    function debug_err($pageName, $e){
        if($e instanceof PDOException){
            // duplicate entry
            if ($e->errorInfo[1] == 1062) {          
                debug_alert("Duplicate names are not allowed!");
            } 
            // unclassfied error occurred
            else {          
                //debug_alert("Unexpected MySQL Error occurred in ".$pageName.". Contact with developers.");        
                //sql insert.. 
                //Logger.write($pagename, $getMessage);
            }
        } else {
            echo $e->getMessage();
        }        
    }
?>