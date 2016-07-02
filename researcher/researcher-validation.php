<?php
require_once("../debug.php");

/* Session */
if (isset($_SESSION['researcherID'])) {
    $researcherID = $_SESSION['researcherID'];
    debug_log("This is DEBUG_MODE with SESSION ResearcherID = " . $researcherID . ".");
} else {
    if ($DEBUG_MODE) {
        debug_log("This is DEBUG_MODE with hard-code ResearcherID = 1.");
        $researcherID = 1;
    } else {
        header("location: ../welcome.php");
    }
}
/* Session */

?>