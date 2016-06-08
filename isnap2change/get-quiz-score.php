<?php  	
    function getQuizScore($quizID, $studentID){
        $pointsBySection = array('MCQ', 'Matching', 'Poster', 'Misc');
        $pointsByQuestion = array('SAQ');
        $conn = db_connect(); 
        $score = 0;
        
        $quizTypeSql = "SELECT * FROM Quiz NATURAL JOIN Quiz_Record WHERE QuizID = ? AND StudentID = ? AND `Status`='GRADED'";
        $quizTypeQuery = $conn->prepare($quizTypeSql);
        $quizTypeQuery->execute(array($quizID, $studentID));
        if($quizTypeQuery->fetchColumn() > 0){            
            $quizTypeSql = "SELECT * FROM Quiz NATURAL JOIN Quiz_Record WHERE QuizID = ? AND StudentID = ? AND `Status`='GRADED'";
            $quizTypeQuery = $conn->prepare($quizTypeSql);
            $quizTypeQuery->execute(array($quizID, $studentID));
            $quizTypeResult = $quizTypeQuery->fetch(PDO::FETCH_OBJ);
                    
            $quizType = $quizTypeResult->QuizType;  

            if(in_array($quizType, $pointsBySection)){
                $pointsSql = "SELECT * FROM Quiz NATURAL JOIN (SELECT QuizID, Points FROM MCQ_Section UNION SELECT QuizID, Points FROM Matching_Section UNION SELECT QuizID, Points FROM Poster_Section UNION SELECT QuizID, Points FROM Misc_Section ) AS QuizPoints WHERE QuizID = ?";
                $pointsQuery = $conn->prepare($pointsSql);
                $pointsQuery->execute(array($quizID));
                $pointsResult = $pointsQuery->fetch(PDO::FETCH_OBJ);
                if($pointsResult != null)
                    $score = $pointsResult->Points;
            } else if(in_array($quizType, $pointsByQuestion)){
                $pointsSql = "SELECT QuizID, StudentID, SUM(Grading) AS SumPoints FROM Quiz NATURAL JOIN SAQ_Section NATURAL JOIN SAQ_Question NATURAL JOIN SAQ_Question_Record WHERE QuizID = ? AND StudentID = ? ";
                $pointsQuery = $conn->prepare($pointsSql);
                $pointsQuery->execute(array($quizID, $studentID));
                $pointsResult = $pointsQuery->fetch(PDO::FETCH_OBJ);
                if($pointsResult != null)
                    $score = $pointsResult->SumPoints;
            }
        }        
        
        db_close($conn); 
        return $score;
    }    
?>