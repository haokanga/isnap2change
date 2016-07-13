<?php
    //check login status
    require_once('student-validation.php');

    require_once("../mysql-lib.php");
    require_once("../debug.php");

    $pageName = "cost-calculaor";

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
    <title>Cost Calculator</title>
    <link rel="stylesheet" href="./css/common.css">
    <link rel="stylesheet" href="./css/vendor/jqx.base.css">
    <link href='https://fonts.googleapis.com/css?family=Maitree|Lato:400,900' rel='stylesheet' type='text/css'>
    <script src="./js/snap.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <style>
        .calculator-header {
            padding-top: 20px;
            text-align: center;
        }
        .calculator-title {
            font-size: 28px;
        }
        .calculator-intro {
            width: 280px;
            font-size: 18px;
            margin: 0 auto;
        }

        .calculator-content {
            padding-top: 20px;
        }
        .factor-item {
            width: 220px;
            margin: 0 auto 20px;
            text-align: center;
            font-size: 18px;
        }

        .result-list {
            padding-top: 20px;
        }
        .result-item {
            overflow: hidden;
            padding-bottom: 20px;
            font-size: 20px;
        }
        .result-item-cost-to-date {
            font-size: 24px;
        }
        .result-label {
            float: left;
            width: 160px;
        }
        .result-value {
            color: #fcee2f;
        }

        .question-form {
            font-size: 18px;
        }
        .question-item {
            display: block;
            margin-bottom: 30px;
        }
        .question-title {
            display: block;
            margin-bottom: 20px;
        }
        .question-field {
            display: block;
            width: 100%;
            height: 30px;
            line-height: 30px;
            border-radius: 15px;
            padding: 0 10px;
            outline: 0;
            border: 0;
            font-size: 14px;
            font-family: 'Maitree', serif;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            -webkit-box-shadow: none;
            -moz-box-shadow: none;
            box-shadow: none;
        }
        .question-error {
            color: red;
            display: block;
            margin: 5px 0;
            display: none;
        }
        .question-error-show {
            display: block;
        }
    </style>


    <style>
        /**
         * range样式
         **/
        input[type=range] {
            /*removes default webkit styles*/
            -webkit-appearance: none;
            /*required for proper track sizing in FF*/
            width: 100%;
        }
        input[type=range]::-webkit-slider-runnable-track {
            /*width: 220px;*/
            height: 4px;
            background: #a0a09f;
            border: none;
            border-radius: 2px;
        }
        input[type=range]::-webkit-slider-thumb {
            -webkit-appearance: none;
            border: none;
            height: 16px;
            width: 16px;
            border-radius: 50%;
            background: #fcee2f;
            margin-top: -6px;
        }
        input[type=range]:focus {
            outline: none;
        }
        input[type=range]:focus::-webkit-slider-runnable-track {
            background: #a0a09f;
        }

        input[type=range]::-moz-range-track {
            width: 300px;
            height: 5px;
            background: #ddd;
            border: none;
            border-radius: 3px;
        }
        input[type=range]::-moz-range-thumb {
            border: none;
            height: 16px;
            width: 16px;
            border-radius: 50%;
            background: #fcee2f;
        }

    </style>
</head>
<body>

<div class="page-wrapper">
    <div class="header-wrapper">
        <div class="header">
            <a href="weekly-task.php?week=<?php echo $week?>" class="header-back-link"></a>
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

    <div class="content-wrapper">
        <div class="page-container">

        <div class="calculator-header">
            <h2 class="calculator-title">Cost Calculator</h2>
            <div class="calculator-intro">Use the calculator to answer the questions below.</div>
        </div>
        <div class="calculator-content mini-row">
            <div class="col-6">
                <div class="mini-row factor-list">
                    <div class="col-6">
                        <div class="factor-item">
                            <div class="factor-title">How much do you pay for a pack of cigarettes?</div>
                            <div class="factor-slider">
                                <input type="range" min="0" max="50" value="1" name="price">
                            </div>
                            <div class="factor-label">$<span class="factor-value">1</span></div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="factor-item">
                            <div class="factor-title">How many cigarettes are in each pack?</div>
                            <div class="factor-slider">
                                <input type="range" min="1" max="50" value="20" name="cigaretteAmount">
                            </div>
                            <div class="factor-label"><span class="factor-value">999</span></div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="factor-item">
                            <div class="factor-title">How many cigarettes do you smoke each day?</div>
                            <div class="factor-slider">
                                <input type="range" min="1" max="100" value="1" name="frequency">
                            </div>
                            <div class="factor-label"><span class="factor-value">1</span></div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="factor-item">
                            <div class="factor-title">How many years have you been smoking?</div>
                            <div class="factor-slider">
                                <input type="range" min="0" max="100" value="5" name="smokeYear">
                            </div>
                            <div class="factor-label"><span class="factor-value">1</span></div>
                        </div>
                    </div>
                </div>
                <div class="result-list">
                    <div class="result-item result-item-cost-to-date">
                        <div class="result-label">Cost to Date: </div>
                        <div class="result-value">$133,333</div>
                    </div>
                    <div class="result-item result-item-weekly-cost">
                        <div class="result-label">Weekly Cost:</div>
                        <div class="result-value">$133,333</div>
                    </div>

                    <div class="result-item result-item-monthly-cost">
                        <div class="result-label">Monthly Cost:</div>
                        <div class="result-value">$133,333</div>
                    </div>
                    <div class="result-item result-item-yearly-cost">
                        <div class="result-label">Yearly Cost</div>
                        <div class="result-value">$333</div>
                    </div>

                    <div class="result-item result-item-ten-year-cost">
                        <div class="result-label">Ten Year Cost</div>
                        <div class="result-value">$133,333</div>
                    </div>


                </div>
            </div>
            <div class="col-6">
                <form class="question-form">
                    <label class="question-item">
                        <span class="question-title">Q.1 What would the cost be of smoking 10 cigarettes a day for 10 years if a packet of 20 cigarettes costs $25?</span>
            <?php
                    if($status == "UNANSWERED"){ ?>
                        <input type="text" name="q1" class="question-field">
            <?php   }
                    if($status == "GRADED"){ ?>
                        <input type="text" name="q1" class="question-field" value="$45625.00" disabled="disabled">
            <?php   } ?>
                        <span class="question-error">Error</span>
                    </label>
                    <label class="question-item">
                        <span class="question-title">Q.2 What would the cost be of smoking 20 cigarettes a day for 20 years if a packet of 20 cigarettes costs $25?</span>
            <?php
                    if($status == "UNANSWERED"){ ?>
                        <input type="text" name="q2" class="question-field">
            <?php   }
                    if($status == "GRADED"){ ?>
                        <input type="text" name="q2" class="question-field" value="$182500.00" disabled="disabled">
            <?php   } ?>
                        <span class="question-error">Error</span>
                    </label>
                    <label class="question-item">
                        <span class="question-title">Q.3 What would the cost be of smoking 40 cigarettes a day for 20 years if a packet of 20 cigarettes costs $25?</span>
            <?php
                    if($status == "UNANSWERED"){ ?>
                        <input type="text" name="q3" class="question-field">
            <?php   }
                    if($status == "GRADED"){ ?>
                        <input type="text" name="q3" class="question-field" value="$365000.00" disabled="disabled">
            <?php   } ?>
                        <span class="question-error">Error</span>
                    </label>
            <?php
                    if($status == "UNANSWERED"){ ?>
                    <button type="submit" class="question-submit"></button>
            <?php   }
                    if($status == "GRADED"){ ?>
                    <button type="submit" class="question-submit" disabled="disabled"></button>
            <?php   } ?>
                </form>
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

<script src="./js/vendor/jqxcore.js"></script>
<script src="./js/vendor/jqxnumberinput.js"></script>
<script src="./js/vendor/jqxbuttons.js"></script>

<script>
    var ResultCtrl = {
        init: function () {
            this.cacheElements()
        },
        cacheElements: function () {
            var $main = $('.result-list');
            this.$main = $main;
            this.$costToDate = $main.find('.result-item-cost-to-date');
            this.$weeklyCost = $main.find('.result-item-weekly-cost');
            this.$monthlyCost = $main.find('.result-item-monthly-cost');
            this.$yearlyCost = $main.find('.result-item-yearly-cost');
            this.$tenYearCost = $main.find('.result-item-ten-year-cost')
        },
        /**
         * refresh calculation result
         * @param data.costToDate {string} Cost to Date
         * @param data.weeklyCost {String} weekly cost
         * @param data.monthlyCost {string} monthly cost
         * @param data.yearlyCost {string} yearly cost
         * @param data.tenYearCost {string} ten year cost
         **/
        setLabel: function (data) {
            for (var key in data) {
                var $item = this['$' + key];
                if ($item.length) {
                    $item.find('.result-value')
                        .text(data[key]);
                }

            }
        }
    };

    ResultCtrl.init();

    var RangeCtrl = {
        init: function (opt) {
            opt = opt || {
                    onChange: $.noop
                };
            this.onChange = opt.onChange;
            this.cacheElements();
            this.addListeners();
            this.normalizeLabel();
        },
        cacheElements: function () {
            var $main = $('.factor-list');
            this.$main = $main;
            this.$ranges = $main.find('input[type=range]');
        },
        addListeners: function () {
            var that = this;

            this.$main.on('input', 'input[type=range]', function (e) {
                var $target = $(e.currentTarget);
                $target.closest('.factor-item')
                    .find('.factor-value')
                    .text($target.val());

                that.onChange(that.getRangeData());
            })
        },
        getRangeData: function () {
            var rangeArray = this.$ranges.serializeArray();
            var rangeData = {};
            rangeArray.forEach(function (range) {
                rangeData[range.name] = +range.value
            });
            return rangeData;
        },
        normalizeLabel: function () {
            this.$ranges.each(function () {
                var $range = $(this);
                $range.closest('.factor-item')
                    .find('.factor-value')
                    .text($range.val())
            });
            this.onChange(this.getRangeData())
        }
    };

    var formatCost = function(cost){
        cost = cost.toFixed(2);
        cost += '';
        var costParts = cost.split('.');
        var cost1 = costParts[0].replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
        var cost2 = costParts.length > 1 ? '.' + costParts[1] : '';

        return '$' + cost1 + cost2;
    };

    RangeCtrl.init({
        /**
         * @param data {Object}
         * @param data.price {number}
         * @param data.frequency {number}
         * @param data.cigaretteAmount {number}
         * @param data.smokeYear {number}
         **/
        onChange: function (data) {
            var formatCost = function(cost){
                cost = cost.toFixed(2);
                cost += '';
                var costParts = cost.split('.');
                cost1 = costParts[0].replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
                cost2 = costParts.length > 1 ? '.' + costParts[1] : '';

                return '$' + cost1 + cost2;
            };

            var costPerPack = data.price;
            var cigarettesPerPack = data.cigaretteAmount;
            var cigarettesPerDay = data.frequency;
            var numberOfYears = data.smokeYear;
            var costPerDay, costPerWeek, costPerYear, costPerMonth, costPer5Years, costPer10Years, costToDate;

            if(numberOfYears > 0 && cigarettesPerDay > 0){
                costPerDay = (costPerPack / cigarettesPerPack) * cigarettesPerDay;
                costPerWeek = costPerDay * 7;
                costPerYear = costPerDay * 365;
                costPerMonth = costPerYear / 12;
                costPer5Years = costPerYear * 5;
                costPer10Years = costPerYear * 10;
                costToDate = costPerYear * numberOfYears;
            }
            else{
                costPerDay = costPerWeek = costPerYear = costPerMonth = costPer5Years = costPer10Years = costToDate = gramsTarPerYear = percentageTar = 0;
            }

            ResultCtrl.setLabel({
                costToDate: formatCost(costToDate),
                weeklyCost:  formatCost(costPerWeek),
                monthlyCost:formatCost(costPerMonth),
                yearlyCost: formatCost(costPerYear),
                tenYearCost: formatCost(costPer10Years)
            })
        }
    });

    var QuestionCtrl = {
        init: function (opt) {
            opt = opt || {
                    onSubmit: $.noop
                };
            this.onSubmit = opt.onSubmit;
            this.cacheElements();
<?php   if($status == "UNANSWERED"){ ?>
            this.$fields.find('input').jqxNumberInput({width: '100%', spinButtons: true, symbol: '$'});
            this.addListeners();
<?php   } ?>

        },
        cacheElements: function () {
            var $main = $('.question-form');
            this.$main = $main;
            this.$fields = $main.find('.question-item')
        },
        addListeners: function () {
            var that = this;
            this.$main.on('submit', function (e) {
                e.preventDefault();
                that.onSubmit(that.getFormData());
            })
        },
        getFormData: function () {

            var data = {};
            this.$fields.find('input')
                .each(function () {
                    var $field = $(this);
                    var name = $field.prop('name');
                    if (name) {
                        data[name] = $field.jqxNumberInput('val')
                    }
                });

            return data;
        },
        /**
         * 设置错误信息
         * @param errors {Array} 错误信息数组
         * @param err.index 错误字段下标
         * @param err.text 错误信息文本
         **/
        setError: function (errors) {
            errors = errors || [];
            var that = this;
            that.$fields.removeClass('question-error-show');
            errors.forEach(function (err) {
                var $error = that.$fields.eq(err.index).find('.question-error');
                $error.addClass('question-error-show');
                if (err.text) {
                    $error.text(err.text)
                }
            })
        }
    };
    QuestionCtrl.init({
        /**
         * @param data.q1 {string} answer for the first question
         * @param data.q2 {string} answer for the second question
         * @param data.q3 {string} answer for the third question
         **/
        onSubmit: function (data) {

            $('.question-error').removeClass('question-error-show');

            var answerArr = [data.q1, data.q2, data.q3];

            $.ajax({
                url: "cost-calculator-feedback.php",
                data: {
                    studentID: <?php echo $studentID?>,
                    quizID: <?php echo $quizID?>,
                    answerArr: JSON.stringify(answerArr)
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

            $('.question-field').jqxNumberInput('disabled','true');

            $(".question-submit").attr("disabled","disabled");
        } else if(feedback.result == "fail") {
            //alert("Sorry! You have failed this quiz.");

            for(i = 0; i < feedback.detail.length; i++) {
                QuestionCtrl.setError([
                    {
                        index: feedback.detail[i],
                        text: 'Wrong answer, please try again'
                    }
                ])
            }

        }

    }
</script>

</body>
</html>



