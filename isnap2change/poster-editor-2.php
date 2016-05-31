<?php






?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8"/>
<title>Zwibbler - Quickly scribble your ideas online</title>
<meta name="description" content="Draw stuff and link to it without downloading anything. This online vector graphics application simulates the back of a napkin for sketching your ideas.">
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />
<style type="text/css">
body {
    min-width:500px;
    font-family: Tahoma, Helvetica, Arial;
    overflow: hidden;
}

a#about {
    position: fixed;
    right: 0px;
    bottom: 0px;
}

div.signin {
    text-align: left;
    margin: 0px;
    padding: 0px;
    font-family: Arial;
    font-size: 8pt;
    padding: 0.5em;
    text-align: right;
    display: none;
}

div#logout {
    display: none;
}

div.loading {
    background: rgb(128,0,0);
    position: absolute;
    top:0;
    right: 0;
    color: white;
    text-decoration: blink;
    display:none;
}
div#accountCreateErrors {
    color: red;
    margin: 1em;
    text-align: center;
}

td {
    font-family: Arial;
    font-size: 8pt;
}

div.createAccount {
    position: absolute;
    top: 0px;
    width: 100%;
    background: #C1D2DE;
    border: 2px solid #001da0;
    display:none;        
}

div.loading {
    background: rgb(128,0,0);
    position: absolute;
    top:0;
    right: 0;
    color: white;
    text-decoration: blink;
    display:none;
}

div.loginResult {
    color: rgb(128,0,0);
    font-family: Arial;
    font-size: 8pt;
    display: none;
}

div#status {
    position: absolute;
    top: 0;
    right: 1em;
    display: none;
    font-family: arial;
    font-size: 10pt;
    text-align: right;
}

div.dialog {
    position: absolute;
    border: 4px solid orange;
    background: white;        
    padding: 10px;
    text-align: center;
    z-index: 2;
    display: none;
    font-family: sans, tahoma, arial, helvetica;
    border-radius: 8px;
}

div.dialog h1 {
    color: orange;
    margin-top: 0px;
}

div.dialog input {
    font-size: large;
}
div.dialog td {
    font-size: large;
}

div#filelist td {
    font-size: 13px;
}

.menuItem {
    float:left;
    padding-bottom: 0px;      
    padding-right: 5px;      
    padding-left: 5px;
    vertical-align: bottom;
    font-family: "FG Virgil";
    font-size: 22px;
    text-decoration: none;
    color: black;
}

.menuItem:hover {
    background: #a9b9e5;
}

div.menubar {

}

.no-select
{
   user-select: none;
   -o-user-select:none;
   -moz-user-select: none;
   -khtml-user-select: none;
   -webkit-user-select: none;
}

@font-face
{
    font-family: "FG Virgil";
    src: url('FGVirgil.ttf');
}

@font-face
{
    font-family: "Stinky Kitty";
    src: url('STINK___.TTF');
}

</style>
<script type='text/javascript'>
    window.Muscula = { settings:{
        logId:"cb0b11da-ca86-4c1c-830f-78e028a95cfb", suppressErrors: false, branding: 'none'
    }};
    (function () {
        var m = document.createElement('script'); m.type = 'text/javascript'; m.async = true;
        m.src = (window.location.protocol == 'https:' ? 'https:' : 'http:') +
            '//musculahq.appspot.com/Muscula.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(m, s);
        window.Muscula.run=function(c){eval(c);window.Muscula.run=function(){};};
        window.Muscula.errors=[];window.onerror=function(){window.Muscula.errors.push(arguments);
        return window.Muscula.settings.suppressErrors===undefined;}
    })();
</script>
<!--[if IE]><script src="flashcanvas.js"></script><![endif]-->
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-5751047-6']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</head>
<body>

<!-- The page is divided into two divs: menubar, and lower -->

<div id="main">

    <div id="menubar" style="background: #d3daed;border-bottom: 1px solid gray">

     
        <div id=signin class=signin style="float:right">
           <form action="javascript:app.loginClicked()" id=signInForm>
               <div class=loginResult id=loginResult>
                   
               </div>
               Username: <input type=text size=15 id="signin-username">
               Password: <input type=password size=15 id="signin-password">
               <input type=submit value="Sign in" name="signin"><br>
               <a href="#" id="createaccount">Create account with one click</a>
               <div style="color:red" id="loginerror"></div>
           </form>
        </div> <!--signin-->
        <div style="clear:both"></div>
    </div> <!--menubar-->
    <div id="lower">
        <div id="createaccount-dialog" class=dialog style="width:400px">
            <h1>Create Account</h1>
            <p>
                An account will let you store your drawings on our server.
                There is no way to recover the password if you forget it.
            </p>
            <p id="createaccounterror" style="color:red"> </p>
            <form id="createaccountform" 
                action="javascript:Dialog.current().onCreate();void(0)">
                <table>
                    <tr>
                        <td>Username:</td>
                        <td>
                            <input id="userNameInput" tabindex=10>
                        </td>    
                    </tr>
                    <tr>
                        <td>Password:</td>
                        <td>
                            <input id="passwordInput1" type="password"
                            tabindex=11>
                        </td>    
                    </tr>
                    <tr>
                        <td>Re-enter password:</td>
                        <td>
                            <input id="passwordInput2" type="password"
                            tabindex=12>
                        </td>    
                    </tr>
                </table>
                <input name="submitButton" type="submit" value="Create Account"
                tabindex=13>
                <input name="cancelButton" type="button" value="Cancel"
                    onclick="Dialog.current().hide()" tabindex=14>
            </form>
        </div> <!--createaccount-->
        <div id="working" class="dialog">
            <h1 id="working-title">Working...</h1>
            <p>
                <img src="ajax-loader.gif"/>
            </p>
        </div>
        <div id="browse" class="dialog" style="width:700px;height:410px">
            <div id="browse-main">
                <div>
                    <div id="filelist"
                    style="display:inline;float:left;width:50%;overflow:scroll;height:300px"></div>
                    <canvas id="previewcanvas" style="border:1px dashed orange;float:left;padding-left:10px" width="300" height="300"></canvas>
                    <div style="clear:both"></div>
                </div>
                <form id="uploadform" method="post" style="padding-top:1em;text-align:left" action="index.php" target="uploadtarget" 
                    enctype="multipart/form-data" style="position:absolute; top:310px" id="importform">
                    Open from My Computer:
                    <input type="hidden" name="type" value="upload">
                    <input type="file" name="file" id="fileinput">
                </form>
                <div style="position:absolute;top:360px;width:100%;padding:10px">
                    <img style="float:left" src="wd-ok.png" onclick="Dialog.current().okayClicked()">
                    <img style="float:left" src="wd-cancel.png" onclick="Dialog.current().cancelClicked()">
                    <img id="deletebutton" style="float:right;margin-right:30px" src="wd-delete.png"
                        onclick="Dialog.current().deleteClicked()">
                </div>
            </div>
            <div id="browse-prompt" style="display:none">
                <h1>Delete file</h1>
                <p>
                    Are you sure you want to permanently delete this file?
                </p>
                <p align=center>
                    <img src="wd-ok.png" onclick="Dialog.current().okayClicked()">
                    <img src="wd-cancel.png" onclick="Dialog.current().cancelClicked()">
                </p>
            </div>
            <div id="browse-working" style="display:none">
                <h1>Deleting...</h1>
                <p>
                    <img src="ajax-loader.gif"/>
                </p>
            </div>
            <iframe style="display:none" id="uploadtarget" name="uploadtarget" src="">
            </iframe>
        </div> <!--browse-->
        <div id="message" class="dialog" style="display:none;width:350px">
            <h1 id="message-title">Message title</h1>
            <p id="message-text">Message text</p>
            <p>
                <img src="wd-ok.png" onclick="Dialog.current().okayClicked()">
            </p>
        </div> <!--message-->
        <div id="save" class="dialog" style="display:none;width:350px">
            <div id="save-normal">
                <h1>Save Drawing</h1>
                <p id="no-account-warning">You are not logged in, and your drawings will
                only be held for a few hours. They might be lost if you
                close your browser. Once you create an account, your
                drawing will be transferred to long-term storage on our server.</p>
                <p>Save as: <input id="savefilename" type="text"/></p>
                <p>Save to: <select id="savetype">
                    <option value="server">Zwibbler.com</option>
                    <option value="client">My Computer</option>
                </select></p>
                <p>
                    <img src="wd-ok.png" onclick="Dialog.current().okayClicked()">
                    <img src="wd-cancel.png" onclick="Dialog.current().cancelClicked()">
                </p>
                <p align=center style="font-size: smaller;color: gray">
                    Or download as 
                        <a href="javascript:app.downloadAs('png')">png</a> |
                        <a href="javascript:app.downloadAs('pdf')">pdf</a> |
                        <a href="javascript:app.downloadAs('svg')">svg</a>
                </p>
            </div>
            <div id="save-working" style="display:none">
                <h1>Saving...</h1>
                <p>
                    <img src="ajax-loader.gif"/>
                </p>
            </div>

            <div id="save-error" style="display:none">
                <h1>An error occured while saving</h1>
                <p>
                    Cryptic error code is: <span id="save-error-code"></span>
                </p>
                <p>
                    <img src="wd-ok.png" onclick="Dialog.current().cancelClicked()">
                </p>

            </div>
        </div> <!--save-->
        <div id="share" class="dialog" style="display:none;width:500px">
            <div id="share-not-enabled" style="display:none">
                <h1>Enable sharing</h1>
                <p>You have not yet shared this drawing.</p>
                <p align=center>
                    <input type="button" value="Share"
                        onclick="Dialog.current().shareClicked()">
                    <input type="button" value="Cancel"
                        onclick="Dialog.current().hide()">
                </p>
            </div>
            <div id="share-working" style="display:none">
                <h1>Updating...</h1>
                <p>
                    <img src="ajax-loader.gif"/>
                </p>
            </div>
            <div id="share-enabled" style="display:none">
                <h1>Share drawing</h1>
                <p>You can link to the drawing from blogs and other web sites.</p>
                <p>Link to image:<br>
                    <input id="share-png" type="text"
                        style="width: 350px"
                                onclick="this.select()"/>
                </p>
                <p>Link to PDF file:<br>
                    <input id="share-pdf" type="text"
                        style="width: 350px"
                                onclick="this.select()"/>
                </p>
                <p>Link to SVG file:<br>
                    <input id="share-svg" type="text"
                        style="width: 350px"
                                onclick="this.select()"/>
                </p>
                <p>
                    <img src="wd-ok.png" onclick="Dialog.current().hide()">
                    <img src="wd-unshare.png"
                    onclick="Dialog.current().unshareClicked()">
                </p>
            </div>
            <div id="save-error" style="display:none">
                <h1>An error occured while changing sharing</h1>
                <p>
                    Cryptic error code is: <span id="share-error-code"></span>
                </p>
                <p>
                    <img src="wd-ok.png" onclick="Dialog.current().hide()">
                </p>

            </div>
        </div> <!--share-->
        <div id=changePasswordDiv class="dialog" style="display:none;width:350px">
            <h1>Change Password</h1>
           <form action="javascript:Dialog.current().onsubmit();void(0)"
               id=changepasswordform>
               <div style="color:red" id="passwordError"></div>
               Choose your password carefully. There is <b>no way</b> to recover the
               password and sign in if you forget it.
               <table>
                <tr>
                    <td>Old Password:</td>
                    <td><input type=text size=15 name="oldpassword"></td>
                </tr>
               <tr>
                    <td>New Password:</td>
                    <td><input type=password size=15 name="newpassword1"></td>
                </tr>
               <tr>
                <td>Verify New Password:</td>
                <td><input type=password size=15 name="newpassword2"></td>
               </tr> 
               </table>
               <input type=submit value="Submit" name="submitButton">
               <input type=button value="Cancel" name="cancelButton"
                   onclick="Dialog.current().hide()"><br>
           </form>
        </div> <!--signin-->
        <div id="clipartDiv" class="dialog"
        style="display:none;width:90%;height:90%">
            <div id="clipart-heading">
            <h1>Insert Clipart</h1>
            <input type="text" value="halloween" id="clipart-text"><input type="button"
                value="Search" onclick="Dialog.current().onSearch()">
                <img id="clipart-working" src="ajax-loader.gif" height="30px"
                style="visibility:hidden"/>
            </div>
            <div id="clipartView">
            </div>
        </div> <!--clipart-->
        <div id="draw" style="width:100%"></div>
    </div> <!--lower-->
</div> <!-- main -->
<!--<textarea id=debugtext
style="position:absolute;top:100px;left:100px;width:500px;height:500px;z-index:4000"></textarea>-->
<div id="no-canvas" style="display:none">
<h1>HTML5 not supported</h1>
<p>
If your browser supported HTML5, you'd see something like this:
<p><img src="sample.png">
<p>
Why not <a href="http://www.google.com/chrome">download Google Chrome</a>, and experience what the web has to offer?
<p>
Here's some more information on <a
href="http://stevehanov.ca/blog/?id=93">Zwibbler</a>.
</div>
<div id="debug" style="clear:both"></div>
<script type="text/javascript" src="http://zwibbler.com/demo/jquery.min.js"></script>
<script type="text/javascript" src="http://zwibbler.com/demo/frontend.js"> </script>
<script type="text/javascript">
var app = createApp();
 
</script>

</body>
</html>

