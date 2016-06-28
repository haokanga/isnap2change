<?php
    require_once('mysql-lib.php');
    require_once('debug.php');
    $pageName = "signup-feedback";

    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        if(isset($_POST["action"])){
            $action = $_POST["action"];

            if($action == "VALIDTOKEN"){
                if($_POST["token"]){
                    $token = $_POST["token"];
                } else{

                }
            }

            if($action == "VALIDUSERNAME"){
                if($_POST["username"]){

                } else{

                }
            }
        } else {

        }
    } else {

    }

    
?>

