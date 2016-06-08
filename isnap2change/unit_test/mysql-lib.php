<?php
    session_start();
    require_once("../connection.php");
    require_once("../debug.php");
    require_once("../mysql-lib.php");	      
    $conn = db_connect();
    echo '###########################<br>';
    echo 'UNIT TEST<br>';
    echo '###########################<br>';
    echo 'getStuQuizScore($quizID,$studentID)<br>';
    for($i=-1;$i<10;$i++){
        for($j=-1;$j<10;$j++){
            echo "getStuQuizScore($i,$j) ".getStuQuizScore($i,$j)."<br>";   
        }
    }	
    
    echo '###########################<br>';
    echo 'UNIT TEST<br>';
    echo '###########################<br>';
    echo 'getQuizPoints($quizID)<br>';
    for($i=-1;$i<10;$i++){
        echo "getQuizPoints($i) ".getQuizPoints($i)."<br>";   
    }	
    
    
    
    echo '###########################<br>';
    echo 'UNIT TEST<br>';
    echo '###########################<br>';
    echo 'getStudentScore($studentID)<br>';
    for($i=-1;$i<10;$i++){
        echo "getStudentScore($i) ".getStudentScore($i)."<br>";   
    }

    echo '###########################<br>';
    echo 'UNIT TEST<br>';
    echo '###########################<br>';
    echo 'updateStudentScore($studentID)<br>';
    for($i=-1;$i<10;$i++){
        echo "updateStudentScore($i) ".updateStudentScore($conn, $i)."<br>";  
        echo getStudentScore($i),"<br>";
    }	
    db_close($conn); 
    
?>