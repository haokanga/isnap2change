<?php
    //if true, echo debug output in dev mode, else production mode
	$DEBUG_MODE = true;
    
    function debug_log($message){
        echo "<script language=\"javascript\">  alert(\"".$message."\"); </script>";
    }
?>