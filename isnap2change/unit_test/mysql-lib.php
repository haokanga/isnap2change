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
    echo 'calculateStudentScore($studentID)<br>';
    for($i=-1;$i<10;$i++){
        echo "calculateStudentScore($i) ".calculateStudentScore($i)."<br>";   
    }

    echo '###########################<br>';
    echo 'UNIT TEST<br>';
    echo '###########################<br>';
    echo 'setStudentScore($studentID)<br>';
    for($i=-1;$i<10;$i++){
        echo "setStudentScore($i) ".setStudentScore($conn, $i)."<br>";  
        echo getStudentScore($i),"<br>";
    }	
    
    echo '###########################<br>';
    echo 'UNIT TEST<br>';
    echo '###########################<br>';
    echo 'beginTransaction()<br>';
    echo 'getStudentScore(1) '.getStudentScore(1).'<br>';
    try{
        $studentID = 1;
        $conn->beginTransaction();              
        $make_err = false;
        
        $update_stmt = "UPDATE Student 
                SET Score = ?
                WHERE StudentID = ?";			
        $update_stmt = $conn->prepare($update_stmt);         
        $update_stmt->execute(array(100, $studentID)); 
        echo 'getStudentScore(1) '.getStudentScore(1).'<br>';
        
        if($make_err){
            $overviewName = 'mysql-lib';
            $schoolName = 'Sample School';                 
            $update_stmt = "INSERT INTO School(SchoolName)
             VALUES (?);";			
            $update_stmt = $conn->prepare($update_stmt);                
            $update_stmt->execute(array($schoolName));
        }    
        $update_stmt = "UPDATE Student 
                SET Score = ?
                WHERE StudentID = ?";			
        $update_stmt = $conn->prepare($update_stmt);         
        $update_stmt->execute(array(1000, $studentID));          
        echo 'getStudentScore(1) '.getStudentScore(1).'<br>';
        
        $conn->commit();                    
    } catch(Exception $e) {
        debug_pdo_err($overviewName, $e);
        $conn->rollback();
    } 
    echo 'getStudentScore(1) '.getStudentScore(1).'<br>';
    
    db_close($conn); 
    
    
?>