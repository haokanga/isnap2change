
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
           
            <div class="col-md-offset-4 col-md-4 col-xs-10 col-xs-offset-1" style="height: 40%; text-align: center; align-items: center;">
                <img class="img-responsive" src="css/image/Snap_Logo_Inverted.png" style="height:70%; margin-top:20%; width: 100%; margin-bottom: 8%;">
                <br>
                </div>
                <div class="col-md-offset-5 col-md-2 col-xs-8 col-xs-offset-2" style="height: 60%; text-align: center; align-items: center; margin-top: 2%;">
                <span style="color: white; text-align: center; font-size: 4vh;">Verify Token</span>
                <div style="text-align: center; margin-top: 4%">
                    <span id="token-validation-fail-text" style="color:red"></span>
                </div>
                <div class="input-group input-group-lg" style="margin-top:8%; text-align: center; align-items: center; width: 100%;">
                    <form id="valid-token" action="signup.php" method="post">
                        <input id="token" name="tokenString" type="password" style="text-align: center; border-radius: 8px; border: none; color:yellow; background-color: black; opacity: 0.7; font-size: 3.8vh;" class="form-control"  placeholder="Token Number" onfocus="this.placeholder=''" onblur="this.placeholder='Token Number'" aria-describedby="sizing-addon1">
                    </form>
                </div>
                <button type="button" onclick="validInfo('VALIDTOKEN')" class="btn btn-primary btn-lg btn-block" style="margin-top:10%; border-radius: 10px; border-color: yellow !important; font-size: 3.6vh; border: yellow solid 4px; color:yellow; background-color: black; opacity: 0.7; width: 80%; margin-left: 10%;">Verify</button>
            </div> 
    </body>
</html>
