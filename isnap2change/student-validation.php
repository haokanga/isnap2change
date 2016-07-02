<?php
require_once("../debug.php");
if (!isset($_SESSION)) {
    session_start();
}
if (isset($_SESSION['studentID'])) {
    $studentID = $_SESSION['studentID'];
    debug_log("This is DEBUG_MODE with SESSION studentID = " . $studentID . ".");
} else {
    if ($DEBUG_MODE) {
        debug_log("This is DEBUG_MODE with hard-code studentID = 1.");
        $studentID = 1;
    } else {
        debug_log("You have not logged in.");
        header("location:login.php");
    }
}
?>