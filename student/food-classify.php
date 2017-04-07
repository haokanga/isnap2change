<?php
/*
    require_once('student-validation.php');
    require_once("../mysql-lib.php");
    require_once("../debug.php");

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        if(isset($_GET["quiz_id"])){
            $quizID = $_GET["quiz_id"];
        } else {

        }
    } else {

    }
*/

    require_once("../mysql-lib.php");
    require_once("../debug.php");

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

        //check quiz extra attr
        if (getQuizExtraAttr($conn, $quizID) == 1) {
            $backPage = "extra-activities.php?week=".$week;
        } else {
            $backPage = "weekly-task.php?week=".$week;
        }

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
    <title>Document</title>
    <link rel="stylesheet" href="./css/common.css">
    <link href='https://fonts.googleapis.com/css?family=Maitree|Lato:400,900' rel='stylesheet' type='text/css'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="./js/vendor/jquery.js"></script>

    <script src="./js/snap.js"></script>
    <style>
        .classify-activity-container {
            max-width: 1000px;
            text-align: center;
            margin: 20px auto;
        }
        .classify-activity-intro {
            max-width: 500px;
            margin: 0 auto 20px;
            font-size: 18px;
        }
        .classify-activity-list {
            max-width: 1000px;
            margin: 10px auto 20px;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }
        .classify-activity-item {
            width: 33.3%;
            padding: 10px;
        }
        .classify-activity-name {
            color: #fcee2d;
            text-align: center;
            margin-bottom: 10px;
            margin-top: 20px;
        }
        .classify-activity-content {
            min-height: 40px;
            border-radius: 10px;
            padding: 10px 20px;
            background-color: rgb(160, 160, 159);
            overflow: hidden;
        }
        .classify-activity-content .classify-activity-answer-item {
            float: left;
            width: calc(50% - 10px);
            margin: 0 5px;
        }
        .classify-activity-answer-list {
            max-width: 1000px;
            margin: 20px auto;
        }
        .classify-activity-answer-item {
            border-radius: 10px;
            color: #fff;
            background-color: rgb(61, 61, 61);
            padding: 10px;
            text-align: center;
            margin-bottom: 20px;
            cursor: move;
            border: 5px solid transparent;
        }
        .classify-activity-answer-icon {
            width: 100px;
            height: 100px;
            display: block;
            margin: 0 auto;
            background-size: 100% 100%;
        }
        .food-baked-potato-icon {
            background-image: url("./img/matching-food/food_matching_baked_potato.png");
        }
        .food-banana-icon {
            background-image: url("./img/matching-food/food_matching_banana.png");
        }
        .food-beef-icon {
            background-image: url("./img/matching-food/food_matching_beef.png");
        }
        .food-broccoli-icon {
            background-image: url("./img/matching-food/food_matching_broccoli.png");
        }
        .food-kiwi-fruit-icon {
            background-image: url("./img/matching-food/food_matching_kiwi_fruit.png");
        }
        .food-oranges-icon {
            background-image: url("./img/matching-food/food_matching_oranges.png");
        }
        .food-oysters-icon {
            background-image: url("./img/matching-food/food_matching_oysters.png");
        }
        .food-red-peppers-icon {
            background-image: url("./img/matching-food/food_matching_red_peppers.png");
        }
        .food-salmon-icon {
            background-image: url("./img/matching-food/food_matching_salmon.png");
        }
        .food-seeds-icon {
            background-image: url("./img/matching-food/food_matching_seeds.png");
        }
        .food-spinach-icon {
            background-image: url("./img/matching-food/food_matching_spinach.png");
        }
        .food-tofu-icon {
            background-image: url("./img/matching-food/food_matching_tofu.png");
        }
        .form-container {
            overflow: hidden;
        }
    </style>
</head>
<body>

<div class="page-wrapper">
    <div class="header-wrapper">
        <div class="header">
            <a href="#" class="header-back-link"></a>
            <a class="home-link" href="#">SNAP</a>
            <ul class="nav-list">
                <li class="nav-item"><a class="nav-link" href="http://taobao.com">GAME HOME</a></li>
                <li class="nav-item"><a class="nav-link" href="http://taobao.com">Snap Facts</a></li>
                <li class="nav-item"><a class="nav-link" href="http://taobao.com">Resources</a></li>
            </ul>
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
        <div class="classify-activity-container">
            <div class="classify-activity-header">
                <div class="h2 classify-activity-title">Food Groups</div>
                <div class="classify-activity-intro">Classify these foods by playing them into their correct categories. Click and drag the food item and drop it into its correct category.</div>
            </div>

            <div class="classify-activity-list">
                <div class="classify-activity-item" data-id="1">
                    <div class="h3 classify-activity-name">Potassium</div>
                    <div class="classify-activity-content"></div>
                </div>
                <div class="classify-activity-item" data-id="2">
                    <div class="h3 classify-activity-name">Iron</div>
                    <div class="classify-activity-content"></div>
                </div>
                <div class="classify-activity-item" data-id="3">
                    <div class="h3 classify-activity-name">Vitamin A</div>
                    <div class="classify-activity-content"></div>
                </div>
                <div class="classify-activity-item" data-id="4">
                    <div class="h3 classify-activity-name">Magnesium</div>
                    <div class="classify-activity-content"></div>
                </div>
                <div class="classify-activity-item" data-id="5">
                    <div class="h3 classify-activity-name">Calcium</div>
                    <div class="classify-activity-content"></div>
                </div>
                <div class="classify-activity-item" data-id="6">
                    <div class="h3 classify-activity-name">Vitamin C</div>
                    <div class="classify-activity-content"></div>
                </div>
            </div>

            <div class="classify-activity-answer-list">
                <div class="mini-row">
                    <div class="col-2">
                        <div class="classify-activity-answer-item" draggable="true" data-id="1">
                            <div class="classify-activity-answer-icon food-tofu-icon"></div>
                            Tofu
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="classify-activity-answer-item" draggable="true" data-id="2">
                            <div class="classify-activity-answer-icon food-baked-potato-icon"></div>
                            Baked Potato
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="classify-activity-answer-item" draggable="true" data-id="3">
                            <div class="classify-activity-answer-icon food-banana-icon"></div>
                            Bananas
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="classify-activity-answer-item" draggable="true" data-id="4">
                            <div class="classify-activity-answer-icon food-beef-icon"></div>
                            Beef
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="classify-activity-answer-item" draggable="true" data-id="5">
                            <div class="classify-activity-answer-icon food-broccoli-icon"></div>
                            Broccoli
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="classify-activity-answer-item" draggable="true" data-id="6">
                            <div class="classify-activity-answer-icon food-kiwi-fruit-icon"></div>
                            Kiwi Fruit
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="classify-activity-answer-item" draggable="true" data-id="7">
                            <div class="classify-activity-answer-icon food-oranges-icon"></div>
                            Oranges
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="classify-activity-answer-item" draggable="true" data-id="8">
                            <div class="classify-activity-answer-icon food-oysters-icon"></div>
                            Oysters
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="classify-activity-answer-item" draggable="true" data-id="9">
                            <div class="classify-activity-answer-icon food-red-peppers-icon"></div>
                            Red Peppers
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="classify-activity-answer-item" draggable="true" data-id="10">
                            <div class="classify-activity-answer-icon food-salmon-icon"></div>
                            Salmon
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="classify-activity-answer-item" draggable="true" data-id="11">
                            <div class="classify-activity-answer-icon food-seeds-icon"></div>
                            Seeds
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="classify-activity-answer-item" draggable="true" data-id="12">
                            <div class="classify-activity-answer-icon food-spinach-icon"></div>
                            Spinach
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-container">
                <form class="question-form">
                    <button type="submit" class="question-submit">
                        <span class="question-submit-icon"></span>
                        Submit
                    </button>
                </form>
            </div>
        </div>
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


<script>

    var DragCtrl = {
        init: function () {
            this.cacheElements();
            this.addListeners()
        },
        cacheElements: function () {
            this.$contentItems = $('.classify-activity-content');
            this.$answerItems = $('.classify-activity-answer-item');
            this.$answerContainer = $('.classify-activity-answer-list')
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
                var $contentItem = $target.closest('.classify-activity-content');
                if (!that.srcItem) {
                    return
                }
                if ($contentItem.length) {
                    $contentItem.append(that.srcItem)
                } else if ($(that.srcItem).closest('.classify-activity-content').length){
                    var $wrapper = $('<div class="col-3"></div>')
                    $wrapper.append(that.srcItem)
                    that.$answerContainer.find('.mini-row').append($wrapper)
                }
                that.srcItem = null
            })
        }
    }
    DragCtrl.init();

    var FormCtrl = {
        init: function (opt) {
            this.opt = $.extend({
                onSubmit: $.noop
            }, opt)
            this.cacheElements()
            this.addListeners()
        },
        cacheElements: function () {
            this.$form = $('.question-form')
            this.$activityItems = $('.classify-activity-item')
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
                var $item = $(this)
                var answers = [];

                $item.find('.classify-activity-answer-item')
                    .each(function () {
                        var $answer = $(this);
                        answers.push($answer.data('id'))
                    })
                data[$item.data('id')] = answers;
            })
            return data
        }
    }
    FormCtrl.init({
        onSubmit: function (data) {
            console.log(data);

            $.ajax({
                url: "food-classify-feedback.php",
                data: {
                    student_id: <?php echo $studentID?>,
                    quiz_id: <?php echo $quizID?>,
                    answer_arr: JSON.stringify(data)
                },
                type: "POST",
                dataType: "json"
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

    //var details = [3, 5];

    function setFeedback(details, feedbackCls) {
        feedbackCls = feedbackCls || 'border-incorrect'
        var errorItemSelector = details.map(function (id) {
            return '.classify-activity-answer-item[data-id=' + id+ ']'
        }).join(',')
        $(errorItemSelector)
            .addClass(feedbackCls)
    }

</script>
</body>
</html>

