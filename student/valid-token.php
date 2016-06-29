
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
            function parseFeedback(response){
                var feedback = JSON.parse(response);

                if(feedback.message != "success"){
                    alert(feedback.message + ". Please try again!");
                    return;
                }

                if(feedback.result == "valid"){
                    $('#valid-token').submit();
                } else {
                    $('#token-validation-fail-text').text("Invalid token!");
                    $('#token').val("");
                }
            }

            function validInfo(action){
                var token = document.getElementById("token").value;
                var postData = "token="+token+"&action="+action;

                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                        parseFeedback(xmlhttp.responseText);
                    }
                };

                xmlhttp.open("POST", "signup-feedback.php", true);
                xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xmlhttp.send(postData);
            }
        </script>
    </head>
    <body>
            <div class="col-xs-offset-5 col-xs-2" style="height: 100%; text-align: center; align-items: center;">
                <img class="img-responsive" src="css/image/Snap_Logo_Inverted.png" style="height:28%; margin-top:39%; width: 130%; margin-left: -20%;">
                <br>
                <h2 style="color: white; text-align: center; margin-top:10%;">Verify Token</h2>
                <div style="text-align: center; margin-top: 4%">
                    <span id="token-validation-fail-text" style="color:red"></span>
                </div>
                <div class="input-group input-group-lg" style="margin-top:4%; text-align: center; align-items: center;">
                    <form id="valid-token" action="signup.php" method="post">
                        <input id="token" name="tokenString" type="password" style="text-align: center; border-radius: 10px; border: none; color:yellow; background-color: black; opacity: 0.7;" class="form-control"  placeholder="TOKEN NUMBER" onfocus="this.placeholder=''" onblur="this.placeholder='Token Number'" aria-describedby="sizing-addon1">
                    </form>
                </div>
                <button type="button" onclick="validInfo('VALIDTOKEN')" class="btn btn-primary btn-lg btn-block" style="margin-top:8%; border-radius: 10px; border-color: yellow !important; color:yellow; background-color: black; opacity: 0.7;">Verify</button>
            </div> 
    </body>
</html>
