<?php

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
        .snap-facts-logo {
            display: block;
            width: 200px;
            height: 130px;
            margin: 20px auto;
            background-size: 100% 100%;
            background-image: url("./img/snap_facts_icon.png");
        }
        .snap-facts-desc {
            width: 400px;
            margin: 0 auto 20px;
            text-align: center;
        }
        .snap-facts-all {
            width: 800px;
            margin: 0 auto;
            padding-bottom: 20px;
            text-align: center;
        }
        .snap-facts-list {
            flex-wrap: wrap;
            display: flex;
            justify-content: center;
        }
        .snap-facts-item {
            margin: 0 10px 20px;
            /*float: left;*/
        }
        .snap-facts-link {
            display: block;
            color: inherit;
            width: 128px;
        }
        .snap-facts-item-name {
            display: block;
            height: 60px;
        }
        .snap-facts-item-logo {
            display: block;
            width: 128px;
            height: 128px;
            background-size: 100% 100%;
        }
        .snap-facts-item-smoking {
            color: #fcee2d;
        }
        .snap-facts-item-nutrition {
            color: #f7751e;
        }
        .snap-facts-item-alcohol {
            color: #93c;
        }
        .snap-facts-item-physical {
            color: #db1b1b;
        }
        .snap-facts-item-health {
            color: #db1b1b;
        }
        .snap-facts-item-sexual {
            color: #af24d1;
        }
        .snap-facts-item-drugs {
            color: #2fedc9;
        }


        .week-facts {
            max-width: 1000px;
            margin: 20px auto 20px;
            text-align: center;
        }
        .week-facts-title {
            color: #fcee2d;
            margin-bottom: 20px;
        }
        .week-facts-item {
            width: 33.33%;
            padding: 0 10px;
            float: left;
            margin-bottom: 20px;
        }

        .week-facts-item-smoking .week-facts-name{
            color: #fcee2d;
        }
        .week-facts-item-nutrition .week-facts-name{
            color: #f7751e;
        }
        .week-facts-item-alcohol .week-facts-name{
            color: #93c;
        }
        .week-facts-item-physical .week-facts-name{
            color: #db1b1b;
        }
        .week-facts-item-health .week-facts-name{
            color: #db1b1b;
        }
        .week-facts-item-sexual .week-facts-name{
            color: #af24d1;
        }
        .week-facts-item-drugs .week-facts-name{
            color: #2fedc9;
        }

        .week-facts-icon {
            display: block;
            width: 128px;
            height: 128px;
            background-size: 100% 100%;
            margin: 0 auto;
        }
        .week-facts-name {
            border-bottom: 2px solid;
            margin-bottom: 20px;
            display: block;
            font-size: 24px;
        }
        .week-facts-intro {
            color: #fff;
            font-size: 20px;
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
                <li class="nav-item"><a  class="nav-link" href="http://taobao.com">GAME HOME</a></li>
                <li class="nav-item"><a  class="nav-link" href="http://taobao.com">Snap Facts</a></li>
                <li class="nav-item"><a  class="nav-link" href="http://taobao.com">Resources</a></li>
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

        <div class="snap-facts-container">
            <div class="snap-facts-header">
                <a href="#" class="snap-facts-logo"></a>
                <div class="snap-facts-desc p1">
                    Snap is all baout providing information. <br/> Pick your category to start finding out more.
                </div>
            </div>

            <div class="snap-facts-all">
                <ul class="snap-facts-list">
                    <li class="snap-facts-item snap-facts-item-smoking">
                        <a href="snap-facts-detail.php?topic_id=1" class="snap-facts-link">
                            <span class="snap-facts-item-logo image-icon-smoking"></span>
                            <span class="snap-facts-item-name h4">Smoking</span>
                        </a>
                    </li>

                    <li class="snap-facts-item snap-facts-item-nutrition">
                        <a href="snap-facts-detail.php?topic_id=2" class="snap-facts-link">
                            <span class="snap-facts-item-logo image-icon-nutrition"></span>
                            <span class="snap-facts-item-name h4" >Nutirtion</span>
                        </a>
                    </li>
                    <li class="snap-facts-item snap-facts-item-alcohol">
                        <a href="snap-facts-detail.php?topic_id=3" class="snap-facts-link">
                            <span class="snap-facts-item-logo image-icon-alcohol"></span>
                            <span class="snap-facts-item-name h4" >Alcohol</span>
                        </a>
                    </li>
                    <li class="snap-facts-item snap-facts-item-physical">
                        <a href="snap-facts-detail.php?topic_id=4" class="snap-facts-link">
                            <span class="snap-facts-item-logo image-icon-physical"></span>
                            <span class="snap-facts-item-name h4" >Physical</span>
                        </a>
                    </li>
                    <li class="snap-facts-item snap-facts-item-health">
                        <a href="snap-facts-detail.php?topic_id=8" class="snap-facts-link">
                            <span class="snap-facts-item-logo image-icon-health"></span>
                            <span class="snap-facts-item-name h4" >Health and Wellbeing</span>
                        </a>
                    </li>
                    <li class="snap-facts-item snap-facts-item-sexual">
                        <a href="snap-facts-detail.php?topic_id=7" class="snap-facts-link">
                            <span class="snap-facts-item-logo image-icon-sexual"></span>
                            <span class="snap-facts-item-name h4" >Sexual Health</span>
                        </a>
                    </li>
                    <li class="snap-facts-item snap-facts-item-drugs">
                        <a href="snap-facts-detail.php?topic_id=6" class="snap-facts-link">
                            <span class="snap-facts-item-logo image-icon-drugs"></span>
                            <span class="snap-facts-item-name h4" >Drugs</span>
                        </a>
                    </li>



                </ul>
            </div>
        </div>

        <div class="week-facts">
            <h2 class="week-facts-title h1">Facts of the Week</h2>
            <div class="week-facts-content">
                <div class="week-facts-list">
                    <div class="clearfix">

                        <div class="week-facts-item week-facts-item-smoking">
                            <a href="" class="week-facts-divnk">
                                <span class="week-facts-icon image-icon-smoking"></span>
                                <span class="week-facts-name">Smoking FAct #23</span>
                                    <span class="week-facts-intro">
                                        According to Quitline, rates of smoking amongst young people have never been lower. The most recent data (2014) finds that 5.1 percent of 12 to 17 year olds in Australia are current smokers (have smoked in the past seven days).
                                    </span>
                            </a>
                        </div>

                        <div class="week-facts-item week-facts-item-drugs">
                            <a href="" class="week-facts-divnk">
                                <span class="week-facts-icon image-icon-drugs"></span>
                                <span class="week-facts-name">Smoking FAct #23</span>
                                    <span class="week-facts-intro">
                                        According to Quitline, rates of smoking amongst young people have never been lower. The most recent data (2014) finds that 5.1 percent of 12 to 17 year olds in Australia are current smokers (have smoked in the past seven days).
                                    </span>
                            </a>
                        </div>

                        <div class="week-facts-item week-facts-item-health">
                            <a href="" class="week-facts-divnk">
                                <span class="week-facts-icon image-icon-health"></span>
                                <span class="week-facts-name">Smoking FAct #23</span>
                                    <span class="week-facts-intro">
                                        According to Quitline, rates of smoking amongst young people have never been lower. The most recent data (2014) finds that 5.1 percent of 12 to 17 year olds in Australia are current smokers (have smoked in the past seven days).
                                    </span>
                            </a>
                        </div>
                    </div>

                    <div class="clearfix">

                        <div class="week-facts-item week-facts-item-smoking">
                            <a href="" class="week-facts-divnk">
                                <span class="week-facts-icon image-icon-smoking"></span>
                                <span class="week-facts-name">Smoking FAct #23</span>
                                    <span class="week-facts-intro">
                                        According to Quitline, rates of smoking amongst young people have never been lower. The most recent data (2014) finds that 5.1 percent of 12 to 17 year olds in Australia are current smokers (have smoked in the past seven days).
                                    </span>
                            </a>
                        </div>

                        <div class="week-facts-item week-facts-item-smoking">
                            <a href="" class="week-facts-divnk">
                                <span class="week-facts-icon image-icon-smoking"></span>
                                <span class="week-facts-name">Smoking FAct #23</span>
                                    <span class="week-facts-intro">
                                        According to Quitline, rates of smoking amongst young people have never been lower. The most recent data (2014) finds that 5.1 percent of 12 to 17 year olds in Australia are current smokers (have smoked in the past seven days).
                                    </span>
                            </a>
                        </div>

                        <div class="week-facts-item week-facts-item-smoking">
                            <a href="" class="week-facts-divnk">
                                <span class="week-facts-icon image-icon-smoking"></span>
                                <span class="week-facts-name">Smoking FAct #23</span>
                                    <span class="week-facts-intro">
                                        According to Quitline, rates of smoking amongst young people have never been lower. The most recent data (2014) finds that 5.1 percent of 12 to 17 year olds in Australia are current smokers (have smoked in the past seven days).
                                    </span>
                            </a>
                        </div>
                    </div>

                </div>
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

</script>
</body>
</html>

