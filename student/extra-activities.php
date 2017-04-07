<?php
    //check login status
    require_once('./student-validation.php');
    require_once("../mysql-lib.php");
    require_once("../debug.php");

    $pageName = "extra-activities";
    $conn = null;

    try {
        $conn = db_connect();

        //get quiz viewed attribute
        $quizViewedAttrs = getQuizViewdAttr($conn, $studentID);

        //get student question viewed attribute
        $studentQuesViewedAttrs = getStudentQuesViewedAttr($conn, $studentID);

        //get student week
        $studentWeek = getStudentWeek($conn, $studentID);

        //get active week
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            if(isset($_GET["week"])) {
                $activeWeek = $_GET["week"];
            } else {
                $activeWeek = $studentWeek;
            }
        } else {

        }

        //get max week
        $maxWeek = getMaxWeek($conn)->WeekNum;

        //get material topics
        $extraActivities = array();

        for($i = 0; $i < $studentWeek; $i++) {
            array_push($extraActivities, getQuizzesStatusByWeek($conn, $studentID, ($i+1), 1));
        }

    } catch(Exception $e) {
        if($conn != null) {
            db_close($conn);
        }

        debug_err($e);
        //to do: handle sql error
        //...
        //exit;
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
    <script src="./js/snap.js"></script>
    <style>

        .extra-activities-detail {
            padding-top: 20px;
        }
        .extra-activities-header {
            text-align: center;
        }
        .extra-activities-logo {
            width: 128px;
            height: 128px;
            margin: 0 auto;
            background-size: 100% 100%;
            background-image: url("./img/extra_activites_icon.png");
        }
        .extra-activities-title {
            font-size: 28px;
        }
        .extra-activities-intro {
            width: 300px;
            font-family: "Maitree", serif;
            font-size: 18px;
            margin: 0 auto;
        }

        .extra-activities-tab {
            padding-top: 40px;
            padding-bottom: 30px;
            text-align: center;
        }
        .extra-activities-tab-title {
            font-size: 14px;
            margin: 10px 0;
        }
        .extra-activities-tab-item {
            width: 48px;
            height: 48px;
            line-height: 44px;
            margin: 0 10px;
            display: inline-block;
            border: 2px solid;
            border-radius: 50%;
            color:  #fcee2d;
            font-size: 32px;
            cursor: pointer;
        }
        .extra-activities-tab-item-active {
            color: #fff;
            background-color: #fcee2d;
            border-color: #fcee2d;
        }
        .extra-activities-tab-item-disabled {
            text-indent: -999em;
            border: 0;
            background-image: url("./img/locked_icon.png");
            cursor: not-allowed;
            background-size: 100% 100%;
        }

        .extra-activities-tab-content {
            max-width: 1000px;
            margin: 0 auto;
            text-align: center;
            overflow: hidden;
        }

        .extra-activities-week-detail {
            display: none;
        }
        .extra-activities-week-detail-active {
            display: block;
        }
        .game-nav-item {
            width: 340px;
            margin: 20px auto;
            position: relative;
        }
        .game-nav-item-completed .game-nav-logo,
        .game-nav-item-completed .game-nav-title,
        .game-nav-item-completed .game-nav-divider,
        .game-nav-item-completed .game-nav-desc {
            opacity: 0.5;
        }
        .game-nav-item-completed .game-nav-status,
        .game-nav-item-completed .game-nav-feedback {
            display: block;
        }
        .game-nav-status {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            text-align: center;
            color: #fff;
            font-size: 70px;
            display: none;
        }
        .game-nav-feedback {
            position: absolute;
            top: 80px;
            left: 0;
            right: 0;
            text-align: center;
            color: #fcee2d;
            font-size: 20px;
            display: none;
        }
        .game-nav-feedback-animate {
            animation: fadein 2s ease-out infinite;
        }
        @keyframes fadein {
            0% {
                opacity: 0;
            }
            100% {
                opacity: 1;
            }
        }
        .game-nav-logo {
            display: block;
            width: 128px;
            height: 128px;
            margin: 0 auto 0 auto;
            background-size: 100% 100%;
        }

        .game-multiple-choice-quiz {
            color: #f7751e;
        }
        .game-multiple-choice-quiz .game-nav-logo {
            background-image: url("./img/quiz_icon.png");
        }
        .game-short-answer-question {
            color: #00f8cd;
        }
        .game-short-answer-question .game-nav-logo {
            background-image: url("./img/short_answer_question_icon.png");
        }
        .game-poster {
            color: #f7751e;
        }
        .game-poster .game-nav-logo {
            background-image: url("./img/poster_icon.png");
        }
        .game-matching {
            color: #AF24D1;
        }
        .game-matching .game-nav-logo {
            background-image: url("./img/matching_icon.png");
        }
        .game-cost-calculator {
            color: #FCEE2D;
        }
        .game-cost-calculator .game-nav-logo {
            background-image: url("./img/calculator_icon.png");
        }
        .game-standard-drinking-tool {
            color: #DB1B1B;
        }
        .game-standard-drinking-tool .game-nav-logo {
            background-image: url("./img/standard_drinking_tool_icon.png");
        }
        .game-video {
            color: #AF24D1;
        }
        .game-video .game-nav-logo {
            background-image: url("./img/video_icon.png");
        }
        .game-nav-title {
            font-size: 24px;
        }
        .game-nav-divider {
            border-top: 2px solid;
        }
        .game-nav-desc {
            width: 340px;
            margin: 0 auto 0 auto;
            color: #fff;
        }
    </style>
</head>
<body>

<div class="page-wrapper">
    <div class="header-wrapper">
        <div class="header">
            <a class="home-link">SNAP</a>
            <ul class="nav-list">
                <li class="nav-item"><a  class="nav-link" href="game-home.php">Snap Change</a></li>
                <li class="nav-item"><a  class="nav-link" href="snap-facts.php">Snap Facts</a></li>
                <li class="nav-item"><a  class="nav-link" href="#">Resources</a></li>
            </ul>
            <div class="settings">
                <div class="info-item info-notification">
                    <a class="info-icon" href="javascript:;"></a>
                    <?php           if (count($quizViewedAttrs) != 0) { ?>
                        <span class="info-number"><?php echo count($quizViewedAttrs) ?></span>
                    <?php           } ?>
                    <ul class="info-message-list">
                        <?php           for ($i = 0; $i < count($quizViewedAttrs); $i++) {
                            if ($quizViewedAttrs[$i]["extraQuiz"] == 0) {
                                $url = "weekly-task.php?week=".$quizViewedAttrs[$i]["week"];
                            } else {
                                $url = "extra-activities.php?week=".$quizViewedAttrs[$i]["week"];
                            }?>
                            <li class="info-message-item">
                                <a href="<?php echo $url ?>">
                                    <?php
                                    $message = "A ";

                                    switch($quizViewedAttrs[$i]["quizType"]) {
                                        case "Video":
                                            $message = $message."Video task";
                                            break;
                                        case "Image":
                                            $message = $message."Image task";
                                            break;
                                        case "SAQ":
                                            $message = $message."Short Answer Question task";
                                            break;
                                        case "Poster":
                                            $message = $message."Poster task";
                                            break;
                                    }

                                    $message = $message." in Week ".$quizViewedAttrs[$i]["week"]." has feedback for you.";
                                    echo $message;
                                    ?>
                                </a>
                            </li>
                        <?php           } ?>
                    </ul>
                </div>
                <div class="info-item info-message">
                    <a class="info-icon" href="javascript:;"></a>
                    <?php           if (count($studentQuesViewedAttrs) != 0) { ?>
                        <span class="info-number"><?php echo count($studentQuesViewedAttrs) ?></span>
                    <?php           } ?>
                    <ul class="info-message-list">
                        <li class="info-message-item">
                            <?php
                            for ($i = 0; $i < count($studentQuesViewedAttrs); $i++) { ?>
                                <a href="messages.php">
                                    You message about <?php echo $studentQuesViewedAttrs[$i]->Subject ?> has been replied.
                                </a>
                            <?php               } ?>
                        </li>
                    </ul>
                </div>


                <div class="setting-icon dropdown">
                    <ul class="dropdown-menu">
                        <li class="dropdown-item"><a href="settings.php">Settings</a></li>
                        <li class="dropdown-item"><a href="#">Log out</a></li>
                    </ul>
                </div>
                <a class="setting-text"><?php echo $_SESSION["studentUsername"]?></a>
            </div>
        </div>
    </div>
    <div class="content-wrapper">
        <div class="extra-activities-detail">
            <div class="extra-activities-header">
                <div class="extra-activities-logo"></div>
                <div class="extra-activities-title">Extra Activities</div>
                <div class="extra-activities-intro">Complete these extra activities to receive more bonus points</div>
            </div>
            <div class="extra-activities-tab">
                <h2 class="extra-activities-tab-title">Select Your week</h2>
                <div class="extra-activities-tab-list">
<?php
                    for($i = 0; $i < $studentWeek; $i++) { ?>
                        <div class="extra-activities-tab-item"><?php echo ($i+1) ?></div>
<?php               }
                    for($i = $studentWeek; $i < $maxWeek; $i++) { ?>
                        <div class="extra-activities-tab-item extra-activities-tab-item-disabled"><?php echo ($i+1) ?></div>
<?php               } ?>
                </div>
            </div>
            <div class="extra-activities-tab-content">
<?php
        for ($i = 0; $i < $studentWeek; $i++) {
            if($i == ($studentWeek-1)) { ?>
                <div class="extra-activities-week-detail extra-activities-week-detail-active mini-row">
<?php       } else { ?>
                <div class="extra-activities-week-detail mini-row">
<?php       }

            for ($j = 0; $j < count($extraActivities[$i]); $j++) { ?>
                    <div class="col-6">
<?php
            //list of question type
            switch ($extraActivities[$i][$j]['QuizType']) {
                case "MCQ":
                    if (isset($extraActivities[$i][$j]['Status'])) { ?>
                        <a href="game-home.php">
                            <div class="game-nav-item game-nav-item-completed game-multiple-choice-quiz" >
                                <div class="game-nav-logo"></div>
                                <div class="game-nav-title">Multiple Choice Question</div>
                                <div class="game-nav-divider"></div>
                                <div class="game-nav-desc">Complete Multiple Choice Question on <?php echo $extraActivities[$i][$j]['TopicName']?> to receive <?php echo $extraActivities[$i][$j]['Points']?> points.</div>
                                <div class="game-nav-status">Completed</div>
                            </div>
                        </a>
<?php               } else { ?>
                        <a href="pre-task-material.php?quiz_id=<?php echo $extraActivities[$i][$j]['QuizID']?>">
                            <div class="game-nav-item game-multiple-choice-quiz">
                                <div class="game-nav-logo"></div>
                                <div class="game-nav-title">Multiple Choice Question</div>
                                <div class="game-nav-divider"></div>
                                <div class="game-nav-desc">Complete Multiple Choice Question on <?php echo $extraActivities[$i][$j]['TopicName']?> to receive <?php echo $extraActivities[$i][$j]['Points']?> points.</div>
                            </div>
                        </a>
<?php               }
                    break;
                case "SAQ":
                    if (isset($extraActivities[$i][$j]['Status'])) {
                        if ($extraActivities[$i][$j]['Status'] == "UNGRADED" || $extraActivities[$i][$j]['Status'] == "GRADED") { ?>
                            <a href="game-home.php">
                                <div class="game-nav-item game-nav-item-completed game-short-answer-question">
                                    <div class="game-nav-logo"></div>
                                    <div class="game-nav-title">Short Answer Question</div>
                                    <div class="game-nav-divider"></div>
                                    <div class="game-nav-desc">Complete Short Answer Question on <?php echo $extraActivities[$i][$j]['TopicName']?> to receive <?php echo $extraActivities[$i][$j]['Points']?> points.</div>
                                    <div class="game-nav-status">Completed</div>
<?php                           if ($extraActivities[$i][$j]['Status'] == "GRADED") { ?>
                                        <div class="game-nav-feedback game-nav-feedback-animate">Teacher's Feedback Available</div>
<?php                           } ?>
                                </div>
                            </a>
<?php                   }

                        if ($extraActivities[$i][$j]['Status'] == "UNSUBMITTED") { ?>
                            <a href="game-home.php">
                                <div class="game-nav-item game-short-answer-question">
                                    <div class="game-nav-logo"></div>
                                    <div class="game-nav-title">Short Answer Question</div>
                                    <div class="game-nav-divider"></div>
                                    <div class="game-nav-desc">Complete Short Answer Question on <?php echo $extraActivities[$i][$j]['TopicName']?> to receive <?php echo $extraActivities[$i][$j]['Points']?> points.</div>
                                </div>
                            </a>
<?php                       }
                    } else { ?>
                        <a href="pre-task-material.php?quiz_id=<?php echo $extraActivities[$i][$j]['QuizID']?>">
                            <div class="game-nav-item game-short-answer-question">
                                <div class="game-nav-logo"></div>
                                <div class="game-nav-title">Short Answer Question</div>
                                <div class="game-nav-divider"></div>
                                <div class="game-nav-desc">Complete Short Answer Question on <?php echo $extraActivities[$i][$j]['TopicName']?> to receive <?php echo $extraActivities[$i][$j]['Points']?> points.</div>
                            </div>
                        </a>
<?php               }
                    break;
                case "Matching":
                    if (isset($extraActivities[$i][$j]['Status'])) { ?>
                        <a href="game-home.php">
                            <div class="game-nav-item game-nav-item-completed game-matching">
                                <div class="game-nav-logo"></div>
                                <div class="game-nav-title">Matching</div>
                                <div class="game-nav-divider"></div>
                                <div class="game-nav-desc">Complete Matching on <?php echo $extraActivities[$i][$j]['TopicName']?> to receive <?php echo $extraActivities[$i][$j]['Points']?> points.</div>
                                <div class="game-nav-status">Completed</div>
                            </div>
                        </a>
<?php               } else { ?>
                        <a href="pre-task-material.php?quiz_id=<?php echo $extraActivities[$i][$j]['QuizID']?>">
                            <div class="game-nav-item game-matching">
                                <div class="game-nav-logo"></div>
                                <div class="game-nav-title">Matching</div>
                                <div class="game-nav-divider"></div>
                                <div class="game-nav-desc">Complete Matching on <?php echo $extraActivities[$i][$j]['TopicName']?> to receive <?php echo $extraActivities[$i][$j]['Points']?> points.</div>
                            </div>
                        </a>
<?php               }
                    break;
                case "Poster":
                    if (isset($extraActivities[$i][$j]['Status'])) {
                        if ($extraActivities[$i][$j]['Status'] == "UNGRADED" || $extraActivities[$i][$j]['Status'] == "GRADED") { ?>
                            <a href="poster.php?quiz_id=<?php echo $extraActivities[$i][$j]['QuizID'] ?>">
                                <div class="game-nav-item game-nav-item-completed game-poster">
                                    <div class="game-nav-logo"></div>
                                    <div class="game-nav-title">Poster</div>
                                    <div class="game-nav-divider"></div>
                                    <div class="game-nav-desc">Complete Poster on <?php echo $extraActivities[$i][$j]['TopicName']?> to receive <?php echo $extraActivities[$i][$j]['Points']?> points.</div>
                                    <div class="game-nav-status">Completed</div>
                                </div>
                            </a>
<?php                   }

                        if ($extraActivities[$i][$j]['Status'] == "UNSUBMITTED") { ?>
                            <a href="poster.php?quiz_id=<?php echo $extraActivities[$i][$j]['QuizID'] ?>">
                                <div class="game-nav-item game-poster">
                                    <div class="game-nav-logo"></div>
                                    <div class="game-nav-title">Poster</div>
                                    <div class="game-nav-divider"></div>
                                    <div class="game-nav-desc">Complete Poster on <?php echo $extraActivities[$i][$j]['TopicName']?> to receive <?php echo $extraActivities[$i][$j]['Points']?> points.</div>
                                </div>
                            </a>
<?php                   }
                    } else { ?>
                        <a href="pre-task-material.php?quiz_id=<?php echo $extraActivities[$i][$j]['QuizID']?>">
                            <div class="game-nav-item game-poster">
                                <div class="game-nav-logo"></div>
                                <div class="game-nav-title">Poster</div>
                                <div class="game-nav-divider"></div>
                                <div class="game-nav-desc">Complete Poster on <?php echo $extraActivities[$i][$j]['TopicName']?> to receive <?php echo $extraActivities[$i][$j]['Points']?> points.</div>
                            </div>
                        </a>
<?php               }
                    break;
                case "Calculator":
                    if (isset($extraActivities[$i][$j]['Status'])) { ?>
                        <a href="cost-calculator.php?quiz_id=<?php echo $extraActivities[$i][$j]['QuizID']?>">
                            <div class="game-nav-item game-nav-item-completed game-cost-calculator">
                                <div class="game-nav-logo"></div>
                                <div class="game-nav-title">Cost Calculator</div>
                                <div class="game-nav-divider"></div>
                                <div class="game-nav-desc">Complete Cost Calculator on <?php echo $extraActivities[$i][$j]['TopicName']?> to receive <?php echo $extraActivities[$i][$j]['Points']?> points.</div>
                                <div class="game-nav-status">Completed</div>
                            </div>
                        </a>
<?php               } else { ?>
                        <a href="pre-task-material.php?quiz_id=<?php echo $extraActivities[$i][$j]['QuizID']?>">
                            <div class="game-nav-item game-cost-calculator">
                                <div class="game-nav-logo"></div>
                                <div class="game-nav-title">Cost Calculator</div>
                                <div class="game-nav-divider"></div>
                                <div class="game-nav-desc">Complete Cost Calculator on <?php echo $extraActivities[$i][$j]['TopicName']?> to receive <?php echo $extraActivities[$i][$j]['Points']?> points.</div>
                            </div>
                        </a>
<?php               }
                    break;
                case "DrinkingTool":
                    if (isset($extraActivities[$i][$j]['Status'])) { ?>
                        <a href="standard-drinking-tool.php?quiz_id=<?php echo $extraActivities[$i][$j]['QuizID']?>">
                            <div class="game-nav-item game-nav-item-completed game-standard-drinking-tool">
                                <div class="game-nav-logo"></div>
                                <div class="game-nav-title">Standard Drinking Tool</div>
                                <div class="game-nav-divider"></div>
                                <div class="game-nav-desc">Complete Standard Drinking Tool on <?php echo $extraActivities[$i][$j]['TopicName']?> to receive <?php echo $extraActivities[$i][$j]['Points']?> points.</div>
                                <div class="game-nav-status">Completed</div>
                            </div>
                        </a>
<?php               } else { ?>
                        <a href="pre-task-material.php?quiz_id=<?php echo $extraActivities[$i][$j]['QuizID']?>">
                            <div class="game-nav-item game-standard-drinking-tool">
                                <div class="game-nav-logo"></div>
                                <div class="game-nav-title">Standard Drinking Tool</div>
                                <div class="game-nav-divider"></div>
                                <div class="game-nav-desc">Complete Standard Drinking Tool on <?php echo $extraActivities[$i][$j]['TopicName']?> to receive <?php echo $extraActivities[$i][$j]['Points']?> points.</div>
                            </div>
                        </a>
<?php               }
                    break;
                case "Video":
                    if (isset($extraActivities[$i][$j]['Status'])) {
                        if ($extraActivities[$i][$j]['Status'] == "UNGRADED" || $extraActivities[$i][$j]['Status'] == "GRADED") { ?>
                            <a href="video.php?quiz_id=<?php echo $extraActivities[$i][$j]['QuizID']?>">
                                <div class="game-nav-item game-nav-item-completed game-video">
                                    <div class="game-nav-logo"></div>
                                    <div class="game-nav-title">Video</div>
                                    <div class="game-nav-divider"></div>
                                    <div class="game-nav-desc">Complete Video on <?php echo $extraActivities[$i][$j]['TopicName']?> to receive <?php echo $extraActivities[$i][$j]['Points']?> points.</div>
                                    <div class="game-nav-status">Completed</div>
<?php                       if ($extraActivities[$i][$j]['Status'] == "GRADED") {
                                if ($extraActivities[$i][$j]['Viewed'] == 0) { ?>
                                    <div class="game-nav-feedback game-nav-feedback-animate">Teacher's Feedback Available</div>
<?php                           } else { ?>
                                    <div class="game-nav-feedback">Teacher's Feedback Available</div>
<?php                           }
                            } ?>
                                </div>
                            </a>
<?php               }

                        if ($extraActivities[$i][$j]['Status'] == "UNSUBMITTED") { ?>
                            <a href="video.php?quiz_id=<?php echo $extraActivities[$i][$j]['QuizID']?>">
                                <div class="game-nav-item game-video">
                                    <div class="game-nav-logo"></div>
                                    <div class="game-nav-title">Video</div>
                                    <div class="game-nav-divider"></div>
                                    <div class="game-nav-desc">Complete Video on <?php echo $extraActivities[$i][$j]['TopicName']?> to receive <?php echo $extraActivities[$i][$j]['Points']?> points.</div>
                                </div>
                            </a>
<?php                   }
                    } else { ?>
                        <a href="pre-task-material.php?quiz_id=<?php echo $extraActivities[$i][$j]['QuizID']?>">
                            <div class="game-nav-item game-video">
                                <div class="game-nav-logo"></div>
                                <div class="game-nav-title">Video</div>
                                <div class="game-nav-divider"></div>
                                <div class="game-nav-desc">Complete Video on <?php echo $extraActivities[$i][$j]['TopicName']?> to receive <?php echo $extraActivities[$i][$j]['Points']?> points.</div>
                            </div>
                        </a>
<?php               }
                    break;

        } ?>
                    </div>
<?php    } ?>
            </div>
<?php } ?>
            </div>
        </div>
    </div>

    <ul class="sitenav">
        <li class="sitenav-item sitenav-healthy-recipes"><a href="#"></a></li>
        <li class="sitenav-item sitenav-game-home"><a href="#"></a></li>
        <li class="sitenav-item sitenav-extra-activities"><a href="extra-activities.php"></a></li>
        <li class="sitenav-item sitenav-progress"><a href="progress.php"></a></li>
        <li class="sitenav-item sitenav-reading-material"><a href="reading-material.php"></a></li>
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


    var TabCtrl = {
        cls: {
            tabActive: 'extra-activities-tab-item-active',
            tabDisabled: 'extra-activities-tab-item-disabled',
            tabContentActive: 'extra-activities-week-detail-active'
        },
        init: function (opt) {
            opt = opt || {
                    onTabChange: $.noop
                }
            this.onTabChange = opt.onTabChange
            this.cacheElements()
            this.addListeners()
        },
        cacheElements: function () {
            var $main = $('.extra-activities-tab')
            this.$main = $main
            this.$tabItems = $main.find('.extra-activities-tab-item')
            this.$tabContent = $('.extra-activities-tab-content')
            this.$tabContentItems = this.$tabContent.find('.extra-activities-week-detail')
        },
        addListeners: function () {
            var that = this

            this.$main.on('click', '.extra-activities-tab-item', function (e) {
                var $target = $(e.currentTarget)
                var cls = that.cls

                if (!$target.hasClass(cls.tabActive) && !$target.hasClass(cls.tabDisabled)) {
                    var index = that.$tabItems.index(e.currentTarget)
                    that.activeItem(index)
                    that.onTabChange(index)
                }
            })
        },
        activeItem: function (index) {
            this.$tabItems.removeClass(this.cls.tabActive)
                .eq(index)
                .addClass(this.cls.tabActive)
            this.$tabContentItems.removeClass(this.cls.tabContentActive)
                .eq(index)
                .addClass(this.cls.tabContentActive)
        }
    }
    TabCtrl.init({
        onTabChange: function (index) {
            MaterialCtrl.showNavPanel(index)
        }
    })

    TabCtrl.activeItem(<?php echo $activeWeek-1 ?>);


    var MaterialCtrl = {
        init: function () {
            this.cacheElements()
            this.addListeners()
        },
        cacheElements: function () {
            var $navMain = $('.extra-activities-tab-content')
            this.$navMain = $navMain
            this.$navPanels = $navMain.find('.extra-activities-week-detail')
            var $materialList = $('.material-list')
            this.$materialList = $materialList
            this.$materialItems = $materialList.find('.material-item')
        },
        addListeners: function () {
            var that = this
            that.$navMain.on('click', '.extra-activities-item', function (e) {
                var $target = $(e.currentTarget)
                var targetMaterialCls = $target.data('target')
                that.showMaterialDetail(targetMaterialCls)
            })
            that.$materialList.on('click', '.material-close', function (e) {
                that.hideMaterialDetail()
            })
        },
        showNavPanel: function (index) {
            this.$navMain.show()
            this.$navPanels.hide()
                .eq(index)
                .show()
            this.$materialItems.removeClass('material-item-active')
            this.$materialList.hide()
        },
        showMaterialDetail: function (targetMaterialCls) {
            this.$navMain.hide()
            this.$materialList.show()
            this.$materialItems.removeClass('material-item-active')
                .filter(targetMaterialCls)
                .addClass('material-item-active')
        },

        hideMaterialDetail: function () {
            this.$navMain.show()
            this.$materialList.hide()
            this.$materialItems.removeClass('material-item-active')
        }
    }

</script>
</body>
</html>
