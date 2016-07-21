<?php
/**
 * Bitnami Server PHP version: 5.6.21
 */
//if true, echo debug output in dev mode, else production mode
$DEBUG_MODE = true;

function debug_log($message)
{
    if ($GLOBALS['DEBUG_MODE']) {
        echo "<script language=\"javascript\">  console.log(\"" . $message . "\"); </script>";
    }
}

function debug_alert($message)
{
    echo "<script language=\"javascript\">  alert(\"" . $message . "\"); </script>";
}

function debug_err($pageName = null, Exception $e)
{
    if ($e instanceof PDOException) {
        // duplicate entry
        if ($e->errorInfo[1] == 1062) {
            debug_alert($e->getMessage());
        } // unclassified PDO exception
        else {
            handle_exception($e);
        }
    } // custom exception and other unclassified exception
    else {
        handle_exception($e);
    }
}

function handle_exception(Exception $e)
{
    if ($GLOBALS['DEBUG_MODE']) {
        echo $e->getMessage();
        echo "The exception was created on line: " . $e->getLine();
        echo "View Log Database to check this bug.";
    }
    logger_write($e);
    redirectToBugReportPage();
}

function redirectToBugReportPage()
{
    $url = getBugReportURL();
    header("Location: $url");
}

function logger_write(Exception $e)
{
    $bugID = null;

    $conn = db_connect('log');
    try {
        $pageName = $_SERVER['SCRIPT_FILENAME'];
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestParameters = serialize($_REQUEST);
        $sessionDump = session_encode();
        $exceptionMessage = $e->getMessage();
        $exceptionTrace = $e->getTraceAsString();
        $bugID = createLog($conn, array($exceptionMessage, $exceptionTrace, $pageName, $requestMethod, $requestParameters, $sessionDump));
    } catch (Exception $loggerException) {
        //don't trigger debug_err here to prevent infinite loop
    }
    db_close($conn);

    return $bugID;
}


/* helper function*/

function getBugReportURL()
{
    return getURL("/bug-report.php");
}

function getURL($pageName){
    $debug_page_dir = __DIR__;
    $host = $_SERVER['HTTP_HOST'];
    $relative_dir = explode("htdocs", $debug_page_dir, 2)[1];
    $url = "http://" . $host . $relative_dir . $pageName;
    return $url;
}
/* helper function*/

?>
