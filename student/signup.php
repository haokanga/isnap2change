<?php
    require_once('mysql-lib.php');
    require_once('debug.php');
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

        debug_err($pageName, $e);
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
        <link rel="stylesheet" type="text/css" href="css/signup.css" />
        <script src="js/jquery.js"></script>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
        <script>
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

        </script>
    </head>
    <body>
        <div class="col-xs-offset-3 col-xs-6" style="height: 100%; text-align: center; align-items: center; color: white; margin-top: 3%;">
            <input id="classid-hidden" type="hidden" value="<?php echo $tokenRes->ClassID?>">
            <span style="color:yellow;"> <?php echo $tokenRes->TokenString?> </span>
            <br>
            School: <?php echo $tokenRes->SchoolName?>
            <br>
            Class: <?php echo $tokenRes->ClassName?>
            <br>

            <div class="input-group" style="margin-top:2%; text-align: center; align-items: center; width: 100%;">
                <span style="display: table-cell; vertical-align: middle; padding: 6px; color: yellow; width: 25%; text-align:right;">Username</span>
                <input id="username-text" type="text" style="width: 70%; text-align: center; border-radius: 10px; border: none; color:yellow; background-color: black; opacity: 0.7;" class="form-control" aria-describedby="sizing-addon1" onblur="validInfo('VALIDUSERNAME')">
                <span id="username-validation-text"></span>
            </div>
            <div class="input-group" style="margin-top:2%; text-align: center; align-items: center; width: 100%;">
                <span style="display: table-cell; vertical-align: middle; padding: 6px; color: yellow; width: 25%; text-align:right;">Password</span>
                <input id="password-text" type="text" style="width: 70%; text-align: center; border-radius: 10px; border: none; color:yellow; background-color: black; opacity: 0.7;" class="form-control" aria-describedby="sizing-addon1">
            </div>
            <div class="input-group" style="margin-top:2%;  text-align: center; align-items: center; width: 100%;">
                <span style="display: table-cell; vertical-align: middle; padding: 6px; color: yellow; text-align:right; width:25%;">Last Name</span>
                <input id="lastname-text" type="text" style="width: 70%; text-align: center; border-radius: 10px; border: none; color:yellow; background-color: black; opacity: 0.7;" class="form-control" aria-describedby="sizing-addon1">
            </div>
            <div class="input-group" style="margin-top:2%;  text-align: center; align-items: center; width: 100%;">
                <span style="display: table-cell; vertical-align: middle; padding: 6px; color: yellow; text-align:right; width:25%;">First Name</span>
                <input id="firstname-text" type="text" style="width: 70%; text-align: center; border-radius: 10px; border: none; color:yellow; background-color: black; opacity: 0.7;" class="form-control" aria-describedby="sizing-addon1">
            </div>
            <div class="input-group" style="margin-top:2%;  align-items: center; width: 100%;">
                <span style="display: table-cell; vertical-align: middle; padding: 6px; color: yellow; text-align:right; width: 25%;">Email</span>
                <input id="email-text" type="email" style="width: 70%; text-align: center; border-radius: 10px; border: none; color:yellow; background-color: black; opacity: 0.7;" class="form-control" aria-describedby="sizing-addon1">
            </div>
            <div class="input-group" style="margin-top:2%; text-align: center; align-items: center; width: 100%;">
                <span style="display: table-cell; vertical-align: middle; padding: 6px; color: yellow; width: 25%; text-align:right;">Date Of Birth</span>
               <select id="dob-dd-selection" class="form-control" style="opacity: 0.7; font-size: 0.7em; margin: 0.8%; width: 22%; background-color: black; color:white; border-radius: 10px; border: none; text-align: center;">
                    <option value="" selected disabled>DD</option>
                    <option>12</option>
                    <option>13</option>
                </select>
                <select id="dob-mm-selection" class="form-control" style="opacity: 0.7; font-size: 0.7em; margin: 0.8%; width: 22%; background-color: black; color:white; border-radius: 10px; border: none; text-align: center;">
                    <option value="" selected disabled>MM</option>
                    <option>1</option>
                    <option>2</option>
                </select>
                 <select id="dob-yyyy-selection" class="form-control" style="opacity: 0.7; font-size: 0.7em; margin: 0.8%; width: 22%; background-color: black; color:white; border-radius: 10px; border: none; text-align: center;">
                    <option value="" selected disabled>YYYY</option>
                    <option>2001</option>
                    <option>2002</option>
                </select>
            </div>
            <div class="input-group" style="margin-top:2%; text-align: center; align-items: center; width: 100%;">
                <span style="display: table-cell; vertical-align: middle; padding: 6px; color: yellow; width: 25%; text-align:right;">Gender</span>

                <label class="radio-inline" style="background-color: black; border-radius: 8px; opacity: 0.7; width: 33%; margin-left: -28%;">
                    <input type="radio" name="gender-radio" id="inlineRadio1" value="Male"> Male
                </label>
                <label class="radio-inline" style="background-color: black; border-radius: 8px; opacity: 0.7; width: 33%;">
                    <input type="radio" name="gender-radio" id="inlineRadio2" value="Female"> Female
                </label>    

            </div>
            <div class="input-group" style="margin-top:2%; text-align: center; align-items: center; width: 100%;">
                <span style="display: table-cell; vertical-align: middle; padding: 6px; color: yellow; width: 25%; text-align:right;">Do you identify as</span>
                <select id="identity-selection" class="form-control" style="opacity: 0.7; width: 69%; background-color: black; color:white; border-radius: 10px; border: none; text-align: center;">
                    <option>Aboriginal</option>
                    <option>Resident</option>
                </select>    
            </div>
            <div class="input-group"  style="margin-top:2%; text-align: center; align-items: center; width: 100%;">           
                <button type="button" class="btn btn-primary btn-default" style="width: 52%; margin-left: 2%; border-radius: 10px; border: none; color:yellow; background-color: black; opacity: 0.7;">Terms & Conditions  <input type="radio" style="color: yellow;" aria-label="..."></button> 
            </div>
            <div class="submitinfo" style="text-align: center;">
                <button type="button" onclick="submitInfo('REGISTER')" class="btn btn-primary btn-lg btn-block" style="margin-left: 35%; width:30%; margin-top:4%; border-radius: 10px; white-space: normal; border: yellow solid 2px; border-color: yellow !important; color:yellow; background-color: black; opacity: 0.7;">Register</button>
            </div>
        </div>

    </body>
</html>
