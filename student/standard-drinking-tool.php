<?php
    //check login status
    require_once('student-validation.php');

    require_once("../mysql-lib.php");
    require_once("../debug.php");

    $pageName = "standard-drinking-tool";

    //check whether a request is GET or POST
    if($_SERVER["REQUEST_METHOD"] == "GET"){
        if(isset($_GET["quiz_id"])){
            $quizID = $_GET["quiz_id"];
        } else{

        }
    } else {

    }

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
    <title>Standard Drinking Tool</title>
    <link rel="stylesheet" href="./css/common.css">

    <link href='https://fonts.googleapis.com/css?family=Maitree|Lato:400,900' rel='stylesheet' type='text/css'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

    <script src="./js/snap.js"></script>
    <style>
        .drink-header {
            padding: 30px 0;
        }
        .drinking-tool-title {
            text-align: center;
            padding-bottom: 10px;
        }
        .drink-prompt {
            width: 400px;
            margin: 0 auto;
            text-align: center;
        }

        .drink-content {
            width: 1200px;
            margin: 0 auto;
        }

        .drink-tool-container {
            float: left;
            width: 700px;
        }
        .drink-tool-content {
            width: 700px;
            min-height: 370px;
            margin: 0 auto 10px;
            background-color: #fff;
            color: #000;
        }
        .drink-tool-prompt {
            text-align: center;
        }
        .drink-question-container {
            width: 500px;
            float: left;
            padding-left: 30px;
        }
        .task-operation {
            right: 250px;
        }

    </style>

    <style>
        /**
         * standard drinking tool
         **/
        .drink-tool-container h1 {
            color: #f58026;
            margin: 10px;
        }
        .btn {
            padding: 13px 20px;
            border-radius: 5px;
        }
        .sr-only {
            display: none;
        }
        .ModATAStandardDrinkToolC {
        }
        body .drink-types-wrapper {
            margin-bottom: 0;
        }
        body .drink-wrapper {
            margin-bottom: 0;
        }
        body #standard-drink-tool-wrapper {
            margin-bottom: 0;
        }
    </style>
</head>
<body>

<div class="page-wrapper">
    <div class="header-wrapper">
        <div class="header">
            <a class="home-link">SNAP</a>

            <div class="settings">
                <div class="setting-icon dropdown">
                    <ul class="dropdown-menu">
                        <li class="dropdown-item"><a href="setting.php">Setting</a></li>
                        <li class="dropdown-item"><a href="logout.php">Logout</a></li>
                    </ul>
                </div>
                <a class="setting-text"><?php echo $studentUsername?></a>
            </div>
        </div>
    </div>


    <div class="content-wrapper ">

        <div class="drink-header">
            <h2 class="drinking-tool-title h2">Standard Drinking Tool</h2>
            <div class="p1 drink-prompt">Use the standard drinking tool to answer the questions below.</div>
        </div>

        <div class="drink-content clearfix" >

            <div class="drink-tool-container">
                <div class="drink-tool-content">


                    <link href="./DesktopModules/ATAStandardDrinkTool/css/standard-drinks-tool.css" type="text/css" rel="stylesheet"/>
                    <script src="./DesktopModules/ATAStandardDrinkTool/js/jquery-ui.js" type="text/javascript"></script>
                    <script>
                        gtmDataLayer = [];
                    </script>
                    <div class="col-inner">
                        <h1>Standard Drink Tool</h1>
                        <div id="dnn_contentPane" class="contentPane"><div class="DnnModule DnnModule-DNN_HTML DnnModule-624"><a name="624"></a>
                                <div class="DNNContainer_noTitle">
                                    <div class="standard-drinks-tool-intro">
                                        <div class="sdt-wrap clearfix">
                                            <img alt="Standard Drink Tool" src="./DesktopModules/ATAStandardDrinkTool/images/standard-drinks/img-glasses.png" />
                                            <div class="std-drink-tool-banner">
                                                <h3 class="std-drink-tool-heading">Do you know what a standard drink looks like?</h3>
                                                <a class="btn begin-sd-tool">Pour a standard drink</a>
                                            </div>
                                        </div>
                                    </div><!-- End_Module_624 -->
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div><div class="DnnModule DnnModule-ATAStandardDrinkTool DnnModule-626"><a name="626"></a>
                            <div class="DNNContainer_noTitle">
                                <div id="dnn_ctr626_ContentPane"><!-- Start_Module_626 --><div id="dnn_ctr626_ModuleContent" class="DNNModuleContent ModATAStandardDrinkToolC clearfix">
                                        <!--CDF(Css|/DesktopModules/ATAStandardDrinkTool/css/standard-drinks-tool.css?cdv=48)-->

                                        <!-- DRINK TOOL WRAPPER -->
                                        <div id="standard-drink-tool-wrapper" style="display: none;">
                                            <!-- STEP 1 -->
                                            <div class="drink-containers-wrapper">
                                                <div class="flexslider carousel">
                                                    <ul class="drink-containers slides"><li>
                                                            <div class="container-thumb">
                                                                <div class="container-tick "><span class="sr-only">selected</span></div>
                                                                <img class="container-image" alt="Drink Image" src="./DesktopModules/ATAStandardDrinkTool/images/standard-drinks/thumb-large-wine-glass.jpg">
                                                                <div class="container-title">Large Wine Glass</div>
                                                                <div class="container-capacity">350ml</div>
                                                                <input type="radio" name="drinkContainer[]" class="radioDrinkContainer" value="0">
                                                            </div>
                                                        </li><li>
                                                            <div class="container-thumb">
                                                                <div class="container-tick "><span class="sr-only">selected</span></div>
                                                                <img class="container-image" alt="Drink Image" src="./DesktopModules/ATAStandardDrinkTool/images/standard-drinks/thumb-small-wine-glass.jpg">
                                                                <div class="container-title">Small Wine Glass</div>
                                                                <div class="container-capacity">300ml</div>
                                                                <input type="radio" name="drinkContainer[]" class="radioDrinkContainer" value="1">
                                                            </div>
                                                        </li><li>
                                                            <div class="container-thumb">
                                                                <div class="container-tick "><span class="sr-only">selected</span></div>
                                                                <img class="container-image" alt="Drink Image" src="./DesktopModules/ATAStandardDrinkTool/images/standard-drinks/thumb-fortified-wine-glass.jpg">
                                                                <div class="container-title">Fortified Wine Glass</div>
                                                                <div class="container-capacity">100ml</div>
                                                                <input type="radio" name="drinkContainer[]" class="radioDrinkContainer" value="2">
                                                            </div>
                                                        </li><li>
                                                            <div class="container-thumb">
                                                                <div class="container-tick "><span class="sr-only">selected</span></div>
                                                                <img class="container-image" alt="Drink Image" src="./DesktopModules/ATAStandardDrinkTool/images/standard-drinks/thumb-sparkling-wine-glass.jpg">
                                                                <div class="container-title">Sparkling Wine Glass</div>
                                                                <div class="container-capacity">180ml</div>
                                                                <input type="radio" name="drinkContainer[]" class="radioDrinkContainer" value="3">
                                                            </div>
                                                        </li><li>
                                                            <div class="container-thumb">
                                                                <div class="container-tick "><span class="sr-only">selected</span></div>
                                                                <img class="container-image" alt="Drink Image" src="./DesktopModules/ATAStandardDrinkTool/images/standard-drinks/thumb-wine-bottle.jpg">
                                                                <div class="container-title">Wine Bottle</div>
                                                                <div class="container-capacity">750ml</div>
                                                                <input type="radio" name="drinkContainer[]" class="radioDrinkContainer" value="4">
                                                            </div>
                                                        </li><li>
                                                            <div class="container-thumb">
                                                                <div class="container-tick "><span class="sr-only">selected</span></div>
                                                                <img class="container-image" alt="Drink Image" src="./DesktopModules/ATAStandardDrinkTool/images/standard-drinks/thumb-beer-middy-glass.jpg">
                                                                <div class="container-title">Middy Glass</div>
                                                                <div class="container-capacity">285ml</div>
                                                                <input type="radio" name="drinkContainer[]" class="radioDrinkContainer" value="5">
                                                            </div>
                                                        </li><li>
                                                            <div class="container-thumb">
                                                                <div class="container-tick "><span class="sr-only">selected</span></div>
                                                                <img class="container-image" alt="Drink Image" src="./DesktopModules/ATAStandardDrinkTool/images/standard-drinks/thumb-beer-schooner-glass.jpg">
                                                                <div class="container-title">Schooner Glass</div>
                                                                <div class="container-capacity">425ml</div>
                                                                <input type="radio" name="drinkContainer[]" class="radioDrinkContainer" value="6">
                                                            </div>
                                                        </li><li>
                                                            <div class="container-thumb">
                                                                <div class="container-tick "><span class="sr-only">selected</span></div>
                                                                <img class="container-image" alt="Drink Image" src="./DesktopModules/ATAStandardDrinkTool/images/standard-drinks/thumb-beer-pint-glass.jpg">
                                                                <div class="container-title">Pint Glass</div>
                                                                <div class="container-capacity">570ml</div>
                                                                <input type="radio" name="drinkContainer[]" class="radioDrinkContainer" value="7">
                                                            </div>
                                                        </li><li>
                                                            <div class="container-thumb">
                                                                <div class="container-tick "><span class="sr-only">selected</span></div>
                                                                <img class="container-image" alt="Drink Image" src="./DesktopModules/ATAStandardDrinkTool/images/standard-drinks/thumb-shot-glass.jpg">
                                                                <div class="container-title">Shot Glass</div>
                                                                <div class="container-capacity">35ml</div>
                                                                <input type="radio" name="drinkContainer[]" class="radioDrinkContainer" value="8">
                                                            </div>
                                                        </li><li>
                                                            <div class="container-thumb">
                                                                <div class="container-tick "><span class="sr-only">selected</span></div>
                                                                <img class="container-image" alt="Drink Image" src="./DesktopModules/ATAStandardDrinkTool/images/standard-drinks/thumb-whiskey-glass.jpg">
                                                                <div class="container-title">Whisky glass</div>
                                                                <div class="container-capacity">200ml</div>
                                                                <input type="radio" name="drinkContainer[]" class="radioDrinkContainer" value="9">
                                                            </div>
                                                        </li>

                                                    </ul>

                                                    <ul class="drink-container-template">
                                                        <li>
                                                            <div class="container-thumb">
                                                                <div class="container-tick "><span class="sr-only">selected</span></div>
                                                                <img class="container-image" alt="Drink Image">
                                                                <div class="container-title">Title</div>
                                                                <div class="container-capacity">300ml</div>
                                                                <input type="radio" name="drinkContainer[]" class="radioDrinkContainer">
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>

                                                <div class="drink-container-buttons">
                                                    <a class="btn drink-container-selected">Next</a>
                                                </div>
                                            </div>

                                            <!-- STEP 2 -->
                                            <div class="drink-types-wrapper clearfix">
                                                <div class="selected-container">
                                                    <img class="container-image" alt="Drink Image" src="images/standard-drinks/thumb-small-wine-glass.jpg">
                                                    <div class="container-title">Small Wine Glass</div>
                                                    <div class="container-capacity">300ml</div>
                                                </div>

                                                <ul class="drink-types">

                                                </ul>

                                                <ul class="drink-types-template">
                                                    <li>
                                                        <div class="container-tick "><span class="sr-only">selected</span></div>
                                                        <span class="drink-title">Title</span><br>
                                                        <span class="drink-volume">10%</span>
                                                        <input type="radio" name="drinkTypeContainer[]" class="drinkTypeContainer">
                                                    </li>
                                                </ul>

                                                <div class="drink-type-buttons">
                                                    <a class="btn step1 js-back">Back</a>
                                                    <a class="btn drinkTypeSelelected">Next</a>
                                                </div>
                                            </div>

                                            <!-- STEP 3 & 4 -->
                                            <div class="drink-wrapper">
                                                <div class="drink-inner-wrapper">
                                                    <div class="drink-overflow" style="background-position: center -12.9404px;">
                                                        <div class="drink-mask" id="drink-mask"><img src="images/standard-drinks/img-glass-pint-mask.png" class="maskimage"></div>
                                                        <div class="underlay-wrapper"><div class="drink-underlay"></div></div>

                                                        <div class="underlay-wrapper topindex">
                                                            <div id="drag-wrapper" class="ui-draggable" style="top: -12.9404px;">
                                                                <div class="arrow-left"></div>
                                                                <div class="dragbox ui-draggable-handle">
                                                                    <div class="filled"><strong><span>0</span>ml</strong></div>
                                                                    Your Pour

                                                                    <div class="dragbox-icon"></div>

                                                                    <div class="filledStandardDrink">
                                                                        <div class="approx">APPROX.</div>
                                                                        <div class="drink-measure"><span>0</span></div>
                                                                        Standard drinks
                                                                    </div>

                                                                </div>
                                                            </div>

                                                            <div class="standardGuide">
                                                                <div class="standardDrinkMl">
                                                                    <div class="approxml">APPROX.</div>
                                                                    <span>0</span>ml
                                                                    <div class="filledStandardDrink">
                                                                        <div class="approx">&nbsp;</div>
                                                                        <div class="drink-measure sd-num">1.0</div>
                                                                        Standard drinks
                                                                    </div>
                                                                </div>
                                                                <div class="arrow-right"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="drink-choices">
                                                    <div class="btn guessBtn">Check Standard Drink</div>
                                                    <strong><span class="glassName"></span></strong> <span class="glasscapacity">0ml</span><br>
                                                    <strong><span class="drinkName"></span></strong> <span class="alcoholVolume"></span>% Alc. Vol
                                                </div>

                                                <div class="sdt-buttons">
                                                    <a class="btn step2 back js-back">Back</a>
                                                    <a class="btn step1 initTool js-restart">Start Again</a>
                                                </div>
                                            </div>
                                        </div>

                                        <script src="./DesktopModules/ATAStandardDrinkTool/js/vendor/jquery.ui.touch-punch.min.js"></script>

                                        <script src="./DesktopModules/ATAStandardDrinkTool/js/standard-drinks-tool.js"></script>

                                        <script src="./DesktopModules/ATAStandardDrinkTool/js/vendor/jquery.flexslider.js"></script>

                                    </div><!-- End_Module_626 --></div>
                                <div class="clear"></div>
                            </div>
                        </div></div>


                </div>
                <div class="h5 drink-tool-prompt">Standard Dringking Tool courtesy of Alcohol, Think Again.</div>
            </div>

            <div class="drink-question-container">
                <form class="question-form drink-form">
                    <label class="question-item">
                        <span class="question-title h5">Q.1 What is the correct volume of red wine to pour into a large wine glass for 1.0 standard drinks? (unit: ml)</span>
                            <span class="question-content">
                    <?php
                             if($status == "UNANSWERED"){ ?>
                                <input type="number"  step="1" name="q1" class="question-field p2">
                    <?php    }
                             if($status == "GRADED"){ ?>
                                 <input type="number"  step="1" name="q1" class="question-field p2" value="95" disabled="disabled">
                     <?php   } ?>
                                <span class="question-error">Error</span>
                            </span>
                    </label>
                    <label class="question-item ">
                        <span class="question-title h5">Q.2 What is the correct volume of mid-strength beer to pour into a schooner glass for 1.0 standard drinks? (unit: ml)</span>
                            <span class="question-content">
                    <?php
                                if($status == "UNANSWERED"){ ?>
                                    <input type="number"  step="1" name="q2" class="question-field p2">
                    <?php       }
                                if($status == "GRADED"){ ?>
                                    <input type="number"  step="1" name="q2" class="question-field p2" value="355" disabled="disabled">
                    <?php       } ?>
                                <span class="question-error">Error</span>
                            </span>
                    </label>
                    <label class="question-item ">
                        <span class="question-title h5">Q.3 What is the correct amount of spirits to pour into a shot glass for 1.0 standard drinks? (unit: ml)</span>
                            <span class="question-content">
                    <?php
                                if($status == "UNANSWERED"){ ?>
                                    <input type="number"  step="1" name="q3" class="question-field p2">
                    <?php       }
                                if($status == "GRADED"){ ?>
                                    <input type="number"  step="1" name="q3" class="question-field p2" value="35" disabled="disabled">
                    <?php       } ?>
                                <span class="question-error">Error</span>
                            </span>
                    </label>
                    <label class="question-item ">
                        <span class="question-title h5">Q.4 What is the correct amount of white wine to pour into a bottle for 1.0 standard drinks? (unit: ml)</span>
                            <span class="question-content">
                    <?php
                                if($status == "UNANSWERED"){ ?>
                                    <input type="number"  step="1" name="q4" class="question-field p2">
                    <?php       }
                                if($status == "GRADED"){ ?>
                                    <input type="number"  step="1" name="q4" class="question-field p2" value="110" disabled="disabled">
                    <?php       } ?>
                                <span class="question-error">Error</span>
                            </span>
                    </label>

                    <?php
                            if($status == "UNANSWERED"){ ?>
                                <button type="submit" class="question-submit">
                                    <span class="question-submit-icon"></span>
                                    SUBMIT
                                </button>
                    <?php   }
                            if($status == "GRADED"){ ?>
                                <button type="submit" class="question-submit" disabled="disabled">
                                    <span class="question-submit-icon"></span>
                                    SUBMIT
                                </button>
                    <?php   } ?>
                </form>
            </div>

        </div>

    </div>

    <ul class="task-operation">
        <li class="cancel-task">
            <a href="weekly-task.php?week=<?php echo $week?>" title="Cancel Task"></a>
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



<script>
    var form = new snap.Form({
        form: '.drink-form',
        onSubmit: function (data) {
            $('.question-error').removeClass('question-error-show');

            var answerArr = [data.q1, data.q2, data.q3, data.q4];

            $.ajax({
                url: "numeric-question-feedback.php",
                data: {
                    student_id: <?php echo $studentID?>,
                    quiz_id: <?php echo $quizID?>,
                    answer_arr: JSON.stringify(answerArr),
                    type: "standard_drinking_tool"
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
        if(feedback.message != "success") {
            alert(feedback.message + ". Please try again!");
            return;
        }

        if(feedback.result == "pass") {
            snap.alert({
                content: 'Congratulation! You have passed this quiz.',
                onClose: function () { }
            });

            $('.question-field').attr('disabled','disabled');
            $('.question-submit').attr("disabled","disabled");
        } else if(feedback.result == "fail") {
            var errors = '[';

            for(i = 0; i < feedback.detail.length - 1; i++) {
                errors = errors + '{"index":' + feedback.detail[i] + ','
                    + '"text" : "Wrong."},';
            }

            errors = errors + '{"index":' + feedback.detail[feedback.detail.length-1] + ','
                + '"text" : "Wrong."}]';

            form.setErrors(JSON.parse(errors));
        }
    }
</script>
</body>
</html>
