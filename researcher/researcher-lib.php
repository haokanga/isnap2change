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

$userAdminPageArr = array('School', 'Class', 'Student');
$userAdminIconArr = array('mortar-board', 'users', 'child');
$contentAdminPageArr = array('Week', 'Quiz', 'Snap Fact', 'Verbose Fact', 'MCQ', 'SAQ', 'Matching', 'Poster', 'Video', 'Image', 'Misc');
$quizTypeArr = array('Matching', 'Poster', 'Video', 'Image', 'Misc');
$contentAdminIconArr = array('calendar', 'book', 'lightbulb-o', 'folder-open', 'check', 'pencil', 'th-list', 'exclamation-triangle', 'video-camera', 'picture-o', 'exclamation-triangle');
$gradingPageArr = array('SAQ Grading', 'Video Grading', 'Image Grading', 'Poster Grading');
$gradingIconArr = array('check', 'check', 'check', 'exclamation-triangle');


// get page name
$pageName = basename($_SERVER['SCRIPT_FILENAME'], '.php');

if (in_array($pageName, array('saq', 'saq-editor', 'video', 'video-editor', 'image', 'image-editor'
)))
    define('SAQ_LIKE_QUIZ_TYPE', explode('-', $pageName, 2)[0]);
else if (in_array($pageName, array('saq-grading', 'saq-grader', 'video-grading', 'video-grader', 'image-grading', 'image-grader'
)))
    define('SAQ_LIKE_SUBMISSION_TYPE', explode('-', $pageName, 2)[0]);


$pageNameForView = getPageNameForView($pageName);

/* helper function */
function getPageNameForView($pageName)
{
    if ($pageName == 'mcq')
        $pageNameForView = 'Multiple Choice Quiz';
    else if (strpos($pageName, 'saq') !== false)
        $pageNameForView = 'SAQ';
    // video, image, video-editor, image-editor
    else if (defined('SAQ_LIKE_QUIZ_TYPE')) {
        $pageNameForView = ucfirst(SAQ_LIKE_QUIZ_TYPE) . ' Quiz';
    } // video-grading, image-grading, video-grader, image-grader
    else if (defined('SAQ_LIKE_SUBMISSION_TYPE')) {
        $pageNameForView = ucfirst(SAQ_LIKE_SUBMISSION_TYPE) . ' Grading';
    } // matching, poster, misc
    else if (in_array(ucwords(str_replace('-', ' ', $pageName)), $GLOBALS['quizTypeArr'])) {
        $pageNameForView = ucwords(str_replace('-', ' ', $pageName)) . " Quiz";
    } // school, class, student, snap-fact, verbose-fact
    else
        $pageNameForView = ucwords(str_replace('-', ' ', $pageName));


    return $pageNameForView;
}

/* helper function */

?>