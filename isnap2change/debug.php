<?php
    //if true, echo debug output in dev mode, else production mode
	$DEBUG_MODE = true;
    
    function debug_log($message){
        if($GLOBALS['DEBUG_MODE']){
            echo "<script language=\"javascript\">  console.log(\"".$message."\"); </script>";
        }
    }
    
    function debug_err($message){
        echo "<script language=\"javascript\">  alert(\"".$message."\"); </script>";
    }
    
    function debug_pdo_err($pageName, $e){
        // duplicate entry
        if ($e->errorInfo[1] == 1062) {          
            debug_err("Duplicate names are not allowed!");
        } 
        // unclassfied error occurred
        else {          
            //debug_err("Unexpected MySQL Error occurred in ".$pageName.". Contact with developers.");        
            //sql insert.. $e->getMessage();
            INSERT INTO BugReport value($pagename, $getMessage);
        }
    }
?>