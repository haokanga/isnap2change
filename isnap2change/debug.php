<?php
    //if true, echo debug output in dev mode, else production mode
	$DEBUG_MODE = true;
    
    function debug_log($message){
        echo "<script language=\"javascript\">  console.log(\"".$message."\"); </script>";
    }
    function debug_pdo_err($pageName, $e){
        debug_log("MySQL Error occurred in ".$pageName.". Contact with developers.");        
        echo $e->getMessage();
    }
?>