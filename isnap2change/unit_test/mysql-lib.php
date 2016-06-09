<?php
    session_start();
    require_once("../connection.php");
    require_once("../debug.php");
    require_once("../mysql-lib.php");	      
    $conn = db_connect();
    echo '###########################<br>';
    echo 'UNIT TEST<br>';
    echo '###########################<br>';
    echo 'getStuQuizScore(\$conn, $quizID, $studentID)<br>';
    for($i=-1;$i<10;$i++){
        for($j=-1;$j<10;$j++){
            echo "getStuQuizScore(\$conn, $i, $j) ".getStuQuizScore($conn, $i, $j)."<br>";   
        }
    }	
    
    echo '###########################<br>';
    echo 'UNIT TEST<br>';
    echo '###########################<br>';
    echo 'getQuizPoints(\$conn, $quizID)<br>';
    for($i=-1;$i<10;$i++){
        echo "getQuizPoints(\$conn, $i) ".getQuizPoints($conn, $i)."<br>";   
    }	
    
    
    
    echo '###########################<br>';
    echo 'UNIT TEST<br>';
    echo '###########################<br>';
    echo 'calculateStudentScore(\$conn, $studentID)<br>';
    for($i=-1;$i<10;$i++){
        echo "calculateStudentScore(\$conn, $i) ".calculateStudentScore($conn, $i)."<br>";   
    }

    echo '###########################<br>';
    echo 'UNIT TEST<br>';
    echo '###########################<br>';
    echo 'setStudentScore($studentID)<br>';
    for($i=-1;$i<10;$i++){
        echo "setStudentScore(\$conn, $i) ".setStudentScore($conn, $i)."<br>";  
        echo getStudentScore($conn, $i),"<br>";
    }	
    
    echo '###########################<br>';
    echo 'UNIT TEST<br>';
    echo '###########################<br>';
    echo 'beginTransaction()<br>';
    echo 'getStudentScore(\$conn, 1) '.getStudentScore($conn, 1).'<br>';
    try{
        $studentID = 1;
        $conn->beginTransaction();              
        $make_err = false;
        
        $update_stmt = "UPDATE Student 
                SET Score = ?
                WHERE StudentID = ?";			
        $update_stmt = $conn->prepare($update_stmt);         
        $update_stmt->execute(array(100, $studentID)); 
        echo 'getStudentScore(\$conn, 1) '.getStudentScore($conn, 1).'<br>';
        
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
        echo 'getStudentScore(\$conn, 1) '.getStudentScore($conn, 1).'<br>';
        
        $conn->commit();                    
    } catch(PDOException $e) {
        debug_pdo_err($overviewName, $e);
        $conn->rollback();
    } 
    echo 'getStudentScore(\$conn, 1) '.getStudentScore($conn, 1).'<br>';
    
    db_close($conn); 
    
    
?>