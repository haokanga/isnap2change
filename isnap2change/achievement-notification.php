<?php
	//if true, echo debug output in dev mode, else production mode
	$DEBUG_MODE = false;
	session_start();
    if($DEBUG_MODE && !isset($_SESSION["studentid"])){
        $_SESSION["studentid"] = 1;
    }

?>

<html>
    <head>
        <title>Achievement</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script type="text/javascript" src="js/jquery-1.12.3.js"></script>
        <script type="text/javascript" src="js/notify.min.js"></script>
        <link href="css/notify-metro.css" rel="stylesheet" />
        <script src="js/notify-metro.js"></script>
    </head>
    <body>
    <img src='img/achievement/diploma-2-52.png'/>
    <script>
    $(document).ready(function(){
        $.notify({
            title: "Head First SNAP!",
            text: "Welcome to iSNAP2Change!",
            image: "<img src='img/achievement/diploma-2-52.png'/>"
        }, {
            style: 'metro',
            position: "bottom right",
            className: "white",
            clickToHide: true,            
            showDuration: 300,
            hideDuration: 1000
        });
    });   
    
    </script>
    </body>
</html>
