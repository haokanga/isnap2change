<?php
	require_once('connection.php');
	
	$materialPreSql = "SELECT COUNT(*) 
					   FROM   Learning_Material
					   WHERE  QuizID = ?";
							
	$materialPreQuery = $conn->prepare($materialPreSql);
	$materialPreQuery->execute(array($quizid));
	
?>

<html>
    <head>
        <title>SNAP</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="css/home.css" />
        <link rel="icon" href="http://example.com/favicon.png">

        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
        <script src="js/jquery-1.12.3.js"></script>
        <script src="js/bootstrap.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script>
            $(document).ready(function () {

                $('#nav').affix({
                    offset: {
                        top: $('header').height()
                    }
                });

                /* var topOfPg1 = ($("#1").offset().top) / 3;
                 
                 $(window).scroll(function () {
                 if ($(window).scrollTop() > topOfPg1)
                 {
                 $('html, body').animate({
                 scrollTop: $("#2").offset().top + 'px'
                 }, 1000, 'swing');
                 }
                 });  */
            });
        </script>

    </head>
    <body>
        <header class="masthead" id="1">
            <div class="start">
                <div class="logo" style="display:flex;justify-content:center;align-items:center;width:100%;height:70%;">
                    <img src="css/image/Snap_Logo_Inverted.png" alt="SNAP" style="width:50%;height:68%;">
                </div>
                <div class="tagline" style="color: white; font-size: 2em; display:flex;justify-content:center;align-items:center; margin-top:35px;">
                    To Inspire A Healthier You.
                </div>
            </div>
        </header>

        <!-- Page 2 -->

        <!-- Begin Navbar -->
        <div id="nav">
            <div class="navbar navbar-inverse navbar-static">
                <div class="container">
                    <!-- .btn-navbar is used as the toggle for collapsed navbar content -->
                    <a class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="glyphicon glyphicon-bar"></span>
                        <span class="glyphicon glyphicon-bar"></span>
                        <span class="glyphicon glyphicon-bar"></span>
                    </a>
                    <div class="navbar-collapse collapse">
                        <ul class="nav navbar-nav">
                            <li class="active">
                                <a class="navbar-brand" href="#">
                                    <img alt="Brand" src="css/image/Snap_Single_Wordform_White.png" style="height: 100%;">
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li><a href="#">Snap Facts</a></li>
                        </ul>
                        <ul class="nav pull-right navbar-nav">
                            <!-- <li>
                                 <form class="navbar-form">
                                     <input type="text" class="form-control" placeholder="Search">
                                     <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i></button>
                                 </form>
                             </li> -->
                            <li>
                                <a href="#"><i class="glyphicon glyphicon-off"></i> LOGIN</a>
                            </li>
                        </ul>
                    </div>		
                </div>
            </div><!-- /.navbar -->
        </div>

        <div class="pg2" id="2">
            <div class="pg2_div">
                <div class="col-xs-4 col-xs-offset-2" style="margin-top:3%;">
                    <div class="panel" style="background-color:black; border-radius:30px;">
                        <div class="panel-body" style="padding: 0px;">
                            <div class="col-xs-2">
                                <img src="css/image/Star_Icon.png" style="margin-top:40%;">
                            </div>
                            <div class="col-xs-9 col-xs-offset-1">
                                <h3 style="color: white;"> Achievement of the week </h3>
                                <h4 style="color:rgb(54,232,197); border: 0px solid rgb(54,232,197); border-bottom-color: rgb(54,232,197); border-bottom-width: 4px;">
                                    Perfect Attendance</h4>
                                <p style="color:white;">Log in every day for the entire SNAP Program to unlock this achievement </p>
                            </div>
                        </div>
                    </div>


                    <div class="game" style="margin-top:13%;">
                        <div class="panel" style="background-color: rgba(0,0,0,0); border-radius:30px;">
                            <div class="panel-body" style="padding:0px;">
                                <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                                    <!-- Indicators -->
                                    <ol class="carousel-indicators">
                                        <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                                        <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                                        <li data-target="#carousel-example-generic" data-slide-to="2"></li>
                                    </ol>

                                    <!-- Wrapper for slides -->
                                    <div class="carousel-inner" role="listbox">
                                        <div class="item active">
                                            <img src="css/image/Temple.png" alt="..." style="border-radius:30px;">
                                            <div class="carousel-caption">
                                                <h4> <u>TEMPLE RUN HIGH SCORE</u> </h4>
                                                <p style="font-size: 50px;">
                                                    78,000
                                                </p>
                                            </div>
                                        </div>
                                        <div class="item">
                                            <img src="css/image/Fruit.png" alt="..." style="border-radius:30px;">
                                            <div class="carousel-caption">
                                                <h4><u> FRUIT NINJA HIGH SCORE </u></h4>
                                                <p style="font-size: 50px;">
                                                    1234
                                                </p>
                                            </div>
                                        </div>
                                        <div class="item">
                                            <img src="css/image/Angry.png" alt="..." style="border-radius:30px;">
                                            <div class="carousel-caption">
                                                <h4><u> ANGRY BIRDS HIGH SCORE</u> </h4>
                                                <p style="font-size: 50px;">
                                                    1200
                                                </p>
                                            </div>
                                        </div>
                                        <div class="item">
                                            <img src="css/image/Candy.png" alt="..." style="border-radius:30px;">
                                            <div class="carousel-caption">
                                                <h4> <u>CANDY CRUSH HIGH SCORE</u> </h4>
                                                <p style="font-size: 50px;">
                                                    34556
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Controls -->
                                    <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                                        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </div> 
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-xs-4" style="background-color: black; margin-top:3%; padding-right:0px; padding-left:0px; padding-bottom:0px;">
                    <div class="scoreboard">
                        <h3 style="text-align:center; color:white;">SNAP Leaderboard </h3>
                        <table class="table table-hover" style="background-color:white;">
                            <tr>
                                <td style="font-size:27px;">
                                    <strong> 1st </strong>
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size:21px;">
                                    <strong>    2nd </strong>
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size:19px;">
                                    <strong>  3rd </strong>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    4th
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    5th
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    6th
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    7th
                                </td>
                            </tr><tr>
                                <td>
                                    8th
                                </td>
                            </tr><tr>
                                <td>
                                    9th
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    10th
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page 3 div -->
        <div class="pg3">
            <div class="pg3_heading" style="display:flex;justify-content:center;align-items:center;width:100%;height:20%; font-family:'Museo Sans';">
                <div style="width:50%;color:yellow; text-align:center; margin-top:2%;">
                    <span style="font-weight:700; font-size:30px;">FACTS</span>
                    <br>
                    <span style="font-size:18px;">of the</span>
                    <br>
                    <span style="font-weight:700; font-size:30px;">WEEK</span>
                </div>
            </div> 
            <div class="facts" style="width:100%; margin-top:3%;">
                <div class="row" style="margin-left:0px; margin-right:0px;">
                    <div class="col-xs-4 fact1">
                        <div class="row" style="margin-left:0px; margin-right:0px;">
                            <div class="col-xs-2">
                                <img src="css/image/Icon_Placeholder.png" style="">
                            </div>
                            <div class="col-xs-9 col-xs-offset-1">
                                <h4 style="color:rgb(252,238,045); border: 0px solid rgb(252,238,045); border-bottom-color: rgb(252,238,045); border-bottom-width: 4px;">
                                    SMOKING FACT #23</h4>
                                <p style="color:white;">Tobacco smoking is one of the largest causes of preventable illness and death in Australia. Research estimates that two in three lifetime smokers will die from a disease caused by their smoking. The most recent estimate of deaths caused by tobacco in Australia is for the financial year 2004–05. Tobacco use caused a total of 14,901 deaths in that year.  </p>
                            </div>
                        </div>   

                    </div>
                    <div class="col-xs-4 fact2">
                        <div class="row" style=" margin-left:0px; margin-right:0px;">
                            <div class="col-xs-2">
                                <img src="css/image/Star_Icon.png" style="">
                            </div>
                            <div class="col-xs-9 col-xs-offset-1">
                                <h4 style="color:rgb(54,232,197); border: 0px solid rgb(54,232,197); border-bottom-color: rgb(54,232,197); border-bottom-width: 4px;">
                                    DRUG FACT #15</h4>
                                <p style="color:white;">Tobacco smoking is one of the largest causes of preventable illness and death in Australia. Research estimates that two in three lifetime smokers will die from a disease caused by their smoking. The most recent estimate of deaths caused by tobacco in Australia is for the financial year 2004–05. Tobacco use caused a total of 14,901 deaths in that year.  </p>

                            </div>
                        </div>   

                    </div>
                    <div class="col-xs-4 fact3">
                        <div class="row" style="margin-left:0px; margin-right:0px;">
                            <div class="col-xs-2">
                                <img src="css/image/Star_Icon.png" style="">
                            </div>
                            <div class="col-xs-9 col-xs-offset-1">
                                <h4 style="color:rgb(247,117,030); border: 0px solid rgb(247,117,030); border-bottom-color: rgb(247,117,030); border-bottom-width: 4px;">
                                    NUTRITION FACT #9</h4>
                                <p style="color:white;">Tobacco smoking is one of the largest causes of preventable illness and death in Australia. Research estimates that two in three lifetime smokers will die from a disease caused by their smoking. The most recent estimate of deaths caused by tobacco in Australia is for the financial year 2004–05. Tobacco use caused a total of 14,901 deaths in that year.  </p>

                            </div>
                        </div>   

                    </div>
                </div>
            </div>
            <div class="pg3_footer" style="display:flex;justify-content:center;align-items:center;width:100%;height:20%; margin-top:3%; font-family:'Museo Sans';">
                <div style="width:50%;color:yellow; text-align:center;">
                    <span style="font-size:13px;">Want to know more?</span>
                    <br>
                    <span style="font-weight:700; font-size:30px;">
                        <img src="css/image/Icon_Placeholder.png" style="">
                    </span>
                </div>
            </div> 
        </div>

        <!-- Page 4 -->
        <div class="pg4">           
            <div class="social" style="width:100%; height:100%;">
                <div class="row" style="margin-left:0px; margin-right:0px; align-content:center; height:100%;">
                    <div class="col-xs-4 col-xs-offset-2 social1" style="margin-top:4%; height:90%;">
                        <img src="css/image/fbk.png" style="height: 90%; width:95%;">
                    </div>
                    <div class="col-xs-4 social2" style="margin-top:4%; height:90%;">
                        <img src="css/image/insta.png" style="height: 90%; width:95%;">
                    </div>
                </div>
            </div>
        </div>

        <!-- Page 5 -->
        <div class="pg5">           
            <div class="celebrity" style="width:100%; height:100%;">
                <div class="col-xs-8 col-xs-offset-2 celeb1" style="margin-top:4%; height: 38%;">
                    <div class="row" style="height:100%;">
                        <div class="col-xs-4" style="height:100%;">
                            <img src="css/image/insta.png" style="width:100%; height:100%;">
                        </div>
                        <div class="col-xs-8" style="color:white;">
                            <h4 style="color:yellow; border: 0px solid yellow; border-bottom-color: yellow; border-bottom-width: 2px;">
                                Celebrity #1</h4>
                            Tobacco smoking is one of the largest causes of preventable illness and death in Australia. Research estimates that two in three lifetime smokers will die from a disease caused by their smoking. The most recent estimate of deaths caused by tobacco in Australia is for the financial year 2004–05. Tobacco use caused a total of 14,901 deaths in that year.
                        </div>
                    </div>
                </div>
                <div class="col-xs-8 col-xs-offset-2 celeb2" style="margin-top:2%; height:38%">
                    <div class="row" style="height:100%;">
                        <div class="col-xs-4" style="height:100%;">
                            <img src="css/image/fbk.png" style="width:100%; height: 100%;">
                        </div>
                        <div class="col-xs-8" style="color:white;">
                            <h4 style="color:yellow; border: 0px solid yellow; border-bottom-color: yellow; border-bottom-width: 2px;">
                                Celebrity #2</h4>
                            Tobacco smoking is one of the largest causes of preventable illness and death in Australia. Research estimates that two in three lifetime smokers will die from a disease caused by their smoking. The most recent estimate of deaths caused by tobacco in Australia is for the financial year 2004–05. Tobacco use caused a total of 14,901 deaths in that year.
                        </div> 
                    </div>
                </div>
            </div>
        </div>

        <!-- Page 6 -->
        <div class="pg6">           
            <div class="contact" style="width:100%; height:100%;">
                <div class="col-xs-6 col-xs-offset-1 contact1" style="margin-top:3%; height: 68%; color:white;">
                    Any questions or comments?
                    <br> Please contact us and we will reach out to you shortly.
                    <br>
                    <br>
                    <form>
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="myName" placeholder="Name">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="emailID" placeholder="Email">
                        </div>     
                        <textarea class="form-control" rows="4"></textarea>
                    </form>
                    <div class="sendbutton" style="display:flex;justify-content:center;align-items:center;width:100%;height:20%; margin-top:1%;">
                        <div style="width:50%;color:yellow; text-align:center;">
                            <span>
                                <img src="css/image/Icon_Placeholder.png" style="height: 64px; width: 64px;">
                            </span>
                        </div>
                    </div> 
                </div>
                <div class="col-xs-3 col-xs-offset-1 contact2" style="margin-top:3%; height:68%">                  
                    <div class="logo" style="height:35%;">
                        <img src="css/image/Snap_Logo_Inverted.png" style="width:100%; height: 100%;">
                    </div>
                    <div class="snaptext" style="color:white; margin-top:3%;">
                        Tobacco smoking is one of the largest causes of preventable illness and death in Australia. Research estimates that two in three lifetime smokers will die from a disease caused by their smoking. The most recent estimate of deaths caused by tobacco in Australia is for the financial year 2004–05.
                    </div> 
                </div>
                <div class="refreshButton" style="display:flex;justify-content:center;align-items:center;width:100%; margin-top:4%;">
                    <div style="width:50%;color:yellow; text-align:center;">
                        <span>
                            <img src="css/image/Refresh.png" style="height: 32px; width: 32px;">
                        </span>
                        <br>
                        <span style="font-size:13px;">Back to Top</span>
                    </div>
                </div> 
                <div class="footer">
                    <div class="containter">
                        <div class="navbar-collapse collapse">
                        <ul class="nav navbar-nav">
                            <li class="active">
                                <a class="navbar-brand" href="#">
                                    <img alt="Brand" src="css/image/Snap_Logo_Inverted.png" style="height: 100%;">
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li><span class="navbar-text navbar-right">Legal stuff - All rights reserved</span></li>
                        </ul>
                       
                    </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
