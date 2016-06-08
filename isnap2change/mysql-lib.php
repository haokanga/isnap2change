<?php  	
    function getStuQuizScore($quizID, $studentID){
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
    
    function getQuizPoints($quizID){
        $pointsBySection = array('MCQ', 'Matching', 'Poster', 'Misc');
        $pointsByQuestion = array('SAQ');
        $conn = db_connect();
        
        $quizTypeSql = "SELECT QuizType
        FROM Quiz WHERE QuizID = ?";
        $quizTypeQuery = $conn->prepare($quizTypeSql);
        $quizTypeQuery->execute(array($quizID));
        $quizTypeResult = $quizTypeQuery->fetch(PDO::FETCH_OBJ);
        $quizType = $quizTypeResult->QuizType;
        if(in_array($quizType, $pointsBySection)){
            $pointsSql = "SELECT * FROM Quiz NATURAL JOIN (SELECT QuizID, Points FROM MCQ_Section UNION SELECT QuizID, Points FROM Matching_Section UNION SELECT QuizID, Points FROM Poster_Section UNION SELECT QuizID, Points FROM Misc_Section ) AS QuizPoints WHERE QuizID = ?";
            $pointsQuery = $conn->prepare($pointsSql);
            $pointsQuery->execute(array($quizID));
            $pointsResult = $pointsQuery->fetch(PDO::FETCH_OBJ);
            if($pointsResult != null)
                $points = $pointsResult->Points;
            else $points = 0;
        } else if(in_array($quizType, $pointsByQuestion)){
            $pointsSql = "SELECT SUM(Points) AS SumPoints FROM Quiz NATURAL JOIN SAQ_Section NATURAL JOIN SAQ_Question WHERE QuizID = ?";
            $pointsQuery = $conn->prepare($pointsSql);
            $pointsQuery->execute(array($quizID));
            $pointsResult = $pointsQuery->fetch(PDO::FETCH_OBJ);
            $points = $pointsResult->SumPoints;
        }
        
        db_close($conn); 
        return $points;
    } 
    
        function getStudentScore($studentID){
        $pointsBySection = array('MCQ', 'Matching', 'Poster', 'Misc');
        $pointsByQuestion = array('SAQ');
        $conn = db_connect(); 
        
        $quizTypeSql = "SELECT QuizType
        FROM Quiz WHERE QuizID = ?";
        $quizTypeQuery = $conn->prepare($quizTypeSql);
        $quizTypeQuery->execute(array($quizid));
        $quizTypeResult = $quizTypeQuery->fetch(PDO::FETCH_OBJ);
        $quizType = $quizTypeResult->QuizType;
        if(in_array($quizType, $pointsBySection)){
            $pointsSql = "SELECT Points FROM Quiz NATURAL JOIN (SELECT QuizID, Points FROM MCQ_Section AS MCQPoints UNION SELECT QuizID , Points FROM Matching_Section AS MatchingPoints) AS QuizPoints WHERE QuizID = ?";
            $pointsQuery = $conn->prepare($pointsSql);
            $pointsQuery->execute(array($quizid));
            $pointsResult = $pointsQuery->fetch(PDO::FETCH_OBJ);
            if($pointsResult != null)
                $points = $pointsResult->Points;
            else $points = 0;
        } else if(in_array($quizType, $pointsByQuestion)){
            $pointsSql = "SELECT SUM(Points) AS SumPoints FROM Quiz NATURAL JOIN SAQ_Section NATURAL JOIN SAQ_Question WHERE QuizID = ?";
            $pointsQuery = $conn->prepare($pointsSql);
            $pointsQuery->execute(array($quizid));
            $pointsResult = $pointsQuery->fetch(PDO::FETCH_OBJ);
            $points = $pointsResult->SumPoints;
        }
        db_close($conn); 
        return $points;
    } 
?>