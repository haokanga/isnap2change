<?php
require_once('../mysql-lib.php');
require_once('../debug.php');
$pageName = "signup";

if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST["tokenString"])){
        $token = $_POST["tokenString"];
    }
}

$conn = null;

try {
    $conn = db_connect();

    $tokenRes = getToken($conn, $token);


} catch(Exception $e){
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

<html>
<head>
    <title>Sign Up</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/themes/ui-darkness/jquery-ui.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/jquery-ui.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <script src="./js/vendor/validate.js"></script>
    <link href='https://fonts.googleapis.com/css?family=Maitree|Lato:400,900' rel='stylesheet' type='text/css'>
    <style>

        body {
            margin: 0px !important;
            background: url('./img/miss_pacman.png') no-repeat center center fixed;
            background-size: cover;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            height: 100vh;
            width: 100%;
            font-family: 'Lato', serif !important;
        }

        .header2 {
            font-family: 'Lato', sans-serif;
            font-size: 28px;
            font-weight: normal;
            color: white !important;
        }

        .header3 {
            font-family: 'Lato', sans-serif !important;
            font-size: 24px !important;
            font-weight: normal !important;
            color: #FCEE2D !important;
        }

        .header4 {
            font-family: 'Lato', sans-serif;
            font-size: 20px !important;
            font-weight: normal;
        }

        .header5 {
            font-family: 'Lato', sans-serif;
            font-size: 18px;
            font-weight: normal;
            color: #FCEE2D;
        }

        label {
            padding: 2px;
        }

        .radio-inline {
            vertical-align: -webkit-baseline-middle !important;
        }

        input::-webkit-input-placeholder {
            color:  #FCEE2D !important;
        }

        input:-moz-placeholder { /* Firefox 18- */
            color:  #FCEE2D !important;
        }

        input::-moz-placeholder {  /* Firefox 19+ */
            color:  #FCEE2D !important;
        }

        input:-ms-input-placeholder {
            color:  #FCEE2D !important;
        }
        select {
            text-align-last:center;
        }
        .input-group {
            margin-top:2%;
            text-align: center;
            align-items: center;
            width: 100%;
        }
        .input-label {
            display: table-cell;
            vertical-align: middle;
            padding: 6px;
            color: #FCEE2D;
            font-family: 'Lato', sans-serif;
            font-size: 18px;
            font-weight: normal;
            width: 25%;
            text-align:right;
            font-weight: normal;
        }
        .input-text-box {
            width: 70% !important;
            text-align: center;
            border-radius: 10px !important;
            border: none;
            color:#FCEE2D;
            background-color: black;
            opacity: 0.7;
        }
        .error {
            color:red;
            font-weight: normal;
        }
        .hint {
            color:red;
            text-align:left;
            font-family: 'Lato', sans-serif;
            font-size: 16px;
            font-weight: normal;
            margin-top: 8px;
        }
        .dialog {
            background-color: white;
        }
        .itemset-tandc {
            width: 52%;
            margin-left: 2%;
            border-radius: 10px;
            border: none;
            color: #FCEE2D;
            background-color: black;
            opacity: 0.7;
            font-family: 'Lato', sans-serif;
            font-size: 20px;
            font-weight: normal;
        }
        .terms-conditions-ahref {
            text-decoration: none;
            color: #FCEE2D;
        }
        .terms-conditions-ahref:focus {
            outline: 0;
        }
        .terms-conditions-ahref:hover {
            color: #FCEE2D;
        }
    </style>
</head>
<body>
<form id="sign-up-form">
    <fieldset>
        <div class="col-xs-offset-3 col-xs-6" style="height: 100%; text-align: center; align-items: center; color: white; margin-top: 3%;">
            <input id="classid-hidden" type="hidden" value="<?php echo $tokenRes->ClassID?>">
            <span class="header2"> <?php echo $tokenRes->TokenString?> </span>
            <br>
            <span class="header4"> School: <?php echo $tokenRes->SchoolName?></span>
            <br>
            <span class="header4"> Class: <?php echo $tokenRes->ClassName?></span>
            <br>
            <span class="hint">
                *All the fields are required
            </span>
            <form >
                <div class="input-group">
                    <label class="input-label">Username</label>
                    <input id="username-text" type="text" class="form-control input-text-box" aria-describedby="sizing-addon1" onblur="validInfo('VALIDUSERNAME')">
                    <span id="username-validation-text"></span>
                </div>
                <div class="input-group">
                    <label class="input-label">Nickname</label>
                    <input id="lastname-text" name="lastname"  type="text" class="form-control input-text-box" aria-describedby="sizing-addon1">

                </div>
                <div class="input-group">
                    <label class="input-label">Password</label>
                    <input id="password-text" name="password" type="password" class="form-control input-text-box" aria-describedby="sizing-addon1">

                </div>
                <div class="input-group">
                    <label class="input-label">Confirm Password</label>
                    <input id="password-text2" name="password2" type="password" class="form-control input-text-box" aria-describedby="sizing-addon1">

                </div>
                <div class="input-group">
                    <label class="input-label">Last Name</label>
                    <input id="lastname-text" name="lastname"  type="text" class="form-control input-text-box" aria-describedby="sizing-addon1">

                </div>
                <div class="input-group">
                    <label class="input-label">First Name</label>
                    <input id="firstname-text" name="firstname" type="text" class="form-control input-text-box" aria-describedby="sizing-addon1">

                </div>
                <div class="input-group">
                    <label class="input-label">Email</label>
                    <input id="email-text" name="email" type="email" class="form-control input-text-box" aria-describedby="sizing-addon1">

                </div>
                <div class="input-group">
                    <label class="input-label">Date Of Birth</label>
                    <select id="dob-dd-selection" class="form-control" style="opacity: 0.7; font-family: 'Lato', sans-serif; font-size: 20px; font-weight: normal; margin: 0.8%; width: 22%; background-color: black; color:white; border-radius: 10px; border: none; text-align: center;">
                        <option value="" selected disabled>DD</option>
                        <option>01</option>
                        <option>02</option>
                        <option>03</option>
                        <option>04</option>
                        <option>05</option>
                        <option>06</option>
                        <option>07</option>
                        <option>08</option>
                        <option>09</option>
                        <option>10</option>
                        <option>11</option>
                        <option>12</option>
                        <option>13</option>
                        <option>14</option>
                        <option>15</option>
                        <option>16</option>
                        <option>17</option>
                        <option>18</option>
                        <option>19</option>
                        <option>20</option>
                        <option>21</option>
                        <option>22</option>
                        <option>23</option>
                        <option>24</option>
                        <option>25</option>
                        <option>26</option>
                        <option>27</option>
                        <option>28</option>
                        <option>29</option>
                        <option>30</option>
                        <option>31</option>
                    </select>
                    <select id="dob-mm-selection" class="form-control" style="opacity: 0.7; font-family: 'Lato', sans-serif; font-size: 20px; font-weight: normal; margin: 0.8%; width: 22%; background-color: black; color:white; border-radius: 10px; border: none; text-align: center;">
                        <option value="" selected disabled>MM</option>
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                        <option>6</option>
                        <option>7</option>
                        <option>8</option>
                        <option>9</option>
                        <option>10</option>
                        <option>11</option>
                        <option>12</option>
                    </select>
                    <select id="dob-yyyy-selection" class="form-control" style="opacity: 0.7; font-family: 'Lato', sans-serif; font-size: 20px; font-weight: normal; margin: 0.8%; width: 22%; background-color: black; color:white; border-radius: 10px; border: none; text-align: center;">
                        <option value="" selected disabled>YYYY</option>
                        <option>2000</option>
                        <option>2001</option>
                        <option>2002</option>
                        <option>2003</option>
                        <option>2004</option>
                        <option>2005</option>
                        <option>2006</option>
                        <option>2007</option>
                        <option>2008</option>
                        <option>2009</option>
                        <option>2010</option>
                        <option>2011</option>
                        <option>2012</option>
                    </select>
                </div>
                <div class="input-group">
                    <label class="input-label">Gender</label>

                    <label class="radio-inline" style="background-color: black; border-radius: 8px; opacity: 0.7; width: 33%; margin-left: -28%;">
                        <input type="radio" name="gender-radio" id="inlineRadio1" value="Male"><span class="header4" style="color: white !important;">Male</span>
                    </label>
                    <label class="radio-inline" style="background-color: black; border-radius: 8px; opacity: 0.7; width: 33%;">
                        <input type="radio" name="gender-radio" id="inlineRadio2" value="Female"><span class="header4" style="color: white !important;">Female</span>
                    </label>

                </div>
                <div class="input-group">
                    <span class="input-label">Do you identify as:</span>
                    <select id="identity-selection" class="form-control header4" style="opacity: 0.7; width: 69%; background-color: black; color:white; border-radius: 10px; border: none; text-align: center;">
                        <option><span class="header4" style="color: white !important;text-align: center;">Aboriginal</span></option>
                        <option><span class="header4" style="color: white !important;text-align: center;">Resident</span></option>
                    </select>
                </div>
                <div class="input-group">
                    <div name="itemset-tandc" class="itemset-tandc btn btn-primary btn-default" >
                        <a class="terms-conditions-ahref">Terms & Conditions</a>
                        <div name="terms-conditions-checkbox-wrapper" style="display: inline;">
                            <input type="checkbox" name="agree" id="terms-conditions-checkbox">
                        </div>
                    </div>
                </div>
                <div id="terms-and-conditions" class="dialog" title="Dialog">
                    <p>I am terms and conditions</p>
                </div>
                <div class="submitinfo" style="text-align: center;">
                    <button type="button" onclick="checkValidaton()" class="header4 btn btn-primary btn-lg btn-block" style="margin-left: 35%; width:30%; margin-top:4%; border-radius: 10px; white-space: normal; border: #FCEE2D solid 2px; border-color: #FCEE2D !important; color:#FCEE2D; background-color: black; opacity: 0.7;">Register</button>
                </div>
        </div>
    </fieldset>
    </form>
    <script>
        $(document).ready(function() {
            $("#terms-conditions-checkbox").hide();
            // validate the form when it is submitted
            var validator = $("#sign-up-form").validate({
                rules: {
                    firstname: "required",
                    lastname: "required",
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true,
                        minlength: 5,
                        maxlength: 12
                    },
                    password2: {
                        required: true,
                        minlength: 5,
                        maxlength: 12,
                        equalTo: "#password-text"
                    },
                },
                errorElement: "span",
                messages: {
                    password2: {
                        equalTo: "These passwords don\'t match"
                    }
                }
            });
        });

        $(function() {
            $("#terms-and-conditions").dialog({
                autoOpen: false,
                modal: true
            });

            $(".terms-conditions-ahref").on("click", function() {
                $("#terms-and-conditions").dialog("open");
                $("#terms-conditions-checkbox").show();
            });

        });

        function parseValidInfoFeedback(response){
            var feedback = JSON.parse(response);

            if(feedback.message != "success"){
                alert(feedback.message + ". Please try again!");
                return;
            }

            if(feedback.result == "invalid"){
                $('#username-validation-text').css("color","red");
                $('#username-validation-text').text("This username is used!");
                $('#username-text').val("");
            } else if(feedback.result == "valid"){
                $('#username-validation-text').css("color","green");
                $('#username-validation-text').text("This username is valid!");
            }
        }

        function parseSubmitInfoFeedback(response){
            var feedback = JSON.parse(response);

            if(feedback.message != "success"){
                alert(feedback.message + ". Please try again!");
                return;
            } else if(feedback.message == "success"){
                alert("You have successfully registered!");
            }


        }

        function validInfo(action){
            var username = document.getElementById("username-text").value;
            var postData = "username="+username+"&action="+action;

            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    parseValidInfoFeedback(xmlhttp.responseText);
                }
            };

            xmlhttp.open("POST", "signup-feedback.php", true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send(postData);
        }

        function submitInfo(action){
            var username = $('#username-text').val();
            var password = $('#password-text').val();
            var lastname = $('#lastname-text').val();
            var firstname = $("#firstname-text").val();
            var email = $('#email-text').val();
            var dobDay = $('#dob-dd-selection').val();
            var dobMon = $('#dob-mm-selection').val();
            var dobYear = $('#dob-yyyy-selection').val();
            var gender = $("input[type='radio'][name='gender-radio']:checked").val();
            var identity = $('#identity-selection').val();
            var classID = $('#classid-hidden').val();

            var postData = "username="+username+"&password="+password+"&lastname="+lastname+"&firstname="+firstname
                +"&email="+email+"&dobDay="+dobDay+"&dobMon="+dobMon+"&dobYear="+dobYear+"&gender="+gender
                +"&identity="+identity+"&classID="+classID+"&action="+action;

            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    parseSubmitInfoFeedback(xmlhttp.responseText);
                }
            };

            xmlhttp.open("POST", "signup-feedback.php", true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send(postData);
        }

        function checkValidaton(){
            var username = $('#username-text').val();
            var password = $('#password-text').val();
            var lastname = $('#lastname-text').val();
            var firstname = $("#firstname-text").val();
            var email = $('#email-text').val();
            var dobDay = $('#dob-dd-selection').val();
            var dobMon = $('#dob-mm-selection').val();
            var dobYear = $('#dob-yyyy-selection').val();
            var gender = $("input[type='radio'][name='gender-radio']:checked").val();
            var identity = $('#identity-selection').val();
            var classID = $('#classid-hidden').val();
            var errorMsg = "";

            if(!$("#terms-conditions-checkbox").is(':checked')){
                errorMsg = "You have to agree to our terms and conditions to registrate.";
            }

            var error = [];
            if(dobDay == null || dobMon == null || dobYear == null ){
                error.push("birthday");
            }

            if(gender == undefined){
                error.push("gender");
            }

            if(error.length != 0){
                errorMsg += "The ";
                for (i = 0; i < error.length; i ++) {
                    if(i != 0 && i != error.length - 1){
                        errorMsg +=", ";
                    }
                    if(i == error.length - 1 && i != 0){
                        errorMsg += " and ";
                    }
                    errorMsg += error[i];
                }

                if(error.length == 1){
                    errorMsg += " is invalid. Please check again!";
                }else{
                    errorMsg += " are invalid. Please check again!";
                }
            }

            if(errorMsg != ""){
                alert(errorMsg);
            }else{
                submitInfo('REGISTER');
            }
        }
    </script>
</body>
</html>