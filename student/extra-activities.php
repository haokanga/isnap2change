<?php
    //check login status
    require_once('./student-validation.php');

    require_once("../mysql-lib.php");
    require_once("../debug.php");
    $pageName = "extra-activities";

    $conn = null;

    try {
        $conn = db_connect();

        //get student week
        $studentWeek = getStudentWeek($conn, $studentID);

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

        debug_err($pageName, $e);
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
            <a class="home-link" href="#">SNAP</a>
            <ul class="nav-list">
                <li class="nav-item"><a  class="nav-link" href="http://taobao.com">GAME HOME</a></li>
                <li class="nav-item"><a  class="nav-link" href="http://taobao.com">Snap Facts</a></li>
                <li class="nav-item"><a  class="nav-link" href="http://taobao.com">Resources</a></li>
            </ul>
            <a href="#" class="settings">
                <span class="setting-icon"></span>
                <span class="setting-text">NoButSrsly</span>
            </a>
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
                    for($i = 0; $i < ($studentWeek-1); $i++) { ?>
                        <div class="extra-activities-tab-item"><?php echo ($i+1) ?></div>
<?php               } ?>
                        <div class="extra-activities-tab-item extra-activities-tab-item-active"><?php echo $studentWeek ?></div>
<?php
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
                switch($extraActivities[$i][$j]['QuizType']){
                    case "MCQ":
                        if(isset($extraActivities[$i][$j]['Status'])) { ?>
                        <a href="game-home.php">
                            <div class="game-nav-item game-nav-item-completed game-multiple-choice-quiz" >
<?php               } else { ?>
                        <a href="pre-task-material.php?quiz_id=<?php echo $extraActivities[$i][$j]['QuizID']?>">
                            <div class="game-nav-item game-multiple-choice-quiz">
<?php               } ?>
                                <div class="game-nav-logo"></div>
                                <div class="game-nav-title">Multiple Choice Question</div>
                                <div class="game-nav-divider"></div>
                                <div class="game-nav-desc">Complete Multiple Choice Question on <?php echo $extraActivities[$i][$j]['TopicName']?> to receive <?php echo $extraActivities[$i][$j]['Points']?> points.</div>
<?php                   if(isset($extraActivities[$i][$j]['Status'])) { ?>
                                <div class="game-nav-status">Completed</div>
<?php                   } ?>
                            </div>
                        </a>
<?php                   break;
                    case "SAQ":
                        if(isset($extraActivities[$i][$j]['Status'])) { ?>
                        <a href="game-home.php">
<?php                       if($extraActivities[$i][$j]['Status'] == "UNGRADED" || $extraActivities[$i][$j]['Status'] == "GRADED"){ ?>
                            <div class="game-nav-item game-nav-item-completed game-short-answer-question">
<?php                       } else { ?>
                            <div class="game-nav-item game-short-answer-question">
<?php                       }
                        } else { ?>
                        <a href="pre-task-material.php?quiz_id=<?php echo $extraActivities[$i][$j]['QuizID']?>">
                            <div class="game-nav-item game-short-answer-question">
<?php                   } ?>
                                <div class="game-nav-logo"></div>
                                <div class="game-nav-title">Short Answer Question</div>
                                <div class="game-nav-divider"></div>
                                <div class="game-nav-desc">Complete Short Answer Question on <?php echo $extraActivities[$i][$j]['TopicName']?> to receive <?php echo $extraActivities[$i][$j]['Points']?> points.</div>
<?php                   if($extraActivities[$i][$j]['Status'] == "UNGRADED") { ?>
                                <div class="game-nav-status">Completed</div>
<?php                   } else if($extraActivities[$i][$j]['Status'] == "GRADED") {?>
                                <div class="game-nav-status">Completed</div>
                                <div class="game-nav-feedback game-nav-feedback-animate">Teacher's Feedback Available</div>
<?php                   } ?>
                            </div>
                        </a>
<?php                   break;
                    case "Matching":
                        if(isset($extraActivities[$i][$j]['Status'])) { ?>
                        <a href="game-home.php">
                            <div class="game-nav-item game-nav-item-completed game-matching">
<?php                   } else { ?>
                        <a href="pre-task-material.php?quiz_id=<?php echo $extraActivities[$i][$j]['QuizID']?>">
                            <div class="game-nav-item game-matching">
<?php                   } ?>
                                <div class="game-nav-logo"></div>
                                <div class="game-nav-title">Matching</div>
                                <div class="game-nav-divider"></div>
                                <div class="game-nav-desc">Complete Matching on <?php echo $extraActivities[$i][$j]['TopicName']?> to receive <?php echo $extraActivities[$i][$j]['Points']?> points.</div>
<?php                   if(isset($extraActivities[$i][$j]['Status'])) { ?>
                                <div class="game-nav-status">Completed</div>
<?php                   } ?>
                            </div>
                        </a>
<?php                   break;
                    case "Poster":
                        if(isset($extraActivities[$i][$j]['Status'])) { ?>
                        <a href="poster.php?quiz_id=<?php echo $extraActivities[$i][$j]['QuizID']?>">
<?php                       if($extraActivities[$i][$j]['Status'] == "UNGRADED" || $extraActivities[$i][$j]['Status'] == "GRADED"){ ?>
                            <div class="game-nav-item game-nav-item-completed game-poster">
<?php                       } else { ?>
                            <div class="game-nav-item game-poster">
<?php                       }
                        } else { ?>
                        <a href="pre-task-material.php?quiz_id=<?php echo $extraActivities[$i][$j]['QuizID']?>">
                            <div class="game-nav-item game-poster">
<?php                       } ?>
                                <div class="game-nav-logo"></div>
                                <div class="game-nav-title">Poster</div>
                                <div class="game-nav-divider"></div>
                                <div class="game-nav-desc">Complete Poster on <?php echo $extraActivities[$i][$j]['TopicName']?> to receive <?php echo $extraActivities[$i][$j]['Points']?> points.</div>
<?php                   if($extraActivities[$i][$j]['Status'] == "UNGRADED" || $extraActivities[$i][$j]['Status'] == "GRADED") { ?>
                                <div class="game-nav-status">Completed</div>
<?php                   } ?>
                            </div>
                        </a>
<?php                   break;
                    case "Calculator":
                        if(isset($extraActivities[$i][$j]['Status'])) { ?>
                        <a href="cost-calculator.php?quiz_id=<?php echo $extraActivities[$i][$j]['QuizID']?>">
                            <div class="game-nav-item game-nav-item-completed game-cost-calculator">
<?php                   } else { ?>
                        <a href="pre-task-material.php?quiz_id=<?php echo $extraActivities[$i][$j]['QuizID']?>">
                            <div class="game-nav-item game-cost-calculator">
<?php                   } ?>
                                <div class="game-nav-logo"></div>
                                <div class="game-nav-title">Cost Calculator</div>
                                <div class="game-nav-divider"></div>
                                <div class="game-nav-desc">Complete Cost Calculator on <?php echo $extraActivities[$i][$j]['TopicName']?> to receive <?php echo $extraActivities[$i][$j]['Points']?> points.</div>
<?php                   if(isset($extraActivities[$i][$j]['Status'])) { ?>
                                <div class="game-nav-status">Completed</div>
<?php                   } ?>
                            </div>
                        </a>
<?php                   break;
                    case "DrinkingTool":
                        if(isset($extraActivities[$i][$j]['Status'])) { ?>
                        <a href="standard-drinking-tool.php?quiz_id=<?php echo $extraActivities[$i][$j]['QuizID']?>">
                            <div class="game-nav-item game-nav-item-completed game-standard-drinking-tool">
<?php                   } else { ?>
                        <a href="pre-task-material.php?quiz_id=<?php echo $extraActivities[$i][$j]['QuizID']?>">
                            <div class="game-nav-item game-standard-drinking-tool">
<?php                   } ?>
                                <div class="game-nav-logo"></div>
                                <div class="game-nav-title">Standard Drinking Tool</div>
                                <div class="game-nav-divider"></div>
                                <div class="game-nav-desc">Complete Standard Drinking Tool on <?php echo $extraActivities[$i][$j]['TopicName']?> to receive <?php echo $extraActivities[$i][$j]['Points']?> points.</div>
<?php                   if(isset($extraActivities[$i][$j]['Status'])) { ?>
                                <div class="game-nav-status">Completed</div>
<?php                   } ?>
                            </div>
                        </a>
<?php                   break;
                    case "Video":
                        if(isset($extraActivities[$i][$j]['Status'])) { ?>
                        <a href="standard-drinking-tool.php?quiz_id=<?php echo $extraActivities[$i][$j]['QuizID']?>">
                            <div class="game-nav-item game-nav-item-completed game-video">
<?php                   } else { ?>
                        <a href="pre-task-material.php?quiz_id=<?php echo $extraActivities[$i][$j]['QuizID']?>">
                            <div class="game-nav-item game-video">
<?php                   } ?>
                                <div class="game-nav-logo"></div>
                                <div class="game-nav-title">Video</div>
                                <div class="game-nav-divider"></div>
                                <div class="game-nav-desc">Complete Standard Drinking Tool on <?php echo $extraActivities[$i][$j]['TopicName']?> to receive <?php echo $extraActivities[$i][$j]['Points']?> points.</div>
<?php                    if($extraActivities[$i][$j]['Status'] == "UNGRADED") { ?>
                                <div class="game-nav-status">Completed</div>
<?php                    } else if($extraActivities[$i][$j]['Status'] == "GRADED") {?>
                                <div class="game-nav-status">Completed</div>
                                <div class="game-nav-feedback game-nav-feedback-animate">Teacher's Feedback Available</div>
<?php                    } ?>
                            </div>
                        </a>
<?php                   break;
    
            } ?>
                    </div>
<?php    } ?>
            </div>
<?php } ?>
            </div>
        </div>
    </div>

    <ul class="sitenav">
        <li class="sitenav-item sitenav-game-home"><a href="#"></a></li>
        <li class="sitenav-item sitenav-achievement"><a href="#"></a></li>
        <li class="sitenav-item sitenav-progress"><a href="#"></a></li>
        <li class="sitenav-item sitenav-extra-activities-material"><a href="#"></a></li>
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
