<?php
    //check login status
    require_once('./student-validation.php');

    require_once("../mysql-lib.php");
    require_once("../debug.php");
    $pageName = "game-home";

    $conn = null;

    try {
        $conn = db_connect();

        //get student score
        $studentScore = getStudentScore($conn, $studentID);



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
    <title>Document</title>
    <link rel="stylesheet" href="./css/common.css">
    <link rel="stylesheet" href="./css/vendor/slick.css">
    <link rel="stylesheet" href="./css/vendor/slick-theme.css">
    <link href='https://fonts.googleapis.com/css?family=Maitree|Lato:400,900' rel='stylesheet' type='text/css'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.min.js"></script>
    <style>

        /**
         * main.html
         **/
        .main-content {
            padding: 30px 0 0 0;
            max-width: 1000px;
            margin: 0 auto;
        }
        .operation-item {
            display: block;
            width: 100%;
            text-align: center;
            margin-bottom: 10px;
            font-size: 20px;
        }
        .operation-game {
            color: #00f8cd;
        }
        .operation-achievements {
            color: #faf600;
        }
        .operation-progress {
            color: #ff6500;
        }
        .operation-reading-material {
            color: #a90505;
        }
        .operation-logo {
            width: 128px;
            height: 128px;
        }
        .achievement-score {
            font-size: 45px;
            margin: 30px 0 50px 0;
            text-align: center;
        }
        .achievement-showcase h3 {
            font-size: 14px;
            text-align: center;
            margin-bottom: 20px;
        }
        .achievement-list {
            text-align: center;
        }
        .achievement-item {
            display: inline-block;
            margin:  0 10px;
        }
        .achievement-link {
            display: block;
        }
        .achievement-logo {
            width: 128px;
            height: 128px;
        }

        /**
         * $周轮播
         **/
        .week-content {
            max-width: 1000px;
            margin: 0 auto;
            position: relative;
            padding-top: 20px;
        }
        .week-content .slick-slider {
            margin-top: 3%;
            padding: 0 20px;
            margin-bottom: 0;
        }
        .week-content .carousel-arrow {
            position: absolute;
            cursor: pointer;
            top: 44px;
            width: 32px;
            height: 32px;
            left: -30px;
            background-size: 100% 100%;
            background-image: url("./img/direction_icon.png");
        }
        .week-content .carousel-prev {
            transform: rotate(180deg);
        }
        .week-content .carousel-next {
            right: -30px;
            left: auto;
        }
        .week-content .slick-dots {
            bottom: -30px;
        }
        .week-item {
            width: 154px;
            height: 154px;
            display: inline-block;
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


        .week-content .slick-dots li {
            width: 16px;
            height: 16px;
            background-color: #a0a09f;
            border-radius: 50%;
            text-align: -999rem;
        }
        .week-content .slick-dots button:before {
            display: none;
        }
        .week-content .slick-dots .slick-active {
            background-color: #faf400;
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
                <span class="setting-text"><?php echo $studentUsername?></span>
            </a>
        </div>
    </div>

    <div class="page-content ">
        <div class="week-content">
            <div class="week-carousel">
                <div class="week-item week-1">
                    <a href="#" class="week-link">
                        <span class="week-img"></span>
                        <span class="week-text">Week 1</span>
                    </a>
                </div>
                <div class="week-item week-2">
                    <a href="#" class="week-link">
                        <span class="week-img"></span>
                        <span class="week-text">Week 2</span>
                    </a>
                </div>
                <div class="week-item week-3">
                    <a href="#" class="week-link">
                        <span class="week-img"></span>
                        <span class="week-text">Week 3</span>
                    </a>
                </div>
                <div class="week-item week-4">
                    <a href="#" class="week-link">
                        <span class="week-img"></span>
                        <span class="week-text">Week 4</span>
                    </a>
                </div>
                <div class="week-item week-5">
                    <a href="#" class="week-link">
                        <span class="week-img"></span>
                        <span class="week-text">Week 5</span>
                    </a>
                </div>
                <div class="week-item week-6">
                    <a href="#" class="week-link">
                        <span class="week-img"></span>
                        <span class="week-text">Week 6</span>
                    </a>
                </div>

            </div>
        </div>
        <div class="main-content">
            <div class="mini-row">
                <div class="col-4">
                    <div class="mini-row">
                        <div class="col-6">
                            <a href="#" class="operation-item operation-game">
                                <img src="./img/game_icon.png" alt="" class="operation-logo">
                                <span>Games</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="#" class="operation-item operation-achievements">
                                <img src="./img/achievement_logo.png" alt="" class="operation-logo">
                                <span>Achievements</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="#" class="operation-item operation-progress">
                                <img src="./img/progress_icon.png" alt="" class="operation-logo">
                                <span>Progress</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="#" class="operation-item operation-reading-material">
                                <img src="./img/reading_material_icon.png" alt="" class="operation-logo">
                                <span>Reading Material</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-8">
                    <h2 class="achievement-score">Total Score:
                        <span class="count"><?php echo $studentScore?></span>
                    </h2>
                    <div class="achievement-showcase">
                        <h3>Achievement Showcase</h3>
                        <ul class="achievement-list">
                            <li class="achievement-item">
                                <a href="#" class="achievement-link">
                                    <img src="./img/achievement_logo.png" class="achievement-logo">
                                </a>
                            </li>
                            <li class="achievement-item">
                                <a href="#" class="achievement-link">
                                    <img src="./img/achievement_logo.png" class="achievement-logo">
                                </a>
                            </li>
                            <li class="achievement-item">
                                <a href="#" class="achievement-link">
                                    <img src="./img/achievement_logo.png" class="achievement-logo">
                                </a>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
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
    $('.week-carousel').slick({
        centerMode: true,
        centerPadding: 0,
        infinite: true,
        slidesToShow: 5,
        slidesToScroll: 1,
        dots: true,
        speed: 200,
        prevArrow: '<span class="carousel-arrow carousel-prev"></span>',
        nextArrow: '<span class="carousel-arrow carousel-next"></span>'
    });

    $('.count').each(function () {
        $(this).prop('Counter',0).animate({
            Counter: $(this).text()
        }, {
            duration: 1000,
            easing: 'swing',
            step: function (now) {
                $(this).text(Math.ceil(now));
            }
        });
    });
</script>

</body>
</html>
