<?php  	
    function getStuQuizScore($conn, $quizID, $studentID){
        $pointsBySection = array('MCQ', 'Matching', 'Poster', 'Misc');
        $pointsByQuestion = array('SAQ');
        $score = 0;
        
        $quizTypeSql = "SELECT COUNT(*) FROM Quiz NATURAL JOIN Quiz_Record WHERE QuizID = ? AND StudentID = ? AND `Status`='GRADED'";
        $quizTypeQuery = $conn->prepare($quizTypeSql);
        $quizTypeQuery->execute(array($quizID, $studentID));
        if($quizTypeQuery->fetchColumn() > 0){            
            $quizTypeSql = "SELECT * FROM Quiz NATURAL JOIN Quiz_Record WHERE QuizID = ? AND StudentID = ? AND `Status`='GRADED'";
            $quizTypeQuery = $conn->prepare($quizTypeSql);
            $quizTypeQuery->execute(array($quizID, $studentID));
            $quizTypeResult = $quizTypeQuery->fetch(PDO::FETCH_OBJ);    
            
            $quizType = $quizTypeResult->QuizType; 

            if(in_array($quizType, $pointsBySection)){
                $pointsSql = "SELECT * FROM Quiz NATURAL JOIN ".$quizType."_Section WHERE QuizID = ?";
                $pointsQuery = $conn->prepare($pointsSql);
                $pointsQuery->execute(array($quizID));
                $pointsResult = $pointsQuery->fetch(PDO::FETCH_OBJ);
                $score = $pointsResult->Points;
            } else if(in_array($quizType, $pointsByQuestion)){
                $pointsSql = "SELECT QuizID, StudentID, SUM(Grading) AS SumPoints FROM Quiz NATURAL JOIN SAQ_Section NATURAL JOIN SAQ_Question NATURAL JOIN SAQ_Question_Record WHERE QuizID = ? AND StudentID = ? ";
                $pointsQuery = $conn->prepare($pointsSql);
                $pointsQuery->execute(array($quizID, $studentID));
                $pointsResult = $pointsQuery->fetch(PDO::FETCH_OBJ);
                $score = $pointsResult->SumPoints;
            }
        }  
        
        return $score;
    }    
    
    function getQuizPoints($conn, $quizID){
        $pointsBySection = array('MCQ', 'Matching', 'Poster', 'Misc');
        $pointsByQuestion = array('SAQ');
        $points = 0;        
        
        $quizTypeSql = "SELECT COUNT(*) FROM Quiz WHERE QuizID = ?";
        $quizTypeQuery = $conn->prepare($quizTypeSql);
        $quizTypeQuery->execute(array($quizID));       
        if($quizTypeQuery->fetchColumn() > 0){
            $quizTypeSql = "SELECT QuizType FROM Quiz WHERE QuizID = ?";
            $quizTypeQuery = $conn->prepare($quizTypeSql);
            $quizTypeQuery->execute(array($quizID));
            $quizTypeResult = $quizTypeQuery->fetch(PDO::FETCH_OBJ);
            $quizType = $quizTypeResult->QuizType;
            
            if(in_array($quizType, $pointsBySection)){
                $pointsSql = "SELECT Points AS SumPoints FROM Quiz NATURAL JOIN ".$quizType."_Section WHERE QuizID = ?";
            } else if(in_array($quizType, $pointsByQuestion)){
                $pointsSql = "SELECT SUM(Points) AS SumPoints FROM Quiz NATURAL JOIN SAQ_Section NATURAL JOIN SAQ_Question WHERE QuizID = ?";
            }
            $pointsQuery = $conn->prepare($pointsSql);
            $pointsQuery->execute(array($quizID));
            $pointsResult = $pointsQuery->fetch(PDO::FETCH_OBJ);
            $points = $pointsResult->SumPoints;
        }
        
        return $points;
    } 
    
    function calculateStudentScore($conn, $studentID){
        $score = 0;
        
        $quizSql = "SELECT * FROM Quiz NATURAL JOIN Quiz_Record WHERE StudentID = ? AND `Status`='GRADED'";
        $quizQuery = $conn->prepare($quizSql);
        $quizQuery->execute(array($studentID));
        $quizResult = $quizQuery->fetchAll(PDO::FETCH_OBJ);
        for($i=0; $i<count($quizResult);$i++){
            $score+= getStuQuizScore($conn, $quizResult[$i]->QuizID, $studentID);
        }
        
        return $score;
    } 
    
    function getStudentScore($conn, $studentID){
        $score = 0;
        
        $scoreSql = "SELECT COUNT(*) FROM Student WHERE StudentID = ? ";
        $scoreQuery = $conn->prepare($scoreSql);
        $scoreQuery->execute(array($studentID));
        if($scoreQuery->fetchColumn() > 0){
            $scoreSql = "SELECT * FROM Student WHERE StudentID = ? ";
            $scoreQuery = $conn->prepare($scoreSql);
            $scoreQuery->execute(array($studentID));
            $scoreResult = $scoreQuery->fetch(PDO::FETCH_OBJ);
            $score = $scoreResult->Score;
        }
        
        return $score;
    }
    
    function setStudentScore($conn, $studentID){        
        $update_stmt = "UPDATE Student 
                SET Score = ?
                WHERE StudentID = ?";			
        $update_stmt = $conn->prepare($update_stmt);         
        $update_stmt->execute(array(calculateStudentScore($conn, $studentID), $studentID));        
    }
?>