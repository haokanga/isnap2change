<?php
    //check login status
    require_once('student-validation.php');

    require_once("../mysql-lib.php");
    require_once("../debug.php");

    $pageName = "short-answer-question";

    //check whether a request is GET or POST
    if($_SERVER["REQUEST_METHOD"] == "GET") {
        if(isset($_GET["quiz_id"])) {
            $quizID = $_GET["quiz_id"];
        } else {

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

        //get saq questions
        $saqQuestions = getSAQQuestions($conn, $quizID);

        //if answered
        if ($status != "UNANSWERED") {
            $saqRecords = getSAQRecords($conn, $quizID, $studentID);
        }

        //if graded
        if ($status == "GRADED") {
            updateSAQViewedStatus($conn, $quizID, $studentID);

            $totalPoints = getQuizPoints($conn, $quizID);

            $studentPoints = getStuQuizScore($conn, $quizID, $studentID);
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
    <title>Short Answer Question</title>
    <link rel="stylesheet" href="./css/common.css">
    <link href='https://fonts.googleapis.com/css?family=Maitree|Lato:400,900' rel='stylesheet' type='text/css'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="./js/vendor/jquery.js"></script>
    <script src="./js/snap.js"></script>
    <style>
        .quiz-short .quiz-title {
            margin-left: 0;
        }
        .quiz-answer-title {
            text-align: center;
        }
        .quiz-answer-field {
            display: block;
            width: 100%;
            min-height: 200px;
            border-radius: 10px;
            padding: 10px 20px;
            color: #333;
            font-size: 18px;
            background-color: rgb(246, 247, 247);
            margin: 10px 0 30px;
        }
        .quiz-feedback-title {
            color: #fcee2d;
            text-align: center;
        }
        .quiz-feedback-content {
            display: block;
            width: 100%;
            min-height: 200px;
            border-radius: 10px;
            padding: 10px 20px;
            color: #333;
            font-size: 18px;
            background-color: rgb(246, 247, 247);
            margin: 10px 0 30px;
        }
        .quiz-feedback-summary {
            text-align: center;
            color: #000;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .quiz-total {
            margin: 30px 0 30px;
            text-align: center;
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

        <ul class="quiz-nav-list">
<?php
            for ($i = 0; $i < count($saqQuestions); $i++) { ?>
                <li class="quiz-nav-item">
                    <span class="quiz-nav-label"></span>
                </li>
<?php       } ?>
        </ul>

        <div class="quiz-list quiz-short">
<?php   for ($i = 0; $i < count($saqQuestions); $i++) {
                if ($i == 0) {
                    $active = "quiz-item-active";
                } ?>
            <div class="quiz-item <?php echo $active ?>" data-id="<?php echo $saqQuestions[$i]->SAQID ?>">
                <div class="h3 quiz-header">
                    <div class="quiz-title"><?php echo 'Q'.($i+1).'. '.$saqQuestions[$i]->Question ?></div>
                </div>

<?php   switch ($status) {
            case "UNANSWERED": ?>
                <div class="quiz-answer-container">
                    <h2 class="quiz-answer-title h4">Your Answer</h2>
                    <textarea cols="30" rows="5" class="quiz-answer-field"></textarea>
                </div>
<?php           break;
            case "UNSUBMITTED": ?>
                <div class="quiz-answer-container">
                    <h2 class="quiz-answer-title h4">Your Answer</h2>
                    <textarea cols="30" rows="5" class="quiz-answer-field"><?php echo $saqRecords[$i]->Answer ?></textarea>
                </div>
<?php           break;
            case "UNGRADED": ?>
                <div class="quiz-answer-container">
                    <h2 class="quiz-answer-title h4">Your Answer</h2>
                    <textarea cols="30" rows="5" class="quiz-answer-field" disabled="disabled"><?php echo $saqRecords[$i]->Answer ?></textarea>
                </div>
<?php           break;
            case "GRADED": ?>
                <div class="quiz-answer-container">
                    <h2 class="quiz-answer-title h4">Your Answer</h2>
                    <textarea cols="30" rows="5" class="quiz-answer-field" disabled="disabled"><?php echo $saqRecords[$i]->Answer ?></textarea>
                </div>

                <div class="quiz-feedback">
                    <h2 class="h4 quiz-feedback-title">Teacher's Feedback</h2>
                    <div class="quiz-feedback-content">
                        <h3 class="h3 quiz-feedback-summary">You obtained <?php echo $saqRecords[$i]->Grading ?> points for ths answer</h3>
                        <div class="quiz-feedback-detail"><?php echo $saqRecords[$i]->Feedback ?></div>
                    </div>
                </div>

                <div class="quiz-total">
                    <h3 class="quiz-total-title h3">Total Task Score:</h3>
                    <div class="h3 quiz-total-score"><?php echo $studentPoints ?>/<?php echo $totalPoints ?></div>
                </div>
<?php           break;
            } ?>

                <div class="quiz-nav-container">
                    <span class="quiz-nav-prev quiz-nav"></span>
                    <span class="quiz-nav-next quiz-nav"></span>
                </div>

                <form class="question-form">
                    <button type="submit" class="question-submit">
                        <span class="question-submit-icon"></span>
                        Submit
                    </button>
                </form>
            </div>
<?php    } ?>
        </div>

    </div>

    <ul class="task-operation">
        <li class="save-task">
            <a href="javascript:;" title="Save"></a>
        </li>
        <li class="cancel-task">
            <a href="weekly-task.php?week=<?php echo $week?>" title="Cancel Task"></a>
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
    var quizNav = new snap.QuizNav();


    var QuizCtrl = {
        init: function (opt) {
            this.opt = $.extend({
                onSubmit: $.noop
            }, opt);
            this.cacheElements();
            this.addListeners()
        },
        cacheElements: function () {
            this.$form = $('.question-form');
            this.$quizItems = $('.quiz-item')
        },
        addListeners: function () {
            var that = this;
            var $doc = $(document);
            this.$form.on('submit', function (e) {
                e.preventDefault();
                that.opt.onSubmit(that.getData())
            })
        },
        getData: function () {
            var result = {};
            this.$quizItems.each(function () {
                var $quiz = $(this);
                var id = $quiz.data('id');
                var answer = $quiz.find('.quiz-answer-field').val();

                if (answer == undefined) {
                    result[id] = null;
                } else {
                    result[id] = $quiz.find('.quiz-answer-field').val();
                }
            });
            return result;
        }
    };

    QuizCtrl.init({
        onSubmit: function (data) {
            console.log(data);

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
                content: 'You have saved your work. Please remember to submit your work.'
            });
        }

        if(feedback.status == "UNGRADED"){
            snap.alert({
                content: 'You have submitted your work. And you cannot edit your answer anymore.',
                onClose: function () { }
            });
            $('.question-answer-field').attr('disabled','disabled');
            $('.question-submit').attr("disabled","disabled");
        }
    }
</script>
</body>
</html>

