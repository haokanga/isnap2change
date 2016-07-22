<?php
require_once("no-cache.php");

session_start();
require_once("../mysql-lib.php");
require_once("../debug.php");

try {
    db_close(testAuthenticationFailure());
} catch (Exception $e) {
    $pageName = basename($_SERVER['SCRIPT_FILENAME'], '.php');
    debug_err($e);
}

function testAuthenticationFailure()
{
    $conn = null;

    $serverName = "localhost";
    $username = "root";
    $wrongPassword = "wrongPassword";

    $conn = new PDO("mysql:host=$serverName; dbname=isnap2changedb; charset=utf8", $username, $wrongPassword);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $conn;
}

echo '###########################<br>';
echo 'UNIT TEST<br>';
echo 'testAuthenticationFailure()<br>';
echo '###########################<br>';


?>