<?php  	
    /*
    ```Naming Convention```
    
    create: INSERT
    update: UPDATE
    delete: DELETE
    get...: SELECT fetch
    get...s: SELECT fetchAll
    
    ```variables order```
    $conn, $pkCol(alphabetical order), $non-pkCol(alphabetical order)
    e.g. $conn, $questionID, $studentID, $status, $week
    
    ```Function Order```
    create
    update
    delete
    get...
    get...s
    misc
    
    */

    /* db connection*/
    function db_connect(){

        $conn;

        $servername = "localhost";
        $username = "root";
        $password = ".kHdGCD2Un%P";

        $conn = new PDO("mysql:host=$servername; dbname=isnap2changedb", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
        return $conn;
    
    }

    function db_close($connection){
        $connection = null;
    }
    /* db connection*/
         
    /* School */
    function createSchool($conn, $schoolName){                
        $updateSql = "INSERT INTO School(SchoolName)
         VALUES (?)";			
        $updateSql = $conn->prepare($updateSql);                
        $updateSql->execute(array($schoolName));
        return $conn->lastInsertId();
    }
    
    function updateSchool($conn, $schoolID, $schoolName){
        $updateSql = "UPDATE School 
            SET SchoolName = ?
            WHERE SchoolID = ?";			
        $updateSql = $conn->prepare($updateSql);                            
        $updateSql->execute(array($schoolName, $schoolID));
    }
    
    function deleteSchool($conn, $schoolID){
        $updateSql = "DELETE FROM School WHERE SchoolID = ?";			
        $updateSql = $conn->prepare($updateSql);
        $updateSql->execute(array($schoolID));
    }
    
    function getSchool($conn, $schoolID){
        $schoolSql = "SELECT SchoolName
                   FROM School WHERE SchoolID = ?";
        $schoolQuery = $conn->prepare($schoolSql);
        $schoolQuery->execute(array($schoolID));
        $schoolResult = $schoolQuery->fetch(PDO::FETCH_OBJ);
        return $schoolResult;
    }
    
    function getSchoolByName($conn, $schoolName){
        $schoolSql = "SELECT SchoolID
                   FROM School WHERE SchoolName = ?";
        $schoolQuery = $conn->prepare($schoolSql);
        $schoolQuery->execute(array($schoolName));
        $schoolResult = $schoolQuery->fetch(PDO::FETCH_OBJ);
        return $schoolResult;
    }
    
    function getSchools($conn){
        $schoolSql = "SELECT SchoolID, SchoolName
                   FROM School";
        $schoolQuery = $conn->prepare($schoolSql);
        $schoolQuery->execute();
        $schoolResult = $schoolQuery->fetchAll(PDO::FETCH_OBJ);
        return $schoolResult;
    }
    /* School */    
    
    /* Class */
    function createClass($conn, $schoolID, $className){                
        $updateSql = "INSERT INTO Class(ClassName, SchoolID)
             VALUES (?,?)";			
        $updateSql = $conn->prepare($updateSql);         
        $updateSql->execute(array($className, $schoolID));
        return $conn->lastInsertId();
    }
    
    function updateClass($conn, $classID, $schoolID, $className){
         $updateSql = "UPDATE Class 
            SET ClassName = ?, SchoolID = ?
            WHERE ClassID = ?";			
        $updateSql = $conn->prepare($updateSql);                            
        $updateSql->execute(array($className, $schoolID, $classID));
    }
    
    function deleteClass($conn, $classID){
        $updateSql = "DELETE FROM Class WHERE ClassID = ?";			
        $updateSql = $conn->prepare($updateSql);
        $updateSql->execute(array($classID));
    }
    
    function getClass($conn, $classID){
        $classSql = "SELECT *
                   FROM Class WHERE ClassID = ?";
        $classQuery = $conn->prepare($classSql);
        $classQuery->execute(array($classID));
        $classResult = $classQuery->fetch(PDO::FETCH_OBJ);
        return $classResult;
    }
    
    function getClassByName($conn, $className){
        $classSql = "SELECT *
                   FROM Class WHERE ClassName = ?";
        $classQuery = $conn->prepare($classSql);
        $classQuery->execute(array($className));
        $classResult = $classQuery->fetch(PDO::FETCH_OBJ);
        return $classResult;
    }
    
    function getClassNum($conn){
        $classNumSql = "SELECT count(*) as Count, SchoolID
                   FROM School NATURAL JOIN Class
                   GROUP BY SchoolID";
        $classNumQuery = $conn->prepare($classNumSql);
        $classNumQuery->execute();
        $classNumResult = $classNumQuery->fetchAll(PDO::FETCH_OBJ);
        return $classNumResult;
    }
    
    function getClasses($conn){
        $classSql = "SELECT *
                   FROM Class NATURAL JOIN School";
        $classQuery = $conn->prepare($classSql);
        $classQuery->execute();
        $classResult = $classQuery->fetchAll(PDO::FETCH_OBJ);
        return $classResult;
    }    
    
    function getStudentNum($conn){
        $studentNumSql = "SELECT count(*) as Count, ClassID
                   FROM   Student NATURAL JOIN Class
                   GROUP BY ClassID";
        $studentNumQuery = $conn->prepare($studentNumSql);
        $studentNumQuery->execute();
        $studentNumResult = $studentNumQuery->fetchAll(PDO::FETCH_OBJ);
        return $studentNumResult;
    }
    
    function updateUnlockedProgress($conn, $classID, $unlockedProgress){
        $updateSql = "UPDATE Class 
            SET UnlockedProgress = ?
            WHERE ClassID = ?";			
        $updateSql = $conn->prepare($updateSql);                            
        $updateSql->execute(array($unlockedProgress, $classID));
    }
    /* Class */
    
    /* Token */    
    function updateToken($conn, $classID, $tokenString, $type){
        $updateSql = "INSERT INTO Token(ClassID, `Type`, TokenString)
                                    VALUES (?,?,?) ON DUPLICATE KEY UPDATE TokenString = ?";			
        $updateSql = $conn->prepare($updateSql);                            
        $updateSql->execute(array($classID, $type, $tokenString, $tokenString));
    }
    
    function getTokens($conn){
        $tokenSql = "SELECT ClassID, `Type`, TokenString
                   FROM Token NATURAL JOIN Class";
        $tokenQuery = $conn->prepare($tokenSql);
        $tokenQuery->execute();
        $tokenResult = $tokenQuery->fetchAll(PDO::FETCH_OBJ); 
        return $tokenResult;
    } 
    /* Token */
    
    /* Student */   
    function deleteStudent($conn, $studentID){
        $updateSql = "DELETE FROM Student WHERE StudentID = ?";			
        $updateSql = $conn->prepare($updateSql);
        $updateSql->execute(array($studentID));
    }
    
    function getStudents($conn){        
        $studentSql = "SELECT * , DATE(SubmissionTime) AS SubmissionDate FROM Student NATURAL JOIN Class
               ORDER BY ClassID";
        $studentQuery = $conn->prepare($studentSql);
        $studentQuery->execute();
        $studentResult = $studentQuery->fetchAll(PDO::FETCH_OBJ);  
        return $studentResult;
    }
    
    function resetPassword($conn, $studentID){
        $updateSql = "UPDATE Student 
            SET Password = ?
            WHERE StudentID = ?";			
        $updateSql = $conn->prepare($updateSql);                            
        $updateSql->execute(array(md5('WelcomeToiSNAP2'), $studentID));
    }    
    /* Student */
    
    /* Week */
    function removeWeek($conn, $week){
        $updateSql = "SET SQL_SAFE_UPDATES=0;
            UPDATE Quiz SET Week = NULL WHERE Week = ?;
            SET SQL_SAFE_UPDATES=1";			
        $updateSql = $conn->prepare($updateSql);                            
        $updateSql->execute(array($week));
        return $updateSql;
    }
    
    function getMaxWeek($conn){
        $weekSql = "select MAX(Week) as WeekNum from Quiz";
        $weekQuery = $conn->prepare($weekSql);
        $weekQuery->execute();
        $weekResult = $weekQuery->fetch(PDO::FETCH_OBJ);
        return $weekResult;
    }
    /* Week */
       
    /* Quiz */
    function createQuiz($conn, $topicID, $quizType, $week){
        $updateSql = "INSERT INTO Quiz(Week, QuizType, TopicID)
             VALUES (?,?,?)";			
        $updateSql = $conn->prepare($updateSql);         
        $updateSql->execute(array($week, $quizType, $topicID));                     
        return $conn->lastInsertId(); 
    }
    
    function updateQuiz($conn, $quizID, $topicID, $week){
        $updateSql = "UPDATE Quiz 
                SET Week = ?, TopicID = ?
                WHERE QuizID = ?";			
        $updateSql = $conn->prepare($updateSql);         
        $updateSql->execute(array($week, $topicID, $quizID)); 
    }
    
    function deleteQuiz($conn, $quizID){
        $updateSql = "DELETE FROM Quiz WHERE QuizID = ?";			
        $updateSql = $conn->prepare($updateSql);
        $updateSql->execute(array($quizID));
    }
    
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
                $pointsSql = "SELECT QuizID, StudentID, SUM(Grading) AS SumPoints FROM Quiz NATURAL JOIN SAQ_Section NATURAL JOIN SAQ_Question NATURAL JOIN SAQ_Question_Record WHERE QuizID = ? AND StudentID = ?";
                $pointsQuery = $conn->prepare($pointsSql);
                $pointsQuery->execute(array($quizID, $studentID));
                $pointsResult = $pointsQuery->fetch(PDO::FETCH_OBJ);
                $score = $pointsResult->SumPoints;
            }
        }  
        
        return $score;
    } 
    
    function getQuizNum($conn){
        $weekSql = "SELECT Week, COUNT(*) AS QuizNum FROM Quiz GROUP BY Week";
        $weekQuery = $conn->prepare($weekSql);
        $weekQuery->execute();
        $weekResult = $weekQuery->fetchAll(PDO::FETCH_OBJ); 
        return $weekResult;
    }
    
    function getQuizzes($conn){
        $quizSql = "SELECT QuizID, Week, QuizType, TopicName
                   FROM Quiz NATURAL JOIN Topic";
        $quizQuery = $conn->prepare($quizSql);
        $quizQuery->execute();
        $quizResult = $quizQuery->fetchAll(PDO::FETCH_OBJ);
        return $quizResult;
    }
    
    function getQuizzesByWeek($conn, $week){
        $quizSql = "SELECT QuizID, Week, QuizType, TopicName
                   FROM Quiz NATURAL JOIN Topic WHERE Week = ?";
        $quizQuery = $conn->prepare($quizSql);
        $quizQuery->execute(array($week));
        $quizResult = $quizQuery->fetchAll(PDO::FETCH_OBJ);
        return $quizResult;
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
    /* Quiz */
    
    /* Topic */    
    function getTopic($conn, $topicID){
        $topicSql = "SELECT * FROM Topic WHERE TopicID = ?";
        $topicQuery = $conn->prepare($topicSql);
        $topicQuery->execute(array($topicID));
        $topicResult = $topicQuery->fetch(PDO::FETCH_OBJ);
        return $topicResult;
    }

    function getTopicByName($conn, $topicName){
        $topicSql = "SELECT * FROM Topic WHERE TopicName = ?";
        $topicQuery = $conn->prepare($topicSql);
        $topicQuery->execute(array($topicName));
        $topicResult = $topicQuery->fetch(PDO::FETCH_OBJ);
        return $topicResult;
    }
    
    function getTopics($conn){
        $topicSql = "SELECT * FROM Topic ORDER BY TopicID";
        $topicQuery = $conn->prepare($topicSql);
        $topicQuery->execute(array());
        $topicResult = $topicQuery->fetchAll(PDO::FETCH_OBJ); 
        return $topicResult;
    }
    /* Topic */
    
    /* MCQ */    
    function createMCQSection($conn, $quizID, $points, $questionnaires){
        $updateSql = "INSERT INTO MCQ_Section(QuizID, Points, Questionnaires)
                    VALUES (?,?,?)";			
        $updateSql = $conn->prepare($updateSql);                            
        $updateSql->execute(array($quizID, $points, $questionnaires)); 
    }
    
    function updateMCQSection($conn, $quizID, $points, $questionnaires){
        $updateSql = "UPDATE MCQ_Section
                    SET Points = ?, Questionnaires = ?
                    WHERE QuizID = ?";			
        $updateSql = $conn->prepare($updateSql);                            
        $updateSql->execute(array($points, $questionnaires, $quizID));
    }
    
    function createMCQQuestion($conn, $quizID, $question){
        $updateSql = "INSERT INTO MCQ_Question(Question, QuizID)
                    VALUES (?,?)";			
        $updateSql = $conn->prepare($updateSql);                            
        $updateSql->execute(array($question, $quizID)); 
        return $conn->lastInsertId();
    }
    
    function updateMCQQuestion($conn, $mcqID, $correctChoice, $question){
        $updateSql = "UPDATE MCQ_Question
                    SET Question = ?, CorrectChoice = ?
                    WHERE MCQID = ?";			
        $updateSql = $conn->prepare($updateSql);                            
        $updateSql->execute(array($question, $correctChoice, $mcqID));
    }
    
    function deleteMCQQuestion($conn, $mcqID){
        $updateSql = "DELETE FROM MCQ_Question WHERE MCQID = ?";			
        $updateSql = $conn->prepare($updateSql);
        $updateSql->execute(array($mcqID));
    }
    
    function getMCQQuestion($conn, $mcqID){
        $mcqQuesSql = "SELECT * FROM MCQ_Question WHERE MCQID = ?";                                
        $mcqQuesQuery = $conn->prepare($mcqQuesSql);
        $mcqQuesQuery->execute(array($mcqID));
        $mcqQuesResult = $mcqQuesQuery->fetch(PDO::FETCH_OBJ);
        return $mcqQuesResult;
    } 
    
    function getMCQQuestions($conn, $quizID){
        $mcqQuesSql = "SELECT *
                    FROM MCQ_Section NATURAL JOIN MCQ_Question
                    LEFT JOIN `Option` USING (MCQID)
                    WHERE QuizID = ?
                    ORDER BY MCQID";                                
        $mcqQuesQuery = $conn->prepare($mcqQuesSql);
        $mcqQuesQuery->execute(array($quizID));
        $mcqQuesResult = $mcqQuesQuery->fetchAll(PDO::FETCH_OBJ); 
        return $mcqQuesResult;
    }
    
    function getMCQQuiz($conn, $quizID){
        $quizSql = "SELECT *, COUNT(MCQID) AS Questions
                   FROM Quiz NATURAL JOIN Topic NATURAL JOIN MCQ_Section LEFT JOIN MCQ_Question USING (QuizID) WHERE QuizID = ? GROUP BY QuizID";
        $quizQuery = $conn->prepare($quizSql);
        $quizQuery->execute(array($quizID));
        $quizResult = $quizQuery->fetch(PDO::FETCH_OBJ);
        return $quizResult;
    }
    
    function getMCQQuizzes($conn){
        $quizSql = "SELECT *, COUNT(MCQID) AS Questions
                   FROM Quiz NATURAL JOIN Topic NATURAL JOIN MCQ_Section LEFT JOIN MCQ_Question USING (QuizID) WHERE QuizType = 'MCQ' GROUP BY QuizID";
        $quizQuery = $conn->prepare($quizSql);
        $quizQuery->execute();
        $quizResult = $quizQuery->fetchAll(PDO::FETCH_OBJ); 
        return $quizResult;
    }    
    /* MCQ */
    
    /* Option */
    function createOption($conn, $mcqID, $content, $explanation){
        $updateSql = "INSERT INTO `Option`(Content, Explanation, MCQID)
             VALUES (?,?,?)";
        $updateSql = $conn->prepare($updateSql);         
        $updateSql->execute(array($content, $explanation, $mcqID));
        return $conn->lastInsertId(); 
    }
    
    function updateOption($conn, $optionID, $content, $explanation){
        $updateSql = "UPDATE `Option` 
                SET Content = ?, Explanation = ?
                WHERE OptionID = ?";			
        $updateSql = $conn->prepare($updateSql);         
        $updateSql->execute(array($content, $explanation, $optionID)); 
    }
    
    function deleteOption($conn, $optionID){
        $updateSql = "DELETE FROM `Option` WHERE OptionID = ?";			
        $updateSql = $conn->prepare($updateSql);
        $updateSql->execute(array($optionID));
    }
    function getOptions($conn, $mcqID){
        $optionSql = "SELECT *
                   FROM MCQ_Question NATURAL JOIN `Option` WHERE MCQID = ?";
        $optionQuery = $conn->prepare($optionSql);
        $optionQuery->execute(array($mcqID));
        $optionResult = $optionQuery->fetchAll(PDO::FETCH_OBJ); 
        return $optionResult;
    }
    
    function getMaxOptionNum($conn, $quizID){
        $optionNumSql = "SELECT MAX(OptionNum) AS MaxOptionNum FROM (SELECT COUNT(*) AS OptionNum FROM MCQ_Question natural JOIN `Option` WHERE QuizID = ? GROUP BY MCQID) AS OptionNumTable";								
        $optionNumQuery = $conn->prepare($optionNumSql);
        $optionNumQuery->execute(array($quizID));
        $optionNumResult = $optionNumQuery->fetch(PDO::FETCH_OBJ);
        return $optionNumResult;
    }
    /* Option */
    
    /* SAQ */    
    function createSAQSection($conn, $quizID){
        $updateSql = "INSERT INTO SAQ_Section(QuizID)
                    VALUES (?)";			
        $updateSql = $conn->prepare($updateSql);                            
        $updateSql->execute(array($quizID)); 
    }
    
    function createSAQQuestion($conn, $quizID, $points, $question){
        $updateSql = "INSERT INTO SAQ_Question(Question, Points, QuizID)
                    VALUES (?,?,?)";			
        $updateSql = $conn->prepare($updateSql);                            
        $updateSql->execute(array($question, $points, $quizID)); 
        return $conn->lastInsertId();
    }
    
    function updateSAQQuestion($conn, $saqID, $points, $question){
        $updateSql = "UPDATE SAQ_Question
                    SET Question = ?, Points = ?
                    WHERE SAQID = ?";			
        $updateSql = $conn->prepare($updateSql);                            
        $updateSql->execute(array($question, $points, $saqID));
    }
    
    function deleteSAQQuestion($conn, $saqID){
        $updateSql = "DELETE FROM SAQ_Question WHERE SAQID = ?";			
        $updateSql = $conn->prepare($updateSql);
        $updateSql->execute(array($saqID));
    }
    
    function getSAQQuestion($conn, $saqID){
        $saqQuesSql = "SELECT * FROM SAQ_Question WHERE SAQID = ?";                                
        $saqQuesQuery = $conn->prepare($saqQuesSql);
        $saqQuesQuery->execute(array($saqID));
        $saqQuesResult = $saqQuesQuery->fetch(PDO::FETCH_OBJ);
        return $saqQuesResult;
    } 
    
    function getSAQQuestions($conn, $quizID){
        $saqQuesSql = "SELECT *
                    FROM SAQ_Section NATURAL JOIN SAQ_Question
                    WHERE QuizID = ?
                    ORDER BY SAQID";                                
        $saqQuesQuery = $conn->prepare($saqQuesSql);
        $saqQuesQuery->execute(array($quizID));
        $saqQuesResult = $saqQuesQuery->fetchAll(PDO::FETCH_OBJ); 
        return $saqQuesResult;
    }
    
    function getSAQQuiz($conn, $quizID){
        $quizSql = "SELECT QuizID, TopicID, Week, QuizType, TopicName, SAQID, SUM(Points) AS Points, COUNT(SAQID) AS Questions
                   FROM Quiz NATURAL JOIN Topic NATURAL JOIN SAQ_Section LEFT JOIN SAQ_Question USING (QuizID) WHERE QuizID = ? GROUP BY QuizID";
        $quizQuery = $conn->prepare($quizSql);
        $quizQuery->execute(array($quizID));
        $quizResult = $quizQuery->fetch(PDO::FETCH_OBJ);
        return $quizResult;
    }
    
    function getSAQQuizzes($conn){
        $quizSql = "SELECT QuizID, TopicID, Week, QuizType, TopicName, SAQID, SUM(Points) AS Points, COUNT(SAQID) AS Questions
                   FROM Quiz NATURAL JOIN Topic NATURAL JOIN SAQ_Section LEFT JOIN SAQ_Question USING (QuizID) WHERE QuizType = 'SAQ' GROUP BY QuizID";
        $quizQuery = $conn->prepare($quizSql);
        $quizQuery->execute();
        $quizResult = $quizQuery->fetchAll(PDO::FETCH_OBJ); 
        return $quizResult;
    }    
    /* SAQ */
    
    /* Matching */    
    function createMatchingSection($conn, $quizID, $description, $points){
        $updateSql = "INSERT INTO Matching_Section(QuizID, Description, Points)
                    VALUES (?,?,?)";			
        $updateSql = $conn->prepare($updateSql);                            
        $updateSql->execute(array($quizID, $description, $points)); 
    }
    
    function updateMatchingSection($conn, $quizID, $description, $points){
        $updateSql = "UPDATE Matching_Section
                    SET Description = ?, Points = ?
                    WHERE QuizID = ?";			
        $updateSql = $conn->prepare($updateSql);                            
        $updateSql->execute(array($description, $points, $quizID));
    }
    
    function getMatchingSection($conn, $quizID){
        $matchingSectionSql = "SELECT *
                   FROM   Matching_Section
                   WHERE  QuizID = ?";
        $matchingSectionQuery = $conn->prepare($matchingSectionSql);
        $matchingSectionQuery->execute(array($quizID));
        $matchingSectionResult = $matchingSectionQuery->fetch(PDO::FETCH_OBJ);
        return $matchingSectionResult;
    } 
    
    function createMatchingQuestion($conn, $quizID, $question){
        $updateSql = "INSERT INTO Matching_Question(Question, QuizID)
                    VALUES (?,?)";			
        $updateSql = $conn->prepare($updateSql);                            
        $updateSql->execute(array($question, $quizID)); 
        return $conn->lastInsertId();
    }
    
    function updateMatchingQuestion($conn, $matchingID, $question){
        $updateSql = "UPDATE Matching_Question
                    SET Question = ?
                    WHERE MatchingID = ?";			
        $updateSql = $conn->prepare($updateSql);                            
        $updateSql->execute(array($question, $matchingID));
    }
    
    function deleteMatchingQuestion($conn, $matchingID){
        $updateSql = "DELETE FROM Matching_Question WHERE MatchingID = ?";			
        $updateSql = $conn->prepare($updateSql);
        $updateSql->execute(array($matchingID));
    }
    
    function getMatchingQuestion($conn, $matchingID){
        $matchingQuesSql = "SELECT * FROM Matching_Question WHERE MatchingID = ?";                                
        $matchingQuesQuery = $conn->prepare($matchingQuesSql);
        $matchingQuesQuery->execute(array($matchingID));
        $matchingQuesResult = $matchingQuesQuery->fetch(PDO::FETCH_OBJ);
        return $matchingQuesResult;
    } 
    
    function getMatchingQuestions($conn, $quizID){
        $matchingQuesSql = "SELECT *
                    FROM Matching_Section NATURAL JOIN Matching_Question
                    LEFT JOIN Matching_Option USING (MatchingID)
                    WHERE QuizID = ?
                    ORDER BY MatchingID";                                
        $matchingQuesQuery = $conn->prepare($matchingQuesSql);
        $matchingQuesQuery->execute(array($quizID));
        $matchingQuesResult = $matchingQuesQuery->fetchAll(PDO::FETCH_OBJ); 
        return $matchingQuesResult;
    }
    
    function getMatchingBuckets($conn, $quizID){
        $matchingQuesSql = "SELECT *
                    FROM Matching_Section NATURAL JOIN Matching_Question
                    WHERE QuizID = ?
                    ORDER BY MatchingID";                                
        $matchingQuesQuery = $conn->prepare($matchingQuesSql);
        $matchingQuesQuery->execute(array($quizID));
        $matchingQuesResult = $matchingQuesQuery->fetchAll(PDO::FETCH_OBJ); 
        return $matchingQuesResult;
    }
    
    function getMatchingQuiz($conn, $quizID){
        $quizSql = "SELECT *, COUNT(MatchingID) AS Questions
                   FROM Quiz NATURAL JOIN Topic NATURAL JOIN Matching_Section LEFT JOIN Matching_Question USING (QuizID) WHERE QuizID = ?";
        $quizQuery = $conn->prepare($quizSql);
        $quizQuery->execute(array($quizID));
        $quizResult = $quizQuery->fetch(PDO::FETCH_OBJ);
        return $quizResult;
    }
    
    function getMatchingQuizzes($conn){
        $quizSql = "SELECT *, COUNT(MatchingID) AS Questions
                   FROM Quiz NATURAL JOIN Topic NATURAL JOIN Matching_Section LEFT JOIN Matching_Question USING (QuizID) WHERE QuizType = 'Matching' GROUP BY QuizID";
        $quizQuery = $conn->prepare($quizSql);
        $quizQuery->execute();
        $quizResult = $quizQuery->fetchAll(PDO::FETCH_OBJ); 
        return $quizResult;
    }    
    /* Matching */
    
    /* Matching_Option */
    function createMatchingOption($conn, $matchingID, $content){
        $updateSql = "INSERT INTO Matching_Option(Content, MatchingID)
             VALUES (?,?)";
        $updateSql = $conn->prepare($updateSql);         
        $updateSql->execute(array($content, $matchingID));
        return $conn->lastInsertId(); 
    }
    
    function updateMatchingOption($conn, $matchingID, $optionID, $content){
        $updateSql = "UPDATE Matching_Option 
                SET Content = ?, MatchingID = ?
                WHERE OptionID = ?";			
        $updateSql = $conn->prepare($updateSql);         
        $updateSql->execute(array($content, $matchingID, $optionID)); 
    }
    
    function deleteMatchingOption($conn, $optionID){
        $updateSql = "DELETE FROM Matching_Option WHERE OptionID = ?";			
        $updateSql = $conn->prepare($updateSql);
        $updateSql->execute(array($optionID));
    }
    
    function getMaxMatchingOptionNum($conn, $quizID){
        $optionNumSql = "SELECT MAX(OptionNum) AS MaxOptionNum FROM (SELECT COUNT(*) AS OptionNum FROM Matching_Question natural JOIN Matching_Option WHERE QuizID = ? GROUP BY MatchingID) AS OptionNumTable";								
        $optionNumQuery = $conn->prepare($optionNumSql);
        $optionNumQuery->execute(array($quizID));
        $optionNumResult = $optionNumQuery->fetch(PDO::FETCH_OBJ);
        return $optionNumResult->MaxOptionNum;
    }
    /* Matching_Option */
    
    /* Learning_Material */
    function createEmptyLearningMaterial($conn, $quizID){
        $content='<p>Learning materials for this quiz has not been added.</p>';
        $updateSql = "INSERT INTO Learning_Material(Content,QuizID) VALUES (?,?)";
        $updateSql = $conn->prepare($updateSql);                            
        $updateSql->execute(array($content, $quizID));
    }
    
    function updateLearningMaterial($conn, $quizID, $content){
        $updateSql = "UPDATE Learning_Material 
            SET Content = ?
            WHERE QuizID = ?";			
        $updateSql = $conn->prepare($updateSql);                            
        $updateSql->execute(array($content, $quizID));   
    }
    
    function getLearningMaterial($conn, $quizID){
        $materialPreSql = "SELECT COUNT(*) 
                       FROM   Learning_Material
                       WHERE  QuizID = ?";							
        $materialPreQuery = $conn->prepare($materialPreSql);
        $materialPreQuery->execute(array($quizID));                
        if($materialPreQuery->fetchColumn() != 1){
			throw new Exception("Failed to get learning material");
        }
		
        $materialSql = "SELECT * 
                        FROM   Learning_Material NATURAL JOIN Quiz
                                                 NATURAL JOIN Topic
                        WHERE  QuizID = ?";
                                
        $materialQuery = $conn->prepare($materialSql);
        $materialQuery->execute(array($quizID));
        $materialRes = $materialQuery->fetch(PDO::FETCH_OBJ);
        return $materialRes;
    }
    /* Learning_Material */
    
    
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
        
        $scoreSql = "SELECT COUNT(*) FROM Student WHERE StudentID = ?";
        $scoreQuery = $conn->prepare($scoreSql);
        $scoreQuery->execute(array($studentID));
        if($scoreQuery->fetchColumn() > 0){
            $scoreSql = "SELECT * FROM Student WHERE StudentID = ?";
            $scoreQuery = $conn->prepare($scoreSql);
            $scoreQuery->execute(array($studentID));
            $scoreResult = $scoreQuery->fetch(PDO::FETCH_OBJ);
            $score = $scoreResult->Score;
        }
        
        return $score;
    }
    
    function updateStudentScore($conn, $studentID){        
        $updateSql = "UPDATE Student 
                SET Score = ?
                WHERE StudentID = ?";			
        $updateSql = $conn->prepare($updateSql);         
        $updateSql->execute(array(calculateStudentScore($conn, $studentID), $studentID));        
    }
    
    function refreshAllStudentsScore($conn){
        $studentResult = getStudents($conn); 
        for($i=0; $i<count($studentResult); $i++){
            $studentID = $studentResult[$i]->StudentID;
            updateStudentScore($conn, $studentID);
        }
    }
    
	
	function updateQuizRecord($conn, $quizID, $studentID, $status){
		$updateQuizRecordSql = "INSERT INTO Quiz_Record(QuizID, StudentID, Status)
							    VALUES (?,?,?) ON DUPLICATE KEY UPDATE Status = ?";				
		$updateQuizRecordQuery = $conn->prepare($updateQuizRecordSql);                            
		$updateQuizRecordQuery->execute(array($quizID, $studentID, $status, $status));
	}
	
	function getQuizStatus($conn, $quizID, $studentID){
		$statusSql = "SELECT COUNT(*) FROM Quiz_Record
					  WHERE QuizID = ? AND StudentID = ?";
		$statusQuery = $conn->prepare($statusSql);
        $statusQuery->execute(array($quizID, $studentID));
		
		if($statusQuery->fetchColumn() == 1){
			$statusSql = "SELECT `Status` FROM Quiz_Record
						  WHERE QuizID = ? AND StudentID = ?";
			$statusQuery = $conn->prepare($statusSql);
			$statusQuery->execute(array($quizID, $studentID));
			
			return $statusQuery->Status; 
		} else if($statusQuery->fetchColumn() == 0){
			return "UNANSWERED";
		} else{
			
		}
	}
	
	function updatePosterSavedDoc($conn, $quizID, $studentID, $zwibblerDoc){
		$posterRecordSaveSql = "INSERT INTO Poster_Record(QuizID, StudentID, ZwibblerDoc)
							    VALUES (?,?,?) ON DUPLICATE KEY UPDATE ZwibblerDoc= ?";
		$posterRecordSaveQuery = $conn->prepare($posterRecordSaveSql);
		$posterRecordSaveQuery->execute(array($quizID, $studentID, $zwibblerDoc, $zwibblerDoc));
	}
	
	function updatePosterSubmittedDoc($conn, $quizID, $studentID, $zwibblerDoc, $imageUrl){
		$posterRecordSubmittedSql = "INSERT INTO Poster_Record(QuizID, StudentID, ZwibblerDoc, ImageURL)
									 VALUES (?,?,?,?) ON DUPLICATE KEY UPDATE ZwibblerDoc = ? , ImageURL = ?";
			
		$posterRecordSubmittedQuery = $conn->prepare($posterRecordSubmittedSql);
		$posterRecordSubmittedQuery -> execute(array($quizID, $studentID, $zwibblerDoc, $imageUrl, $zwibblerDoc, $imageUrl));
	}
	
	function getPosterSavedDoc($conn, $quizID, $studentID){
		$posterSql = "SELECT COUNT(*)
					  FROM   Poster_Record
					  WHERE  StudentID=? AND QuizID=?";
		$posterQuery = $conn->prepare($posterSql);
		$posterQuery->execute(array($studentID, $quizID));
	
		if($posterQuery->fetchColumn() != 1){
			throw new Exception("Failed to get saved poster");
		}
	
		$posterSql = "SELECT ZwibblerDoc
					  FROM   Poster_Record
					  WHERE  StudentID=? AND QuizID=?";
		$posterQuery = $conn->prepare($posterSql);
		$posterQuery->execute(array($studentID, $quizID));
		$posterRes = $posterQuery->fetch(PDO::FETCH_OBJ);
		
		return $posterRes;
	}
    
?>