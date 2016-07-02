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
        <link rel="stylesheet" type="text/css" href="./css/signup.css"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
        <script src="./js/vendor/jquery.js"></script>
        <script src="./js/signup.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
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
                <select id="dob-mm-selection" class="form-control" style="opacity: 0.7; font-size: 0.7em; margin: 0.8%; width: 22%; background-color: black; color:white; border-radius: 10px; border: none; text-align: center;">
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
                <select id="dob-yyyy-selection" class="form-control" style="opacity: 0.7; font-size: 0.7em; margin: 0.8%; width: 22%; background-color: black; color:white; border-radius: 10px; border: none; text-align: center;">
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
