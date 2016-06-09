<?php  	
    /*
    Naming Convention:
    
    Create: INSERT
    Update: UPDATE
    Delete: DELETE
    get...: SELECT fetch
    get...s: SELECT fetchAll
    
    variables order: $conn, $pkCol(alphabetical order), $non-pkCol(alphabetical order)
    e.g. $conn, $questionID, $studentID, $status, $week
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
         VALUES (?);";			
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
    
    function getClassByName($conn, $className){
        $classSql = "SELECT ClassID
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
    /* Class */
    
    /* Token */    
    function updateToken($conn, $classID, $type, $tokenString){
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
    
    function getWeekNum($conn){
        $weekSql = "select MAX(Week) as WeekNum from Quiz";
        $weekQuery = $conn->prepare($weekSql);
        $weekQuery->execute();
        $weekResult = $weekQuery->fetch(PDO::FETCH_OBJ);
        return $weekResult;
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
    
    function updateStudentScore($conn, $studentID){        
        $updateSql = "UPDATE Student 
                SET Score = ?
                WHERE StudentID = ?";			
        $updateSql = $conn->prepare($updateSql);         
        $updateSql->execute(array(calculateStudentScore($conn, $studentID), $studentID));        
    }
	
	function updateQuizRecord($conn, $quizID, $studentID, $status){
		$updateQuizRecordSql = "INSERT INTO Quiz_Record(QuizID, StudentID, Status)
							    VALUES (?,?,?) ON DUPLICATE KEY UPDATE Status = ?;";			
		
		$updateQuizRecordQuery = $conn->prepare($updateQuizRecordSql);                            
		$updateQuizRecordQuery->execute(array($quizid, $studentid, $status, $status));
	}
?>