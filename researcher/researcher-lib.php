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
$gradingPageArr = array('SAQ-Grading', 'Poster-Grading');
$gradingIconArr = array('check', 'exclamation-triangle');


// get page name
$pageName = basename($_SERVER['SCRIPT_FILENAME'], '.php');
$pageNameForView = getPageNameForView($pageName);

/* helper function */
function getPageNameForView($pageName)
{
    switch ($pageName) {
        case 'mcq':
            $pageNameForView = 'Multiple Choice Quiz';
            break;
        case 'saq':
        case 'saq-editor':
            $pageNameForView = 'Short Answer Quiz';
            break;
        default:
            // video, image, video-editor, image-editor
            if (defined('SAQ_LIKE_QUIZ_TYPE')) {
                $pageNameForView = ucfirst(SAQ_LIKE_QUIZ_TYPE) . ' Quiz';
            } // other quiz overview, fact overview
            else if (in_array(ucwords(str_replace('-', ' ', $pageName)), $GLOBALS['quizTypeArr'])) {
                $pageNameForView = ucwords(str_replace('-', ' ', $pageName)) . " Quiz";
            } // user admin
            else
                $pageNameForView = ucwords(str_replace('-', ' ', $pageName));
    }

    return $pageNameForView;
}

/* helper function */

?>