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

        //get student week
        $studentWeek = getStudentWeek($conn, $studentID);

        //get max week
        $maxWeek = getMaxWeek($conn)->WeekNum;

        //get quiz viewed attribute
        $quizViewedAttrs = getQuizViewdAttr($conn, $studentID);

        //get student question viewed attribute
        $studentQuesViewedAttrs = getStudentQuesViewedAttr($conn, $studentID);

    } catch(Exception $e) {
        if($conn != null) {
            db_close($conn);
        }

        debug_err($e);
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
    <title>Snap Change</title>
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
            padding: 60px 0 0 0;
            max-width: 1000px;
            margin: 0 auto;
        }
        .operation-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
        .operation-item {
            display: block;
            width: 150px;
            text-align: center;
            margin-bottom: 20px;
            font-size: 20px;
        }
        .operation-health-recipes {
            color: #a90505;
        }
        .operation-game {
            color: #00f8cd;
        }
        .operation-extra-activities {
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
            display: block;
            margin: 0 auto;
        }
        .achievement-score {
            font-size: 45px;
            margin: 30px 0 50px 0;
            text-align: center;
        }
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
        .week-locked .week-img {
            background-image: url("./img/locked_icon.png");
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
        .week-11 .week-img {
            background-image: url("./img/11_icon.png");
        }
        .week-12 .week-img {
            background-image: url("./img/12_icon.png");
        }
        .week-13 .week-img {
            background-image: url("./img/13_icon.png");
        }
        .week-14 .week-img {
            background-image: url("./img/14_icon.png");
        }
        .week-15 .week-img {
            background-image: url("./img/15_icon.png");
        }
        .week-more .week-img {
            background-image: url("./img/extra_week_icon.png");
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
        .report-container {
            margin-top: 40px;
            margin-bottom: 30px;
        }
        .report-title {
            width: 200px;
            text-align: center;
            margin: 0 auto 20px;
            color: #fcee2d;
        }
        .report-icon {
            cursor: pointer;
            width: 64px;
            height: 64px;
            margin: 0 auto;
            background-size: 100% 100%;
            background-image: url("./img/send_icon.png");
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
        <div class="week-content">
            <div class="week-carousel">
<?php
         for($i = 0; $i < $studentWeek; $i++) {
             if($i >= 15) { ?>
                <div class="week-item week-more">
<?php        } else { ?>
                <div class="week-item week-<?php echo ($i+1)?>">
<?php        } ?>
                    <a href="weekly-task.php?week=<?php echo ($i+1)?>" class="week-link">
                        <span class="week-img"></span>
                        <span class="week-text">Week <?php echo ($i+1)?></span>
                    </a>
                </div>
<?php    }

             for($i = $studentWeek; $i < $maxWeek; $i++) {  ?>
                 <div class="week-item week-locked">
                    <a class="week-link">
                        <span class="week-img"></span>
                        <span class="week-text">Week <?php echo ($i+1)?></span>
                    </a>
                </div>

<?php        } ?>

            </div>
        </div>

        <div class="main-content">
            <h2 class="achievement-score">Total Score:
                <span class="count"><?php echo $studentScore ?></span>
            </h2>

            <div class=" operation-container">
                <a href="#" class="operation-item operation-health-recipes">
                    <img src="./img/recipe_icon.png" alt="" class="operation-logo">
                    <span>Healthy Recipes</span>
                </a>
                <a href="#" class="operation-item operation-game">
                    <img src="./img/game_icon.png" alt="" class="operation-logo">
                    <span>Games</span>
                </a>
                <a href="extra-activities.php" class="operation-item operation-extra-activities">
                    <img src="./img/extra_activites_icon.png" alt="" class="operation-logo">
                    <span>Extra Activities</span>
                </a>
                <a href="progress.php" class="operation-item operation-progress">
                    <img src="./img/progress_icon.png" alt="" class="operation-logo">
                    <span>Progress</span>
                </a>
                <a href="reading-material.php" class="operation-item operation-reading-material">
                    <img src="./img/reading_material_icon.png" alt="" class="operation-logo">
                    <span>Reading Material</span>
                </a>
            </div>

            <div class="report-container">
                <div class="h5 report-title">Do you have a question? Send us a message.</div>
                <div class="report-icon"></div>
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

<script src="./js/snap.js"></script>
<script>
    $('.week-carousel').slick({
        centerMode: true,
        centerPadding: 0,
        infinite: true,
        initialSlide: <?php echo $studentWeek-1?>,
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

    $('.report-icon').on('click', function () {
        snap.showSendDialog({
            onConfirm: function (data) {
                console.log(data);

                var sendTime = new Date();

                var dd = sendTime.getDate();
                var mm = sendTime.getMonth() + 1;
                var yyyy = sendTime.getFullYear();

                if(dd<10) {
                    dd="0"+dd;
                }

                if(mm<10) {
                    mm="0"+mm;
                }

                sendTime = yyyy+"-"+mm+"-"+dd+ " " +sendTime.getHours() + ":" + sendTime.getMinutes()+":" + sendTime.getSeconds();

                $.ajax({
                    url: "student-question-feedback.php",
                    data: {
                        student_id: <?php echo $studentID?>,
                        subject: data.title,
                        content: data.content,
                        send_time: sendTime,
                        action: 'UPDATE'
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
        })
    })

    function parseFeedback(feedback) {
        if(feedback.message != "success"){
            snap.alert({
                content: 'Sorry. Please try again',
                onClose: function () { }
            });
        } else if(feedback.message == "success"){
            snap.alert({
                content: 'You have sent a message to the researcher. Please wait for reply',
                onClose: function () { }
            });
        }
    }
</script>

</body>
</html>

