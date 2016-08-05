<?php
    //check login status
    require_once('student-validation.php');

    require_once("../mysql-lib.php");
    require_once("../debug.php");

    $pageName = "video";

    //check whether a request is GET or POST
    if($_SERVER["REQUEST_METHOD"] == "GET"){
        if(isset($_GET["quiz_id"])){
            $quizID = $_GET["quiz_id"];
        } else{

        }
    } else {

    }

    $conn = null;

    try {
        $conn = db_connect();

        $week = getWeekByQuiz($conn, $quizID);

        //check whether the week is locked or not
        if ($week > getStudentWeek($conn, $studentID)) {
            echo '<script>alert("This is a locked quiz!")</script>';
            echo '<script>window.location="game-home.php"</script>';
        }

        //check quiz status
        $status = getQuizStatus($conn, $quizID, $studentID);

        //check quiz extra attr
        if (getQuizExtraAttr($conn, $quizID)) {
            $backPage = "extra-activities.php?week=".$week;
        } else {
            $backPage = "weekly-task.php?quiz_id=".$quizID;
        }

        //get media
        $mediaContent = getLearningMaterial($conn, $quizID);

        //get questions
        $mediaQuestions = getSAQQuestions($conn, $quizID);

        //if answered
        if ($status != "UNANSWERED") {
            $mediaRecords = getSAQRecords($conn, $quizID, $studentID);
        }

        //if graded
        if ($status == "GRADED") {
            updateSAQViewedStatus($conn, $quizID, $studentID);
        }

    } catch(Exception $e) {
        if($conn != null) {
            db_close($conn);
        }

        debug_err($pageName, $e);
        //to do: handle sql error
        //...
        exit;
    }

    db_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0, width=device-width, user-scalable=no">
    <title>Video</title>
    <link rel="stylesheet" href="./css/common.css">
    <link href='https://fonts.googleapis.com/css?family=Maitree|Lato:400,900' rel='stylesheet' type='text/css'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="./js/snap.js"></script>
    <style>
        .video-container {
            max-width: 1000px;
            margin: 0 auto 20px;
        }
        .video-header {
            margin: 20px auto;
            text-align: center;
        }
        .video-prompt {
            width: 500px;
            margin: 0 auto;
        }
        .video-content {
            width: 100%;
            background-color: #ddd;
        }
        .video-content video,
        .video-content img {
            width: 100%;
            height: auto;
        }
        .video-content iframe {
            width: 800px;
            height: 800px;
        }
        .question-container {
            max-width: 800px;
            margin: 0 auto;
        }
        .task-operation {
            right: 350px;
        }
    </style>
</head>
<body>

<div class="page-wrapper">
    <div class="header-wrapper">
        <div class="header">
            <a class="home-link">SNAP</a>
            <div class="settings">
                <div class="setting-icon dropdown">
                    <ul class="dropdown-menu">
                        <li class="dropdown-item"><a href="#">Logout</a></li>
                    </ul>
                </div>
                <a href="#" class="setting-text">NoButSrsly</a>
            </div>
        </div>
    </div>


    <div class="content-wrapper">

        <div class="video-container">
            <div class="video-header">
                <h2 class="h2 video-title">Video</h2>
                <div class="p1 video-prompt">
                    Watch this video from the Truth and then answer the questions below.
                </div>
            </div>
            <div class="video-content">
                <?php echo htmlspecialchars_decode($mediaContent->Content) ?>
            </div>
        </div>

        <div class="question-container">
            <form class="question-form ">
<?php
            for($i = 0; $i < count($mediaQuestions); $i++) { ?>
                <label class="question-item">
                    <span class="question-title h5"><?php echo 'Q.'.($i+1).' '.$mediaQuestions[$i]->Question ?></span>
                        <span class="question-content">
<?php               switch ($status) {
                        case "UNANSWERED": ?>
                            <textarea  cols="30" rows="3" class="question-area p2" name="<?php echo $mediaQuestions[$i]->SAQID ?>"></textarea>
<?php                       break;
                        case "UNSUBMITTED": ?>
                            <textarea  cols="30" rows="3" class="question-area p2" name="<?php echo $mediaQuestions[$i]->SAQID ?>"><?php echo $mediaRecords[$i]->Answer ?></textarea>
<?php                       break;
                        case "UNGRADED": ?>
                            <textarea  cols="30" rows="3" class="question-area p2" name="<?php echo $mediaQuestions[$i]->SAQID ?>" disabled="disabled"><?php echo $mediaRecords[$i]->Answer ?></textarea>
<?php                       break;
                        case "GRADED": ?>
                            <div class="feedback-title">Your Answer</div>
                            <div class="feedback-content"><?php echo $mediaRecords[$i]->Answer ?></div>
                            <div class="feedback-title feedback-title-hilight">Teacher's Feedback</div>
                            <div class="feedback-content"><?php echo $mediaRecords[$i]->Feedback ?></div>
<?php                       break;
                    } ?>
                        </span>
                </label>
<?php       } ?>

<?php
            if($status=="UNANSWERED" || $status=="UNSUBMITTED") { ?>
                <button type="submit" class="question-submit">
                    <span class="question-submit-icon"></span>
                    SUBMIT
                </button>
<?php       } ?>

<?php
            if($status=="UNGRADED" || $status=="GRADED") { ?>
                <button type="submit" class="question-submit" disabled="disabled">
                    <span class="question-submit-icon"></span>
                    SUBMIT
                </button>
<?php       } ?>
            </form>
        </div>


    </div>

    <ul class="task-operation">
        <li class="save-task">
            <a href="javascript:;" title="Save"></a>
        </li>
        <li class="cancel-task">
            <a href="<?php echo $backPage?>" title="Cancel Task"></a>
        </li>
    </ul>

    <div class="footer-wrapper">
        <div class="footer">
            <div class="footer-content">
                <a href="#" class="footer-logo"></a>
                <ul class="footer-nav">
                    <li class="footer-nav-item"><a href="#">Any Legal Stuff</a></li>
                    <li class="footer-nav-item"><a href="#">Acknowledgements</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>


<script>
    var form = new snap.Form({
        form: '.question-form',
        onSubmit: function (data) {
            $.ajax({
                url: "text-question-feedback.php",
                data: {
                    student_id: <?php echo $studentID?>,
                    quiz_id: <?php echo $quizID?>,
                    answer_arr: JSON.stringify(data),
                    status: "UNGRADED"
                },
                type: "POST",
                dataType : "json"
            })

                .done(function(feedback) {
                    parseFeedback(feedback);
                })

                .fail(function( xhr, status, errorThrown ) {
                    alert( "Sorry, there was a problem!" );
                    console.log( "Error: " + errorThrown );
                    console.log( "Status: " + status );
                    console.dir( xhr );
                });
        }
    });

    $(document).on('click', '.save-task', function (e) {
        $.ajax({
            url: "text-question-feedback.php",
            data: {
                student_id: <?php echo $studentID?>,
                quiz_id: <?php echo $quizID?>,
                answer_arr: JSON.stringify(form.getData()),
                status: "UNSUBMITTED"
            },
            type: "POST",
            dataType : "json"
        })

            .done(function(feedback) {
                parseFeedback(feedback);
            })

            .fail(function( xhr, status, errorThrown ) {
                alert( "Sorry, there was a problem!" );
                console.log( "Error: " + errorThrown );
                console.log( "Status: " + status );
                console.dir( xhr );
            });
    });

    function parseFeedback(feedback) {
        if(feedback.message != "success"){
            alert(feedback.message + ". Please try again!");
            return;
        }

        if(feedback.status == "UNSUBMITTED"){
            snap.alert({
                content: 'You have saved your work. Please remember to submit your work.',
                onClose: function () { }
            });
        }

        if(feedback.status == "UNGRADED"){
            snap.alert({
                content: 'You have submitted your work. And you cannot edit your answer anymore.',
                onClose: function () { }
            });
            $('.question-area').attr('disabled','disabled');
            $('.question-submit').attr("disabled","disabled");
        }
    }

</script>
</body>
</html>

