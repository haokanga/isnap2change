<?php

//	require_once('student-validation.php');
    require_once("../mysql-lib.php");
    require_once("../debug.php");
    $pageName = "multiple-choice-question";

    /*
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        if(isset($_GET["quiz_id"])){
            $quizID = $_GET["quiz_id"];
        } else {

        }
    } else {

    }
    */

    $quizID = 8;
    $studentID = 21;

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

        //get matching questions
        $matchingQuestions = getMatchingBuckets($conn, $quizID);

        $matchingOptions = getMatchingOptions($conn, $quizID);

        //get matching options
        shuffle($matchingOptions);

        $btnDisabled = "";
        $correctOptions = array();

        if ($status == "GRADED") {
            $btnDisabled = "disabled";

            foreach ($matchingQuestions as $matchingQuestion) {
                array_push($correctOptions, getMatchingOptionsByMatchingID($conn, $matchingQuestion->MatchingID));
            }
        }

    } catch(Exception $e) {
        if($conn != null) {
            db_close($conn);
        }

       // debug_err($pageName, $e);
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
    <title>Matching | SNAP</title>
    <link rel="stylesheet" href="./css/common.css">
    <link href='https://fonts.googleapis.com/css?family=Maitree|Lato:400,900' rel='stylesheet' type='text/css'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <style>
        .matching-activity-container {
            max-width: 1000px;
            text-align: center;
            margin: 20px auto;
        }
        .matching-activity-intro {
            max-width: 500px;
            margin: 0 auto 20px;
            font-size: 18px;
        }
        .matching-activity-list {
            max-width: 1000px;
            margin: 10px auto 20px;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }
        .matching-activity-item {
            width: 33.3%;
            padding: 10px;
        }
        .matching-activity-name {
            color: #fcee2d;
            text-align: center;
            margin-bottom: 10px;
            margin-top: 20px;
        }
        .matching-activity-content {
            min-height: 40px;
            border-radius: 10px;
            padding: 10px 20px;
            background-color: rgb(160, 160, 159);
        }
        .matching-activity-answer-list {
            max-width: 1000px;
            margin: 50px auto;
        }
        .matching-activity-answer-item {
            border-radius: 10px;
            color: #fff;
            background-color: rgb(61, 61, 61);
            padding: 10px;
            text-align: center;
            margin-bottom: 20px;
            cursor: move;
        }
        .form-container {
            overflow: hidden;
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
            <div class="home-link">SNAP</div>
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

        <div class="matching-activity-container">
            <div class="matching-activity-header">
                <div class="h2 matching-activity-title">Matching Activity</div>
                <div class="matching-activity-intro"><?php echo $matchingQuestions[0]->Description ?></div>
            </div>

            <div class="matching-activity-list">
<?php
        for ($i = 0; $i < count($matchingQuestions); $i++) { ?>
                <div class="matching-activity-item" data-id="<?php echo $matchingQuestions[$i]->MatchingID ?>">
                    <div class="h3 matching-activity-name"><?php echo $matchingQuestions[$i]->Question ?></div>
                    <div class="matching-activity-content">
<?php
                if ($status == "GRADED") {
                    for ($j = 0; $j < count($correctOptions[$i]); $j++) { ?>
                        <div class="matching-activity-answer-item" draggable="false" data-id="<?php echo $correctOptions[$i][$j]->OptionID ?>"><?php echo $correctOptions[$i][$j]->Content ?></div>
<?php                }
                } ?>
                    </div>
                </div>
<?php    } ?>
            </div>

<?php     if ($status == "UNANSWERED") { ?>
            <div class="matching-activity-answer-list">
                <div class="mini-row">
<?php
                    for ($i = 0; $i < count($matchingOptions); $i++) { ?>
                        <div class="col-3">
                            <div class="matching-activity-answer-item" draggable="true" data-id="<?php echo $matchingOptions[$i]->OptionID ?>"><?php echo $matchingOptions[$i]->Content ?></div>
                        </div>
<?php               } ?>
                </div>
            </div>
<?php    } ?>



            <div class="form-container">
                <form class="question-form">
                        <button type="submit" class="question-submit" <?php echo $btnDisabled?>>
                            <span class="question-submit-icon"></span>
                            Submit
                        </button>
                </form>
            </div>

        </div>

    </div>

    <ul class="task-operation">
        <li class="cancel-task">
            <a href="#" title="Cancel Task"></a>
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

<script src="./js/snap.js"></script>
<script>

    var DragCtrl = {
        init: function () {
            this.cacheElements();
            this.addListeners()
        },
        cacheElements: function () {
            this.$contentItems = $('.matching-activity-content');
            this.$answerItems = $('.matching-activity-answer-item');
            this.$answerContainer = $('.matching-activity-answer-list')
        },
        addListeners: function () {
            var that = this;

            this.$answerItems.on('dragstart', function (e) {
                that.srcItem = e.currentTarget
            });
            var $body = $('body');
            $body.on('dragenter, dragover', function (e) {
                e.preventDefault()
            });
            $body.on('drop', function (e) {
                var $target = $(e.target);
                var $contentItem = $target.closest('.matching-activity-content');
                if (!that.srcItem) {
                    return
                }
                if ($contentItem.length) {
                    $contentItem.append(that.srcItem)
                } else if ($(that.srcItem).closest('.matching-activity-content').length){
                    var $wrapper = $('<div class="col-3"></div>');
                    $wrapper.append(that.srcItem);
                    that.$answerContainer.find('.mini-row').append($wrapper)
                }
                that.srcItem = null

            })
        }
    };
    DragCtrl.init();

    var FormCtrl = {
        init: function (opt) {
            this.opt = $.extend({
                onSubmit: $.noop
            }, opt);
            this.cacheElements();
            this.addListeners()
        },
        cacheElements: function () {
            this.$form = $('.question-form')
            this.$activityItems = $('.matching-activity-item')
        },
        addListeners: function () {
            var that = this
            this.$form.on('submit', function (e) {
                e.preventDefault()
                that.opt.onSubmit(that.getData())
            })
        },
        getData: function () {
            var data = {};
            this.$activityItems.each(function () {
                var $item = $(this);
                var answers = [];

                $item.find('.matching-activity-answer-item')
                    .each(function () {
                        var $answer = $(this);
                        answers.push($answer.data('id'))
                    });

                  data[$item.data('id')] = answers;
            });
            return data
        }
    };

    FormCtrl.init({
        onSubmit: function (data) {
            console.log(data)

            $.ajax({
                url: "matching-question-feedback.php",
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
                content: 'Congratulations! You have completed this task.',
                onClose: function () {
                    var matchingAnswerItem = $('.matching-activity-answer-item');
                    matchingAnswerItem.attr('draggable', false);
                    matchingAnswerItem.removeClass('border-incorrect');
                    $('.question-submit').attr("disabled","disabled");
                }
            });
        } else if (feedback.result == "fail") {
            snap.alert({
                content: 'Sorry! Please try again.',
                onClose: function () {
                    setFeedback(feedback.detail)
                }
            })
        }
    }

    function setFeedback(details, feedbackCls) {
        $('.matching-activity-answer-item').removeClass('border-incorrect');

        feedbackCls = feedbackCls || 'border-incorrect';
        var errorItemSelector = details.map(function (id) {
            return '.matching-activity-answer-item[data-id=' + id+ ']'
        }).join(',');
        $(errorItemSelector)
            .addClass(feedbackCls)
    }
</script>
</body>
</html>

