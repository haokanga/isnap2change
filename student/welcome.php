<?php
	require_once('mysql-lib.php');
    require_once('debug.php');
    $pageName = "welcome";

    $conn = null;

    try {
        $conn = db_connect();

        //get students' rank
        $leaderboardRes = getStudentsRank($conn);

        //get fact topics
        $topicRes = getFactTopics($conn);

        //randomly select three topics
        $topicArr = array();

        foreach($topicRes as $singleTopic) {
            array_push($topicArr, $singleTopic->TopicID);
        }

        $randKeys = array_rand($topicArr, 3);

        //randomly select one fact from each topic
        $factRes = array();

        for($i = 0; $i < 3; $i++) {
            $factsRes = getFactsByTopicID($conn, $topicArr[$randKeys[$i]]);
            $randFactKey = array_rand($factsRes, 1);
            $factRes[$i] = $factsRes[$randFactKey];
        }
    } catch(Exception $e){
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

<html>
    <head>
        <title>SNAP</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="css/home.css" />

        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
        <script src="js/jquery-1.12.3.js"></script>
        <script src="js/bootstrap.js"></script>
        <script>
            $(document).ready(function () {
                $('#nav').affix({
                    offset: {
                        top: $('header').height() - $('#nav').height()
                    }
                });

                $('body').scrollspy({target: '#nav'});

                $('.scroll-top').click(function () {
                    $('body,html').animate({scrollTop: 0}, 1000);
                });

                /* smooth scrolling for nav sections */
                $('#nav .navbar-nav li>a').click(function () {
                    var link = $(this).attr('href');
                    var posi = $(link).offset().top;
                    $('body,html').animate({scrollTop: posi}, 100);
                });

            });

            function parseFeedback(response) {
                var feedback = JSON.parse(response);

                if(feedback.message != "success"){
                    alert(feedback.message + ". Please try again!");
                    return;
                }

                if(feedback.result == "valid"){
                    location.href = 'avatar.php';
                } else {
                    $('#login-fail-text').text("Invalid username or password!");
                }
            }

            function validStudent() {
                var username = document.getElementById("username").value;
                var password = document.getElementById("password").value;

                //send request
                var xmlhttp = new XMLHttpRequest();

                xmlhttp.onreadystatechange = function() {
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                        parseFeedback(xmlhttp.responseText);
                    }
                };

                xmlhttp.open("POST", "login.php", true);
                xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xmlhttp.send("username="+username+"&password="+password);
            }
			<!--Twitter widgets.js -->
			window.twttr = (function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0],
					  t = window.twttr || {};
			if (d.getElementById(id)) return t;
			js = d.createElement(s);
			js.id = id;
			js.src = "https://platform.twitter.com/widgets.js";
			fjs.parentNode.insertBefore(js, fjs);
			 
			t._e = [];
			t.ready = function(f) {
				t._e.push(f);
		    };
			 
			  return t;
			}(document, "script", "twitter-wjs"));
        </script>

    </head>
    <body>
		 <!--Facebook Page Plugin JavaScript SDK -->
		<div id="fb-root"></div>
		<script>(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.6&appId=909271749154924";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));</script>
	
        <header class="start" id="1">
            <div class="logo" style="display:flex;justify-content:center;align-items:center;width:100%;height:70%;">
                <img src="css/image/Snap_Logo_Inverted.png" alt="SNAP" style="width:50%;height:68%;">
            </div>
            <div class="tagline" style="color: white; font-size: 2em; display:flex;justify-content:center;align-items:center; margin-top:35px;">
                To Inspire A Healthier You.
            <!--    <input type="image" src="css/image/Refresh.png" name="saveForm" class="btTxt" id="scrollDown" style="border: none;" />  -->
            </div>
        </header>

         <nav class="navbar navbar-inverse navbar-static-top" id="nav">
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
                             <a href="#" data-toggle="modal" data-target="#myModal"><i class="glyphicon glyphicon-off"></i>LOGIN</a>
                         </li>
                     </ul>
                 </div>
             </div>
         </nav>

         <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="width:100%;">
             <div class="modal-dialog" role="document" style="height:90%;">
                 <div class="modal-content" style="height:90%;">
                     <div class="modal-body">
                         <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:white;"><span aria-hidden="true">&times;</span></button>
                         <div class="col-xs-6 col-xs-offset-3">
                             <img src="css/image/Snap_Logo_Inverted.png" style="height:20%; width: 100%;">
                                 <div>
                                     <span id="login-fail-text"></span>
                                 </div>
                                 <div class="input-group input-group-lg" style="margin-top:20%; text-align: center;">
                                     <input id="username" type="text" style="text-align: center; border-radius: 10px; color:yellow; border: none; background-color: black; opacity: 0.7;" class="form-control" placeholder="Username" aria-describedby="sizing-addon1">
                                 </div>
                                 <div class="input-group input-group-lg" style="margin-top:5%; text-align: center;">
                                     <input id="password" type="password" style="text-align: center; border-radius: 10px; border: none; color:yellow; background-color: black; opacity: 0.7;" class="form-control" placeholder="Password" aria-describedby="sizing-addon1">
                                 </div>
                             <button type="button" data-dismiss="modal" aria-label="Close" class="btn btn-primary btn-lg btn-block" style="margin-top:5%; border-radius: 10px; border-color: yellow !important; color:yellow; background-color: black; opacity: 0.7;" onclick="validStudent()">Log In</button>
                             <div style="text-align: center;">
                                 <span style="color: white;"> Don't have an account?</span>
                                 <a href='#' onclick="location.href = 'SignUp.html';" style='color:yellow;'>Sign Up</a>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
         </div>

        <div class="pg2" id="2">
            <div class="pg2_div" style="margin-top:5%;">
                <div class="col-xs-4 col-xs-offset-2">
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
                <div class="col-xs-4" style="background-color: black; padding-right:0px; padding-left:0px; padding-bottom:0px;">
                    <div class="scoreboard">
                        <h3 style="text-align:center; color:white;">SNAP Leaderboard </h3>
                        <table class="table table-hover" style="background-color:white;">					
		  <?php  for($i = 0; $i < count($leaderboardRes); $i++) { ?>
							<tr>
		  <?php			if($i == 0) { ?>
                                <td style="font-size:27px;">
                                    <strong> 1st </strong>
                                </td>		
		  <?php	   		} else if($i == 1) { ?>
                                <td style="font-size:21px;">
                                    <strong> 2nd </strong>
                                </td>
		  <?php	   		} else if($i == 2) { ?>					
                                <td style="font-size:19px;">
                                    <strong> 3rd </strong>
                                </td>
		  <?php		  	} else { ?>
								<td>
                                    4th
                                </td>
		 <?php		  	} ?>				
								<td> <?php echo $leaderboardRes[$i]->Username ?> </td>
								<td> <?php echo $leaderboardRes[$i]->Score ?> </td>
                            </tr>
		  <?php         } ?>
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
		<?php	for($i = 0; $i < 3; $i++) { ?>
                    <div class="col-xs-4 fact1">
                        <div class="row" style="margin-left:0px; margin-right:0px;">
                            <div class="col-xs-2">
								<?php 
									switch($factRes[$i]->TopicID) {
										case 1: 
											echo '<img src="css/image/Icon_Placeholder.png" style="">';
											break;
										case 2:
											echo  '<img src="css/image/Star_Icon.png" style="">';
											break;
										case 3:
											echo  '<img src="css/image/Star_Icon.png" style="">';
											break;
										case 4:
										    echo  '<img src="css/image/Star_Icon.png" style="">';
											break;
										default:
											echo  '<img src="css/image/Star_Icon.png" style="">';
											break;
								} ?>  
                            </div>
                            <div class="col-xs-9 col-xs-offset-1">
								<?php 
									if($i == 0) {
										echo '<h4 style="color:rgb(252,238,045); border: 0px solid rgb(252,238,045); border-bottom-color: rgb(252,238,045); border-bottom-width: 4px;">';
									}
									
									if($i == 1) {
										echo '<h4 style="color:rgb(54,232,197); border: 0px solid rgb(54,232,197); border-bottom-color: rgb(54,232,197); border-bottom-width: 4px;">';
									}
									
									if($i == 2) {
										echo ' <h4 style="color:rgb(247,117,030); border: 0px solid rgb(247,117,030); border-bottom-color: rgb(247,117,030); border-bottom-width: 4px;">';
									}									
								        echo strtoupper($factRes[$i]->TopicName)." FACT #".$factRes[$i]->FactID;
										echo '</h4>';
								?>
                            
                                <p style="color:white;"><?php echo $factRes[$i]->Content; ?></p>
                            </div>
                        </div>   
                    </div>
		<?php 	}  ?>			
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
						    <div class="fb-page" data-width="400" data-height="500" data-href="https://www.facebook.com/cpuresearch" data-tabs="timeline, messages" data-small-header="true" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true">
								<div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/cpuresearch"><a href="https://www.facebook.com/cpuresearch">CPU Research</a></blockquote></div>
							</div>
						
                    </div>
                    <div class="col-xs-4 social2" style="margin-top:4%; height:90%;">
                        <a class="twitter-timeline" data-width="400" data-height="500" href="https://twitter.com/CPUResearch" data-widget-id="732758309019607040">Tweets by @CPUResearch</a>
						<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
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
                <nav class="navbar navbar-inverse navbar-fixed-bottom">
                    <div class="container">
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
                </nav>
            </div>
        </div>
    </body>
</html>
