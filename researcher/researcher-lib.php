<?php
require_once("../mysql-lib.php");
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

/* Array Definition */

// sidebar
$userAdminPageArr = array('School', 'Class', 'Student');
$userAdminIconArr = array('mortar-board', 'users', 'child');
$contentAdminPageArr = array('Week', 'Quiz', 'Snap Fact', 'Verbose Fact', 'Recipe', 'MCQ', 'SAQ', 'Matching', 'Poster', 'Video', 'Image', 'Misc');
$quizTypeArr = array('Matching', 'Poster', 'Video', 'Image', 'Misc');
$contentAdminIconArr = array('calendar', 'book', 'lightbulb-o', 'folder-open', 'spoon', 'check', 'pencil', 'th-list', 'paint-brush', 'video-camera', 'picture-o', 'magic');
$gradingPageArr = array('SAQ Grading', 'Video Grading', 'Image Grading', 'Poster Grading');
$gradingIconArr = array('pencil', 'video-camera', 'picture-o', 'magic');

// php quiz type classification
$editableQuizTypeArr = array('MCQ', 'SAQ', 'Matching', 'Poster', 'Video', 'Image');
$miscQuizTypeArr = array('DrinkingTool', 'Calculator');
$saqLikeQuizTypeArr = array('SAQ', 'Video', 'Image');

/* Array Definition */


// get page name
$pageName = basename($_SERVER['SCRIPT_FILENAME'], '.php');

// deal with saq-like pages
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
    $pageNameForView = str_replace('-', ' ', str_replace('editor', '', $pageName));
    $pageNameForView = str_replace('mcq', 'Multiple Choice Quiz', $pageNameForView);

    if (strpos($pageName, 'saq') !== false) {
        $pageNameForView = str_replace('saq', 'Short Answer Question Quiz', $pageNameForView);
        return ucwords($pageNameForView);
    }

    // video, image, video-editor, image-editor
    if (defined('SAQ_LIKE_QUIZ_TYPE')) {
        $pageNameForView = ucfirst(SAQ_LIKE_QUIZ_TYPE) . ' Quiz';
    } // video-grading, image-grading, video-grader, image-grader
    else if (defined('SAQ_LIKE_SUBMISSION_TYPE')) {
        $pageNameForView = ucfirst(SAQ_LIKE_SUBMISSION_TYPE) . ' Grading';
    } // matching, poster, misc
    else

        if (in_array(ucwords(str_replace('-', ' ', $pageNameForView)), $GLOBALS['quizTypeArr'])) {
            $pageNameForView = ucwords(str_replace('-', ' ', $pageNameForView)) . " Quiz";
        }

    // school, class, student, snap-fact, verbose-fact, recipe
    // recipe-ingredient/nutrition/step-editor
    return ucwords($pageNameForView);
}

/* helper function */

?>