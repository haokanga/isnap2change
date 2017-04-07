<?php
	require_once('student-validation.php');
    require_once("../mysql-lib.php");
    require_once("../debug.php");
    $pageName = "multiple-choice-question";

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        if(isset($_GET["quiz_id"])){
            $quizID = $_GET["quiz_id"];
        } else {

        }
    } else {

    }

    $conn = null;

    try{
        $conn = db_connect();

        $week = getWeekByQuiz($conn, $quizID);

        //check whether the week is locked or not
        if ($week > getStudentWeek($conn, $studentID)) {
            echo '<script>alert("This is a locked quiz!")</script>';
            echo '<script>window.location="game-home.php"</script>';
        }

        //check quiz status
        $status = getQuizStatus($conn, $quizID, $studentID);

        //get learning material
        $materialRes = getLearningMaterial($conn, $quizID);

        //get mcq questions
        $mcqQuestions = getMCQQuestionsByQuizID($conn, $quizID);


        $mcqOptions = array();
        $feedback = array();

        for ($i = 0; $i < count($mcqQuestions); $i++) {
            //get mcq options
            $options = getOptions($conn, $mcqQuestions[$i]->MCQID);

            array_push($mcqOptions, $options);

            //if graded
            if ($status == "GRADED") {
                $singleFeedback = array();

                foreach($options as $row) {
                    $feedbackDetails = array();
                    $feedbackDetails["correctAns"] = $mcqQuestions[$i]->CorrectChoice;
                    $feedbackDetails["Explanation"] = $row->Explanation;
                    array_push($singleFeedback, $feedbackDetails);
                }

                array_push($feedback, $singleFeedback);
            }
        }
    } catch(Exception $e) {
        if ($conn != null) {
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
    <title>Multiple Chocie Question | SNAP</title>
    <link rel="stylesheet" href="./css/common.css">
    <link href='https://fonts.googleapis.com/css?family=Maitree|Lato:400,900' rel='stylesheet' type='text/css'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <style>
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
                        <li class="dropdown-item"><a href="setting.php">Setting</a></li>
                        <li class="dropdown-item"><a href="#">Logout</a></li>
                    </ul>
                </div>
                <a class="setting-text"><?php echo $studentUsername?></a>
            </div>
        </div>
    </div>


    <div class="content-wrapper">

        <ul class="quiz-nav-list">
<?php
        for ($i = 0; $i < count($mcqQuestions); $i++) { ?>
            <li class="quiz-nav-item">
                <span class="quiz-nav-label"></span>
            </li>
<?php   } ?>
        </ul>

        <div class="quiz-list">
<?php   for ($i = 0; $i < count($mcqQuestions); $i++) {
            if ($i == 0) {
                $active = "quiz-item-active";
            } ?>
            <div class="quiz-item <?php echo $active ?>" data-id="<?php echo $mcqQuestions[$i]->MCQID ?>">
                <div class="h3 quiz-header">
                    <div class="quiz-label">
                        <span class="image-icon-speech"></span>
                    </div>
                    <div class="quiz-title"><?php echo 'Q'.($i+1).'. '.$mcqQuestions[$i]->Question ?></div>
                </div>
                <ul class="quiz-answer-list">
<?php       for ($j = 0; $j < count($mcqOptions[$i]); $j++) { ?>
                    <li class="quiz-answer-item" data-answer="<?php echo $mcqOptions[$i][$j]->OptionID ?>">
                        <div class="quiz-label">
                            <span class="image-icon-speech"></span>
                        </div>
                        <div class="quiz-answer-content">
                            <?php echo $mcqOptions[$i][$j]->Content ?>
<?php
                            if ($status == "GRADED") { ?>
                                <div class="quiz-feedback">
                                    <div class="quiz-feedback-title">
<?php
                                if ($feedback[$i][$j]["correctAns"] == $mcqOptions[$i][$j]->OptionID) { ?>
                                    This is correct:
<?php                           } else { ?>
                                    This is incorrect:
<?php                           }?>
                                    </div>
                                    <div class="quiz-feedback-content"><?php echo $feedback[$i][$j]["Explanation"] ?></div>
                                </div>
<?php                       } ?>
                        </div>
                    </li>
<?php       } ?>
                </ul>
                <div class="quiz-nav-container">
                    <span class="quiz-nav-prev quiz-nav"></span>
                    <span class="quiz-nav-next quiz-nav"></span>
                </div>

                <form class="question-form">

<?php
                if($status == "UNANSWERED"){ ?>
                    <button type="submit" class="question-submit">
                        <span class="question-submit-icon"></span>
                        SUBMIT
                    </button>
<?php           }
                if($status == "GRADED"){ ?>
                    <button type="submit" class="question-submit" disabled="disabled">
                        <span class="question-submit-icon"></span>
                        SUBMIT
                    </button>
<?php           } ?>

                </form>
            </div>
<?php   } ?>
        </div>

    </div>

    <ul class="task-operation">
        <li class="cancel-task">
            <a href="weekly-task.php?week=<?php echo $week?>" title="Cancel Task"></a>
        </li>
    </ul>

    <div class="attachment">
        <ul class="attachment-nav">
            <li class="attachment-nav-item">SNAP <br>FACTS</li>
            <li class="attachment-nav-item">READING <br> MATERIAL</li>
        </ul>
    </div>

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

<script src="./js/snap.js"></script>
<script>
    snap.initAttachmentCtrl();

    var quizNav = new snap.QuizNav();

    var QuizCtrl = {
        cls: {
            answerSelected: 'quiz-answer-item-selected'
        },
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
            });
            $doc.on('click', '.quiz-answer-item', function (e) {
                var $target = $(e.currentTarget)
                var isOriginalSelected = $target.hasClass(that.cls.answerSelected);
                var $quiz = $target.closest('.quiz-item');
                $quiz.find('.quiz-answer-item').removeClass(that.cls.answerSelected);
                if (!isOriginalSelected) {
                    $target.addClass(that.cls.answerSelected)
                }


                var index = that.$quizItems.index($quiz);

                if ($quiz.find('.quiz-answer-item-selected').length) {
                    quizNav.fillItem(index)
                } else {
                    quizNav.unfillItem(index)
                }
            })
        },
        getData: function () {
            var result = {};
            this.$quizItems.each(function () {
                var $quiz = $(this);
                var id = $quiz.data('id');
                var answer = $quiz.find('.quiz-answer-item-selected').data('answer');

                if (answer == undefined) {
                    result[id] = null;
                } else {
                    result[id] = $quiz.find('.quiz-answer-item-selected').data('answer');
                }
            });
            return result
        },
        setFeedback: function (data) {
            detail = data || [];
            var that = this;
            var $quizItems = this.$quizItems;
            var feedbackTpl =
                '         <div class="quiz-feedback">' +
                '             <div class="quiz-feedback-title">This is correct:</div>' +
                '             <div class="quiz-feedback-content">aaa</div>' +
                '         </div>'
                detail.forEach(function (quizState) {
                var $quizItem = $quizItems.filter('[data-id="' + quizState.MCQID +'"]');
                var $quizAnswers = $quizItem.find('.quiz-answer-item');
                var isCorrect = false;

                for (var key in quizState.explanation) {
                    var $answerItem = $quizAnswers.filter('[data-answer="' + key + '"]');
                    var answerId = $answerItem.data('answer');
                    var $feedback = $(feedbackTpl)

                    $feedback.find('.quiz-feedback-title')
                        .text(answerId == quizState.correctAns ? 'This is correct:' : 'This is incorrect')
                    $feedback.find('.quiz-feedback-content').text(quizState.explanation[key])
                    $answerItem.find('.quiz-answer-content')
                        .append($feedback)

                    if (answerId == quizState.correctAns) {
                        $answerItem.addClass('quiz-answer-item-correct')
                    }

                    if ($answerItem.hasClass('quiz-answer-item-selected')) {
                        if (answerId == quizState.correctAns) {
                            isCorrect = true
                        } else {
                            $answerItem.addClass('quiz-answer-item-incorrect')
                        }
                    }
                }

                quizNav.feedback($quizItems.index($quizItem), isCorrect)
            })
        }
    };

    QuizCtrl.init({
        onSubmit: function (data) {
            $.ajax({
                url: "multiple-choice-question-feedback.php",
                data: {
                    student_id: <?php echo $studentID?>,
                    quiz_id: <?php echo $quizID?>,
                    answer_arr: JSON.stringify(data)
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



    function parseFeedback(feedback) {
        if (feedback.message != "success") {
            alert(feedback.message + ". Please try again!");
            return;
        }

        if (feedback.result == "pass") {
            snap.alert({
                content: 'Congratulations! You have passed this quiz. The result is: ' + feedback.score + '/' + feedback.quesNum + '.'
            });

            QuizCtrl.setFeedback(feedback.detail);

        } else if (feedback.result == "fail") {
            snap.alert({
                content: 'Sorry! You have failed this quiz. The result is: ' + feedback.score + '/' + feedback.quesNum + '.'
            })
        }
    }


</script>
</body>
</html>
