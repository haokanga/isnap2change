<?php
    //check login status
    require_once('./student-validation.php');

    require_once("../mysql-lib.php");
    require_once("../debug.php");
    $pageName = "weekly-task";

    $conn = null;

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        if(isset($_GET["week"])) {
            $week = $_GET["week"];
        } else {

        }
    } else {

    }

    $conn = null;

    try{
        $conn = db_connect();

        //check whether the week is locked or not
        if ($week > getStudentWeek($conn, $studentID)) {
            echo '<script>alert("This is a locked week!")</script>';
            echo '<script>window.location="game-home.php"</script>';
        }

        //get due time for this week
        $dueTime = getStuWeekRecord($conn, $studentID, $week);

        //get all quizzes and status by studentID and week
        $quizzesStatusRes = getQuizzesStatusByWeek($conn, $studentID, $week);


    } catch(Exception $e) {
        if($conn != null) {
            db_close($conn);
        }

        debug_err($pageName, $e);
        //to do: handle sql error
        //...
        exit;
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0, width=device-width, user-scalable=no">
    <title>WEEKLY TASK</title>
    <link rel="stylesheet" href="./css/common.css">
    <link href='https://fonts.googleapis.com/css?family=Maitree|Lato:400,900' rel='stylesheet' type='text/css'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="./js/vendor/kinetic.js"></script>
    <script src="./js/vendor/jquery.final-countdown.min.js"></script>
    <script src="./js/weekly-task.js"></script>
    <style>
        .page-wrapper {
            padding-top: 50px;
            margin: 0 auto;
        }
        .header-fixed {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1024;
        }
        .sitenav {
            position: fixed;
            left: 20px;
            top: 20%;
        }
        .sitenav-item a {
            display: block;
            width: 64px;
            height: 64px;
            background-size: 100% 100%;
            margin-bottom: 20px;
        }
        .sitenav-game-home a {
            background-image: url("./img/game_icon.png");
        }
        .sitenav-achievement a {
            background-image: url("./img/achievement_logo.png");

        }
        .sitenav-progress a {
            background-image: url("./img/progress_icon.png");

        }
        .sitenav-reading-material a {
            background-image: url("./img/reading_material_icon.png");
        }


        .week-detail {
            max-width: 1000px;
            margin: 0 auto 0 auto;
            padding-top: 3%;
        }

        .week-item {
            width: 154px;
            height: 154px;
            display: block;
            margin: 0 auto;
        }
        .week-link {
            display: block;
            width: 100%;
        }
        .slick-current .week-img {
            width: 120px;
            height: 120px;
        }
        .week-img {
            width: 100px;
            height: 100px;
            margin: 0 auto;
            display: block;
            background-size: 100% 100%;
        }
        .week-text {
            display: block;
            text-align: center;
            font-size: 20px;
        }
        .week-1 .week-img {
            background-image: url("./img/one_icon.png");
        }
        .week-2 .week-img {
            background-image: url("./img/two_icon.png");
        }
        .week-3 .week-img {
            background-image: url("./img/three_icon.png");
        }
        .week-4 .week-img {
            background-image: url("./img/four_icon.png");
        }
        .week-5 .week-img {
            background-image: url("./img/five_icon.png");
        }
        .week-6 .week-img {
            background-image: url("./img/six_icon.png");
        }
        .week-7 .week-img {
            background-image: url("./img/seven_icon.png");
        }
        .week-8 .week-img {
            background-image: url("./img/eight_icon.png");
        }
        .week-9 .week-img {
            background-image: url("./img/nine_icon.png");
        }
        .week-10 .week-img {
            background-image: url("./img/ten_icon.png");
        }
        .week-more .week-img {
            background-image: url("./img/one_icon.png");
        }


        /**
         * count down timer
         **/
        .time-remain {
            max-width: 1000px;
            margin: 0 auto;
            text-align: center;
        }
        .time-remain-title {
            font-size: 24px;
        }
        .time-remain-detail {
            margin: 20px 0 0 0;
        }
        .time-remain-item {
            width: 102px;
            height: 102px;
            border-radius: 50%;
            border: 2px solid #fff;
            margin: 0 30px;
            display: inline-block;
            font-size: 20px;
        }
        .time-remain-hour {
            border-color: #f4e62e;
        }
        .time-remain-minute {
            border-color: #af24d1;
        }
        .time-remain-second {
            border-color: #36e8c5;
        }
        .time-number {
            margin: 10px 0 0px 0;
        }

        .time-label {
            font-size: 20px;
        }

        .game-nav {
            max-width: 1000px;
            margin: 20px auto 0;
            text-align: center;
            overflow: hidden;
        }
        .game-nav-item {
            width: 340px;
            margin: 20px auto;
        }

        .game-nav-logo {
            display: block;
            width: 128px;
            height: 128px;
            margin: 0 auto 0 auto;
            background-size: 100% 100%;
        }
        .game-fruit-ninja {
            color: #2fedc9;
        }
        .game-fruit-ninja .game-nav-logo {
            background-image: url("./img/game_icon.png");
        }
        .game-short-answer-question {
            color: #f7751e;
        }
        .game-short-answer-question .game-nav-logo {
            background-image: url("./img/quiz_icon.png");
        }
        .game-multiple-choice-quiz {
            color: #f7751e;
        }
        .game-multiple-choice-quiz .game-nav-logo {
            background-image: url("./img/quiz_icon.png");
        }
        .game-video {
            color: #93c;
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
    <style>
        .visually-hide {
            position: absolute;
            left: -9999em;
        }
        html, body {
            height: 100%;
        }
        html {
            background-position: center center;
            background-repeat: no-repeat;
            background-size: cover;
        }
        body {
            background-color: rgba(44,62,80 , 0.6 );
            background-position: center;
            background-repeat: repeat;
            font-family: 'Raleway', 'Arial', sans-serif;
        }
        .countdown-container {
            position: relative;
        }
        .clock-item .inner {
            height: 0px;
            padding-bottom: 100%;
            position: relative;
            width: 100%;
        }
        .clock-canvas {
            background-color: rgba(255, 255, 255, .1);
            border-radius: 50%;
            height: 0px;
            padding-bottom: 100%;
        }
        .text {
            color: #fff;
            font-size: 30px;
            font-weight: bold;
            margin-top: -50px;
            position: absolute;
            top: 50%;
            text-align: center;
            text-shadow: 1px 1px 1px rgba(0, 0, 0, 1);
            width: 100%;
        }
        .text .val {
            font-size: 50px;
            line-height: 1;
            margin: 10px 0 10px 0;
        }
        .text .type-time {
            font-size: 20px;
        }
        @media (min-width: 768px) and (max-width: 991px) {
            .clock-item {
                margin-bottom: 30px;
            }
        }
        @media (max-width: 767px) {
            .clock-item {
                margin: 0px 30px 30px 30px;
            }
        }

        .clock-row {
            text-align: center;
        }
        .clock-item {
            display: inline-block;
            margin: 0 15px;
            width: 150px;
            height: 150px;
        }

    </style>
</head>
<body>

<div class="page-wrapper">
    <div class="header-wrapper header-fixed">
        <div class="header">
            <a class="home-link" href="#">SNAP</a>
            <ul class="nav-list">
                <li class="nav-item"><a  class="nav-link" href="http://taobao.com">GAME HOME</a></li>
                <li class="nav-item"><a  class="nav-link" href="http://taobao.com">Snap Facts</a></li>
                <li class="nav-item"><a  class="nav-link" href="http://taobao.com">Resources</a></li>
            </ul>
            <a href="#" class="settings">
                <span class="setting-icon"></span>
                <span class="setting-text"><?php echo $studentUsername?></span>
            </a>
        </div>
    </div>

    <div class="content-wrapper">
        <div class="week-detail">
<?php
    switch ($week) {
        case 1: ?>
            <div class="week-item week-1">
                <a class="week-link">
                    <span class="week-img"></span>
                    <span class="week-text">Week 1</span>
                </a>
            </div> <?php ;
            break;
        case 2: ?>
            <div class="week-item week-2">
                <a class="week-link">
                    <span class="week-img"></span>
                    <span class="week-text">Week 2</span>
                </a>
            </div> <?php ;
            break;
        case 3: ?>
            <div class="week-item week-3">
                <a class="week-link">
                    <span class="week-img"></span>
                    <span class="week-text">Week 3</span>
                </a>
            </div> <?php ;
            break;
        case 4: ?>
            <div class="week-item week-4">
                <a class="week-link">
                    <span class="week-img"></span>
                    <span class="week-text">Week 4</span>
                </a>
            </div> <?php ;
            break;
        case 5: ?>
            <div class="week-item week-5">
                <a class="week-link">
                    <span class="week-img"></span>
                    <span class="week-text">Week 5</span>
                </a>
            </div> <?php ;
            break;
        case 6: ?>
            <div class="week-item week-6">
                <a class="week-link">
                    <span class="week-img"></span>
                    <span class="week-text">Week 6</span>
                </a>
            </div> <?php ;
            break;
        case 7: ?>
            <div class="week-item week-7">
                <a class="week-link">
                    <span class="week-img"></span>
                    <span class="week-text">Week 7</span>
                </a>
            </div> <?php ;
            break;
        case 8: ?>
            <div class="week-item week-8">
                <a class="week-link">
                    <span class="week-img"></span>
                    <span class="week-text">Week 8</span>
                </a>
            </div> <?php ;
            break;
        case 9: ?>
            <div class="week-item week-9">
                <a class="week-link">
                    <span class="week-img"></span>
                    <span class="week-text">Week 9</span>
                </a>
            </div> <?php ;
            break;
        case 10: ?>
            <div class="week-item week-10">
                <a class="week-link">
                    <span class="week-img"></span>
                    <span class="week-text">Week 10</span>
                </a>
            </div> <?php ;
            break;
        default: ?>
            <div class="week-item week-1">
                <a class="week-link">
                    <span class="week-img"></span>
                    <span class="week-text">Week 1</span>
                </a>
            </div> <?php ;
    }   ?>
            <div class="time-remain">
                <h2 class="time-remain-title">Time remaining:</h2>


                <div class="countdown countdown-container ">
                    <div class="clock clock-row">

                        <!-- visually hide clock days -->
                        <div class="clock-item visually-hide clock-days countdown-time-value">
                            <div class="wrap">
                                <div class="inner">
                                    <div id="canvas-days" class="clock-canvas"></div>

                                    <div class="text">
                                        <p class="val">0</p>
                                        <p class="type-days type-time">DAYS</p>
                                    </div><!-- /.text -->
                                </div><!-- /.inner -->
                            </div><!-- /.wrap -->
                        </div><!-- /.clock-item -->

                        <div class="clock-item clock-hours countdown-time-value ">
                            <div class="wrap">
                                <div class="inner">
                                    <div id="canvas-hours" class="clock-canvas"></div>

                                    <div class="text">
                                        <p class="val">0</p>
                                        <p class="type-hours type-time">HOURS</p>
                                    </div><!-- /.text -->
                                </div><!-- /.inner -->
                            </div><!-- /.wrap -->
                        </div><!-- /.clock-item -->

                        <div class="clock-item clock-minutes countdown-time-value ">
                            <div class="wrap">
                                <div class="inner">
                                    <div id="canvas-minutes" class="clock-canvas"></div>

                                    <div class="text">
                                        <p class="val">0</p>
                                        <p class="type-minutes type-time">MINUTES</p>
                                    </div><!-- /.text -->
                                </div><!-- /.inner -->
                            </div><!-- /.wrap -->
                        </div><!-- /.clock-item -->

                        <div class="clock-item clock-seconds countdown-time-value">
                            <div class="wrap">
                                <div class="inner">
                                    <div id="canvas-seconds" class="clock-canvas"></div>

                                    <div class="text">
                                        <p class="val">0</p>
                                        <p class="type-seconds type-time">SECONDS</p>
                                    </div><!-- /.text -->
                                </div><!-- /.inner -->
                            </div><!-- /.wrap -->
                        </div><!-- /.clock-item -->
                    </div><!-- /.clock -->
                </div><!-- /.countdown-wrapper -->


            </div>

            <div class="game-nav">
                <div class="col-6">
                    <div class="game-nav-item game-fruit-ninja">
                        <div class="game-nav-logo"></div>
                        <div class="game-nav-title">Fruit Ninja</div>
                        <div class="game-nav-divider"></div>
                        <div class="game-nav-desc">Play the new game mode and score 20 points.</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="game-nav-item game-short-answer-question">
                        <div class="game-nav-logo"></div>
                        <div class="game-nav-title">Fruit Ninja</div>
                        <div class="game-nav-divider"></div>
                        <div class="game-nav-desc">Play the new game mode and score 20 points.</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="game-nav-item game-multiple-choice-quiz">
                        <div class="game-nav-logo"></div>
                        <div class="game-nav-title">Fruit Ninja</div>
                        <div class="game-nav-divider"></div>
                        <div class="game-nav-desc">Play the new game mode and score 20 points.</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="game-nav-item game-video">
                        <div class="game-nav-logo"></div>
                        <div class="game-nav-title">Fruit Ninja</div>
                        <div class="game-nav-divider"></div>
                        <div class="game-nav-desc">Play the new game mode and score 20 points.</div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <ul class="sitenav">
        <li class="sitenav-item sitenav-game-home"><a href="#"></a></li>
        <li class="sitenav-item sitenav-achievement"><a href="#"></a></li>
        <li class="sitenav-item sitenav-progress"><a href="#"></a></li>
        <li class="sitenav-item sitenav-reading-material"><a href="#"></a></li>
    </ul>
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


<script>
<?php
        if($dueTime != null) { ?>
            if((Date.parse(new Date()) - Date.parse(new Date("<?php echo $dueTime?>"))) <= 0) {
                $('.countdown').final_countdown({
                    start: new Date().getTime() / 1000,
                    end: Date.parse(new Date("<?php echo $dueTime?>"))/1000,
                    now: new Date().getTime() / 1000
                }, function() {
                    alert("Time is up!");
                });
            } else {

            }
<?php   } else { ?>
            newDue = new Date(Date.parse(new Date()) +  60 * 1000);

            $('.countdown').final_countdown({
                start: new Date().getTime() / 1000,
                end: Date.parse(newDue) / 1000,
                now: new Date().getTime() / 1000
            }, function() {
                alert("Time is up!");
            });

            var dd = newDue.getDate();
            var mm = newDue.getMonth() + 1;
            var yyyy = newDue.getFullYear();

            if(dd<10) {
                dd="0"+dd;
            }

            if(mm<10) {
                mm="0"+mm;
            }

            newDue = yyyy+"-"+mm+"-"+dd+ " " +newDue.getHours() + ":" + newDue.getMinutes()+":" + newDue.getSeconds();

            saveDueTime(<?php echo $studentID ?>, <?php echo $week ?>, newDue);
<?php	} ?>
</script>

</body>
</html>

