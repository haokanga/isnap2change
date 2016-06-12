<?php    
    require_once("../debug.php");    
    if(isset($_SESSION['researcherid'])){
        $researcherid = $_SESSION['researcherid'];
        debug_log("This is DEBUG_MODE with SESSION ResearcherID = ".$researcherid.".");
    }else{
        if($DEBUG_MODE){
            debug_log("This is DEBUG_MODE with hard-code ResearcherID = 1.");
            $researcherid = 1;
        } else {
            header("location: ../welcome.php");
        }
    }
?>