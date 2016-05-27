<html>
    <head>
        <title>Quiz</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="css/quiz.css" />
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link href='https://fonts.googleapis.com/css?family=Raleway:400|Open+Sans' rel='stylesheet' type='text/css'>
        <link href='https://maxcdn.bootstrapcdn.com/font-awesome/4.6.2/css/font-awesome.min.css' rel='stylesheet' type='text/css'>
        <script type="text/javascript" src="js/jquery-1.12.3.js"></script>
    </head>
    <body>
        <script>

            function CountDownTimer(duration, granularity) {
                this.duration = duration;
                this.granularity = granularity || 1000;
                this.tickFtns = [];
                this.running = false;
            }

            CountDownTimer.prototype.start = function () {
                if (this.running) {
                    return;
                }
                this.running = true;
                var start = Date.now(),
                        that = this,
                        diff, obj;

                (function timer() {
                    diff = that.duration - (((Date.now() - start) / 1000) | 0);

                    if (diff > 0) {
                        setTimeout(timer, that.granularity);
                    } else {
                        diff = 0;
                        that.running = false;
                    }

                    obj = CountDownTimer.parse(diff);
                    that.tickFtns.forEach(function (ftn) {
                        ftn.call(this, obj.minutes, obj.seconds);
                    }, that);
                }());
            };

            CountDownTimer.prototype.onTick = function (ftn) {
                if (typeof ftn === 'function') {
                    this.tickFtns.push(ftn);
                }
                return this;
            };

            CountDownTimer.prototype.expired = function () {
                return !this.running;
            };

            CountDownTimer.parse = function (seconds) {
                return {
                    'minutes': (seconds / 60) | 0,
                    'seconds': (seconds % 60) | 0
                };
            };

            window.onload = function () {

                var display1 = document.querySelector('#time1'),
                        timer = new CountDownTimer(30);    // set time here

                timer.onTick(format1).start();

                function format1(minutes, seconds) {
                    minutes = minutes < 10 ? "0" + minutes : minutes;
                    seconds = seconds < 10 ? "0" + seconds : seconds;
                    display1.textContent = minutes + ':' + seconds;
                }
            };

            $(document).ready(function ()
            {
                $("#button0").addClass("highlight");

                $('#panel1').css({
                    top: ($('.content').outerHeight() - $('#panel1').outerHeight()) / 2
                });

                $(".options").find(".btn").click(function () {
                    var index = $("#hiddenIndex").val();
                    var num = $(this).attr('id');
                    $("#radio_" + num).prop("checked", true);
                    $("#panel" + index).find(".btn").removeClass("active");
                    $(this).addClass("active");
                    $("#button" + index).addClass("completed");
                });

                $(".next").click(function () {
                    var index = $("#hiddenIndex").val();
                    $("#panel" + index).addClass("hidden");
                    $("#button" + index).removeClass("highlight");
                    index++;
                    $("#panel" + index).removeClass("hidden");
                    $("#panel" + index).css({
                        top: ($('.content').outerHeight() - $("#panel" + index).outerHeight()) / 2
                    });
                    $("#hiddenIndex").val(index);
                    $("#button" + index).addClass("highlight");
                });

                $(".last").click(function () {
                    var index = $("#hiddenIndex").val();
                    $("#panel" + index).addClass("hidden");
                    $("#button" + index).removeClass("highlight");
                    index--;
                    $("#panel" + index).removeClass("hidden");
                    $("#panel" + index).css({
                        top: ($('.content').outerHeight() - $("#panel" + index).outerHeight()) / 2
                    });
                    $("#hiddenIndex").val(index);
                    $("#button" + index).addClass("highlight");
                });

                $(".opt").find(".btn").click(function () {
                    debugger;
                    var index = $(this).val();
                    $(this).addClass("highlight");
                    var currentIndex = $("#hiddenIndex").val();
                    $("#button" + currentIndex).removeClass("highlight");
                    $("#panel" + currentIndex).addClass("hidden");
                    $("#panel" + index).removeClass("hidden");
                    $("#panel" + index).css({
                        top: ($('.content').outerHeight() - $("#panel" + index).outerHeight()) / 2
                    });
                    $("#hiddenIndex").val(index);

                });

            });
        </script>
        <header class="navbar navbar-static-top bs-docs-nav">

            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                </button>
                <a class="navbar-brand" href="#">QUIZ</a>
            </div>
            <div class="nav navbar-nav navbar-btn navbar-right" style="margin-right:22px;">


                <button type="button" onclick="return goBack()" class="btn btn-success">SUBMIT</button> 


            </div>
            <div class="nav navbar-nav navbar-btn navbar-right" style="margin-right: 15px; font-size: x-large;">
                <span id="time1"></span>
            </div>
        </header>

        <div class="content"> 
            <div class="col-md-1 sidebar" style="margin-top:8px; margin-bottom:8px;">

                <ul class="list-group lg opt" style="max-height: 89vh; overflow-y: auto;">
                    <li class="list-group-item" style="color:turquoise;">
                        <button type="button" class="btn btn-default" id="button0" style="color:turquoise;font-weight: bold;" value="0">i</button>
                    </li>
                     <li class="list-group-item">
                        <button type="button" class="btn btn-default" id="button1" value="1">1</button>
                    </li>
                    <li class="list-group-item">
                        <button type="button" class="btn btn-default" id="button2" value="2">2</button>
                    </li>
                    <li class="list-group-item">
                        <button type="button" class="btn btn-default" id="button3" value="3">3</button>
                    </li>
                    <li class="list-group-item">
                        <button type="button" class="btn btn-default" id="button4" value="4">4</button>
                    </li>
                    <li class="list-group-item">
                        <button type="button" class="btn btn-default" id="button5" value="5">5</button>
                    </li>
                    <li class="list-group-item">
                        <button type="button" class="btn btn-default" id="button6" value="6">6</button>
                    </li>
                    <li class="list-group-item">
                        <button type="button" class="btn btn-default" id="button7" value="7">7</button>
                    </li>
                    <li class="list-group-item">
                        <button type="button" class="btn btn-default" id="button8" value="8">8</button>
                    </li>
                    <li class="list-group-item">
                        <button type="button" class="btn btn-default" id="button9" value="9">9</button>
                    </li>
                    <li class="list-group-item">
                        <button type="button" class="btn btn-default" id="button10" value="10">10</button>
                    </li>


                </ul>
            </div>


            <div class="info" style="padding-top:10px; padding-bottom:10px;" id="panel0">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="myHeader" style="text-align:center;">
                            <div class="page-header" style="color: black;">
                                <h1> 
                                    <i>Nutrition</i>
                                </h1> 
                            </div>
                            <div class="para" style="padding-left:15px; padding-right:15px;">
                                <div style="color:black; text-align:center;">
                                    There are three main layers of the food pyramid. The bottom layer is the most important one for your daily intake of food. It contains vegetables, fruits, grains and legumes. You should be having most of your daily food from this layer. These foods are all derived or grow on plants and contain important nutrients such as vitamins, minerals and antioxidants. They are also responsible for being the main contributor of carbohydrates and fibre to our diet. 
                                    The middle layer is comprised of dairy based products such as milk, yoghurt, cheese. These are essential to providing our bodies with calcium and protein and important vitamins and minerals. 
                                    They layer also contains lean meat, poultry, fish, eggs, nuts, seeds, legumes. These foods are our main source of protein and are also responsible for providing other nutrients to us including iodine, iron, zinc, B12 vitamins and healthy fats. 
                                    The top layer, which is the smallest layer, is the layer you should me eating the least off. This layer is made up of food which has unsaturated fats such as sugar, butter, margarine and oils; small amounts of these unsaturated fats are needed for healthy brain and hear function. 
                                    (my own words)
                                    Source: The Healthy Living Pyramid. Nutrition Australia. [Accessed 28/04/2016 http://www.nutritionaustralia.org/national/resource/healthy-living-pyramid]

                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="myques">
                <div class="panel panel-default hidden" id="panel1">
                    <div class="panel-heading" style="font-size: xx-large; font-weight: 600; color:black; height:35%; min-height: 35%; max-height: 35%; text-align:center;">
                        <div class="ques" >
                            Which of these breakfast foods will provide you with the most energy and is very healthy for you?
                        </div> 
                    </div>
                    <div class="panel-body" style="width: 85%; margin-left:7.5%;">
                        <br/>
                        <textarea class="form-control" rows="10"></textarea>
                        <br>
                        <br>
                        <div class="back2"  style="text-align: center;">
                            <a class="btn btn-default next" href="#" role="button" style="padding-top:8px;"><span class="glyphicon glyphicon-chevron-left"></span></a>
                            <a class="btn btn-default back" href="#" role="button" style="padding-top:8px;"><span class="glyphicon glyphicon-chevron-right"></span></a>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default hidden" id="panel2">
                    <div class="panel-heading" style="font-size: xx-large; font-weight: 600; color:black; height:35%; min-height: 35%; max-height: 35%; justify-content:center; display:flex; align-items:center;">
                        <div class="ques" >
                            Which of these breakfast foods?
                        </div>   
                    </div>
                     <div class="panel-body" style="width: 85%; margin-left:7.5%;">
                        <br/>
                        <textarea class="form-control" rows="10"></textarea>
                        <br>
                        <br>
                        <div class="back2"  style="text-align: center;">
                            <a class="btn btn-default next" href="#" role="button" style="padding-top:8px;"><span class="glyphicon glyphicon-chevron-left"></span></a>
                            <a class="btn btn-default back" href="#" role="button" style="padding-top:8px;"><span class="glyphicon glyphicon-chevron-right"></span></a>
                        </div>
                    </div>
                    
                    
                </div>

                <input type="hidden" id="hiddenIndex" value="0">
                <input type=hidden id="hiddenMCQIDArray" value="<?php echo substr($MCQIDArray, 0, strlen($MCQIDArray)-1); ?>">

                <form id="hiddenReturnQuiz" action="learning-material.php" method=post>
                    <!--	<input  type=hidden name="quizid" value=<?php echo $quizid; ?>></input>
                            <input  type=hidden name="quiztype" value=<?php echo $quiztype; ?>></input>
                            <input  type=hidden name="week" value=<?php echo $week; ?>></input>
                            <input  type=hidden name="status" value=<?php echo $status; ?>></input>  -->
                </form>

                <form id="hiddenReturnTask" action="weekly-task.php" method=post>
                    <!--	<input  type=hidden name="week" value=<?php echo $week; ?>></input>  -->
                </form>
            </div>
        </div>
    </body>
</html>

