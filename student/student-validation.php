<?php
    require_once("../debug.php");

    session_start();

    if (isset($_SESSION['studentID']) && isset($_SESSION['studentUsername'])) {
        $studentID = $_SESSION['studentID'];
        $studentUsername = $_SESSION['studentUsername'];
    } else {
        debug_log("You have not logged in.");
        header("location:welcome.php");
    }
?>