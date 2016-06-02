<?php  	
    function getQuizPoints($quizid){
        $pointsBySection = array('MCQ', 'Matching', 'Calculator', 'Poster');
        $pointsByQuestion = array('SAQ');
        $conn = db_connect(); 
        // get student
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