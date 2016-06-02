<?php    
    //set userid    
    if(isset($_SESSION['researcherid'])){
        $researcherid = $_SESSION['researcherid'];
        if($DEBUG_MODE){
            echo "<script language=\"javascript\">  console.log(\"This is DEBUG_MODE with SESSION ResearcherID = ".$researcherid.".\"); </script>";
        }
    }else{
        if($DEBUG_MODE){
            echo "<script language=\"javascript\">  console.log(\"This is DEBUG_MODE with hard-code ResearcherID = 1.\"); </script>";
            $researcherid = 1;
        }
    }
?>