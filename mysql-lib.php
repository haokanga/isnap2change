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
function db_connect()
{

    $conn = null;

    $serverName = "localhost";
    $username = "root";
    $password = ".kHdGCD2Un%P";

    $conn = new PDO("mysql:host=$serverName; dbname=isnap2changedb; charset=utf8", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $conn;

}

function db_close(PDO $conn)
{
    $conn = null;
}

/* db connection*/

/* School */
function createSchool(PDO $conn, $schoolName)
{
    $updateSql = "INSERT INTO School(SchoolName)
         VALUES (?)";
    $updateSql = $conn->prepare($updateSql);
    $updateSql->execute(array(htmlspecialchars($schoolName)));
    return $conn->lastInsertId();
}

function updateSchool(PDO $conn, $schoolID, $schoolName)
{
    $updateSql = "UPDATE School 
            SET SchoolName = ?
            WHERE SchoolID = ?";
    $updateSql = $conn->prepare($updateSql);
    $updateSql->execute(array(htmlspecialchars($schoolName), $schoolID));
}

function deleteSchool(PDO $conn, $schoolID)
{
    deleteRecord($conn, $schoolID, "School");
}

function getSchool(PDO $conn, $schoolID)
{
    return getRecord($conn, $schoolID, "School");
}

function getSchoolByName(PDO $conn, $schoolName)
{
    $schoolSql = "SELECT SchoolID
                   FROM School WHERE SchoolName = ?";
    $schoolQuery = $conn->prepare($schoolSql);
    $schoolQuery->execute(array(htmlspecialchars($schoolName)));
    $schoolResult = $schoolQuery->fetch(PDO::FETCH_OBJ);
    return $schoolResult;
}

function getSchools(PDO $conn)
{
    return getRecords($conn, "School");
}

/* School */

/* Class */
function createClass(PDO $conn, $schoolID, $className)
{
    $updateSql = "INSERT INTO Class(ClassName, SchoolID)
             VALUES (?,?)";
    $updateSql = $conn->prepare($updateSql);
    $updateSql->execute(array(htmlspecialchars($className), $schoolID));
    return $conn->lastInsertId();
}

function updateClass(PDO $conn, $classID, $schoolID, $className, $unlockedProgress)
{
    $updateSql = "UPDATE Class 
            SET ClassName = ?, SchoolID = ?, UnlockedProgress = ?
            WHERE ClassID = ?";
    $updateSql = $conn->prepare($updateSql);
    $updateSql->execute(array(htmlspecialchars($className), $schoolID, $unlockedProgress, $classID));
}

function deleteClass(PDO $conn, $classID)
{
    deleteRecord($conn, $classID, "Class");
}

function getClass(PDO $conn, $classID)
{
    return getRecord($conn, $classID, "Class");
}

function getClassByName(PDO $conn, $className)
{
    $classSql = "SELECT *
                   FROM Class WHERE ClassName = ?";
    $classQuery = $conn->prepare($classSql);
    $classQuery->execute(array(htmlspecialchars($className)));
    $classResult = $classQuery->fetch(PDO::FETCH_OBJ);
    return $classResult;
}

function getClassNum(PDO $conn)
{
    $classNumSql = "SELECT count(*) AS Count, SchoolID
                   FROM School NATURAL JOIN Class
                   GROUP BY SchoolID";
    $classNumQuery = $conn->prepare($classNumSql);
    $classNumQuery->execute();
    $classNumResult = $classNumQuery->fetchAll(PDO::FETCH_OBJ);
    return $classNumResult;
}

function getClasses(PDO $conn)
{
    return getRecords($conn, "Class", array("School"));
}

function getStudentNum(PDO $conn)
{
    $studentNumSql = "SELECT count(*) AS Count, ClassID
                   FROM   Student NATURAL JOIN Class
                   GROUP BY ClassID";
    $studentNumQuery = $conn->prepare($studentNumSql);
    $studentNumQuery->execute();
    $studentNumResult = $studentNumQuery->fetchAll(PDO::FETCH_OBJ);
    return $studentNumResult;
}

/* Class */

/* Token */
function updateToken(PDO $conn, $classID, $tokenString)
{
    $updateSql = "UPDATE Class 
            SET TokenString = ?
            WHERE ClassID = ?";
    $updateSql = $conn->prepare($updateSql);
    $updateSql->execute(array(htmlspecialchars($tokenString), $classID));
}

function getToken(PDO $conn, $token)
{
    $tokenSql = "SELECT COUNT(*)
				 FROM Class NATURAL JOIN School
				 WHERE TokenString = BINARY ?";

    $tokenQuery = $conn->prepare($tokenSql);
    $tokenQuery->execute(array($token));

    if ($tokenQuery->fetchColumn() != 1) {
        throw new Exception("Fail to get token");
    }

    $tokenSql = "SELECT SchoolName, ClassID, ClassName, TokenString
				 FROM Class NATURAL JOIN  School
				 WHERE TokenString = BINARY ?";

    $tokenQuery = $conn->prepare($tokenSql);
    $tokenQuery->execute(array($token));
    $tokenRes = $tokenQuery->fetch(PDO::FETCH_OBJ);
    return $tokenRes;
}

function validToken(PDO $conn, $token)
{
    $tokenSql = "SELECT COUNT(*)
				 FROM Class
				 WHERE TokenString = BINARY ?";

    $tokenQuery = $conn->prepare($tokenSql);
    $tokenQuery->execute(array($token));
    $tokenRes = $tokenQuery->fetchColumn();

    if ($tokenRes == 0) {
        return false;
    } else if ($tokenRes == 1) {
        return true;
    } else throw new Exception("Duplicate tokens");
}

/* Token */

/* Student */
function createStudent(PDO $conn, $username, $password, $firstName, $lastName, $email, $gender, $dob, $identity, $classID)
{
    $insertStudentSql = "INSERT INTO Student(Username, `Password`, FirstName, LastName, Email, Gender, DOB, Identity, Score, ClassID)
						 VALUES (?,?,?,?,?,?,?,?,?,?)";

    $insertStudentSql = $conn->prepare($insertStudentSql);

    if (!$insertStudentSql->execute(array($username, md5($password), $firstName, $lastName, $email, $gender, $dob, $identity, 0, $classID))) {
        throw new Exception("Fail to insert a student");
    }
}

function deleteStudent(PDO $conn, $studentID)
{
    deleteRecord($conn, $studentID, "Student");
}

function getStudent(PDO $conn, $studentID)
{
    return getRecord($conn, $studentID, "Student", array("Class"));
}

function getStudents(PDO $conn)
{
    $studentSql = "SELECT * , DATE(SubmissionTime) AS SubmissionDate FROM Student NATURAL JOIN Class
                   ORDER BY ClassID";
    $studentQuery = $conn->prepare($studentSql);
    $studentQuery->execute();
    $studentResult = $studentQuery->fetchAll(PDO::FETCH_OBJ);
    return $studentResult;
}

function getStudentsRank(PDO $conn)
{
    $leaderboardSql = "SELECT Username, Score
					   FROM Student
					   ORDER BY Score DESC, SubmissionTime 
					   LIMIT 10;";

    $leaderboardQuery = $conn->prepare($leaderboardSql);
    $leaderboardQuery->execute(array());
    $leaderboardRes = $leaderboardQuery->fetchAll(PDO::FETCH_OBJ);

    return $leaderboardRes;
}

function resetPassword(PDO $conn, $studentID)
{
    $updateSql = "UPDATE Student 
            SET Password = ?
            WHERE StudentID = ?";
    $updateSql = $conn->prepare($updateSql);
    $updateSql->execute(array(md5('WelcomeToiSNAP2'), $studentID));
}

function validStudent(PDO $conn, $username, $password)
{
    $validStudentSql = "SELECT COUNT(*) FROM Student WHERE `Username` = BINARY ? AND `Password` = BINARY ?";
    $validStudentQuery = $conn->prepare($validStudentSql);
    $validStudentQuery->execute(array($username, md5($password)));
    $validStudentRes = $validStudentQuery->fetchColumn();

    if ($validStudentRes == 0) {
        return null;
    } else if ($validStudentRes == 1) {

        $validStudentSql = "SELECT StudentID, Username FROM Student WHERE `Username` = BINARY ? AND `Password` = BINARY ?";
        $validStudentQuery = $conn->prepare($validStudentSql);
        $validStudentQuery->execute(array($username, md5($password)));
        $validStudentRes = $validStudentQuery->fetch(PDO::FETCH_OBJ);

        return $validStudentRes;
    } else throw new Exception("Duplicate students in Database");
}

function validUsername(PDO $conn, $username)
{
    $userSql = "SELECT COUNT(*)
				FROM Student
				WHERE Username = BINARY ?";

    $userQuery = $conn->prepare($userSql);
    $userQuery->execute(array($username));
    $userRes = $userQuery->fetchColumn();

    if ($userRes == 0) {
        return true;
    } else if ($userRes == 1) {
        return false;
    } else throw new Exception("Two or more than two users have the same username");
}

/* Student */

/* Week */
function removeWeek(PDO $conn, $week)
{
    $updateSql = "SET SQL_SAFE_UPDATES=0;
            UPDATE Quiz SET Week = NULL WHERE Week = ?;
            SET SQL_SAFE_UPDATES=1";
    $updateSql = $conn->prepare($updateSql);
    $updateSql->execute(array($week));
    return $updateSql;
}

function getWeekByQuiz(PDO $conn, $quizID)
{
    return getQuiz($conn, $quizID)->Week;
}

function getMaxWeek(PDO $conn)
{
    $weekSql = "SELECT MAX(Week) AS WeekNum FROM Quiz";
    $weekQuery = $conn->prepare($weekSql);
    $weekQuery->execute();
    $weekResult = $weekQuery->fetch(PDO::FETCH_OBJ);
    return $weekResult;
}

/* Week */

/* Student Week Record*/
function createStuWeekRecord(PDO $conn, $studentID, $week, $dueTime)
{
    $updateSql = "INSERT IGNORE INTO Student_Week_Record(StudentID, Week, DueTime)
                  VALUES (?,?,?)";
    $updateSql = $conn->prepare($updateSql);
    $updateSql->execute(array($studentID, $week, $dueTime));
}

function getStuWeekRecord(PDO $conn, $studentID, $week)
{
    $weekRecordSql = "SELECT COUNT(*) 
                      FROM Student_Week_Record
                      WHERE StudentID = ? AND Week = ?";

    $weekRecordQuery = $conn->prepare($weekRecordSql);
    $weekRecordQuery->execute(array($studentID, $week));

    if ($weekRecordQuery->fetchColumn() == 0) {
        return null;
    }

    $weekRecordSql = "SELECT DueTime 
                      FROM Student_Week_Record
                      WHERE StudentID = ? AND Week = ?";

    $weekRecordQuery = $conn->prepare($weekRecordSql);
    $weekRecordQuery->execute(array($studentID, $week));
    $weekRecordRes = $weekRecordQuery->fetch(PDO::FETCH_OBJ);

    return $weekRecordRes->DueTime;
}


/* Student Week Record*/

/* Quiz */
function createQuiz(PDO $conn, $topicID, $quizType, $week)
{
    $updateSql = "INSERT INTO Quiz(Week, QuizType, TopicID)
             VALUES (?,?,?)";
    $updateSql = $conn->prepare($updateSql);
    $updateSql->execute(array($week, $quizType, $topicID));
    return $conn->lastInsertId();
}

function updateQuiz(PDO $conn, $quizID, $topicID, $week)
{
    $updateSql = "UPDATE Quiz 
                SET Week = ?, TopicID = ?
                WHERE QuizID = ?";
    $updateSql = $conn->prepare($updateSql);
    $updateSql->execute(array($week, $topicID, $quizID));
}

function deleteQuiz(PDO $conn, $quizID)
{
    deleteRecord($conn, $quizID, "Quiz");
}

function getStuQuizScore(PDO $conn, $quizID, $studentID)
{
    $pointsBySection = array('MCQ', 'Matching', 'Poster', 'Misc');
    $pointsByQuestion = array('SAQ');
    $score = 0;

    $quizTypeSql = "SELECT COUNT(*) FROM Quiz NATURAL JOIN Quiz_Record WHERE QuizID = ? AND StudentID = ? AND `Status`='GRADED'";
    $quizTypeQuery = $conn->prepare($quizTypeSql);
    $quizTypeQuery->execute(array($quizID, $studentID));
    if ($quizTypeQuery->fetchColumn() > 0) {
        $quizTypeSql = "SELECT * FROM Quiz NATURAL JOIN Quiz_Record WHERE QuizID = ? AND StudentID = ? AND `Status`='GRADED'";
        $quizTypeQuery = $conn->prepare($quizTypeSql);
        $quizTypeQuery->execute(array($quizID, $studentID));
        $quizTypeResult = $quizTypeQuery->fetch(PDO::FETCH_OBJ);

        $quizType = $quizTypeResult->QuizType;

        if (in_array($quizType, $pointsBySection)) {
            $pointsSql = "SELECT * FROM Quiz NATURAL JOIN " . $quizType . "_Section WHERE QuizID = ?";
            $pointsQuery = $conn->prepare($pointsSql);
            $pointsQuery->execute(array($quizID));
            $pointsResult = $pointsQuery->fetch(PDO::FETCH_OBJ);
            $score = $pointsResult->Points;
        } else if (in_array($quizType, $pointsByQuestion)) {
            $pointsSql = "SELECT QuizID, StudentID, SUM(Grading) AS SumPoints FROM Quiz NATURAL JOIN SAQ_Section NATURAL JOIN SAQ_Question NATURAL JOIN SAQ_Question_Record WHERE QuizID = ? AND StudentID = ?";
            $pointsQuery = $conn->prepare($pointsSql);
            $pointsQuery->execute(array($quizID, $studentID));
            $pointsResult = $pointsQuery->fetch(PDO::FETCH_OBJ);
            $score = $pointsResult->SumPoints;
        }
    }

    return $score;
}

function getQuizNum(PDO $conn)
{
    $weekSql = "SELECT Week, COUNT(*) AS QuizNum FROM Quiz GROUP BY Week";
    $weekQuery = $conn->prepare($weekSql);
    $weekQuery->execute();
    $weekResult = $weekQuery->fetchAll(PDO::FETCH_OBJ);
    return $weekResult;
}

function getQuizType(PDO $conn, $quizID)
{
    $quizTypeSql = "SELECT COUNT(*)
                        FROM Quiz
                        WHERE QuizID = ?";
    $quizTypeQuery = $conn->prepare($quizTypeSql);
    $quizTypeQuery->execute(array($quizID));
    if ($quizTypeQuery->fetchColumn() != 1) {
        throw new Exception("Failed to get quiz type");
    }

    $quizTypeSql = "SELECT QuizType 
                        FROM   Quiz
                        WHERE  QuizID = ?";

    $quizTypeQuery = $conn->prepare($quizTypeSql);
    $quizTypeQuery->execute(array($quizID));
    $quizTypeQueryRes = $quizTypeQuery->fetch(PDO::FETCH_OBJ);

    if ($quizTypeQueryRes->QuizType == "Misc") {
        return getMiscQuizType($conn, $quizID);
    } else {
        return $quizTypeQueryRes->QuizType;
    }

}

function getQuiz(PDO $conn, $quizID){
    return getRecord($conn, $quizID, "Quiz");
}

function getQuizzes(PDO $conn)
{
    return getRecords($conn, "Quiz", array("Topic"));
}

function getQuizzesByWeek(PDO $conn, $week)
{
    $quizSql = "SELECT QuizID, Week, QuizType, TopicName
                   FROM Quiz NATURAL JOIN Topic WHERE Week = ?";
    $quizQuery = $conn->prepare($quizSql);
    $quizQuery->execute(array($week));
    $quizResult = $quizQuery->fetchAll(PDO::FETCH_OBJ);
    return $quizResult;
}

function getQuizPoints(PDO $conn, $quizID)
{
    $pointsBySection = array('MCQ', 'Matching', 'Poster', 'Misc');
    $pointsByQuestion = array('SAQ');
    $points = 0;

    $quizTypeSql = "SELECT COUNT(*) FROM Quiz WHERE QuizID = ?";
    $quizTypeQuery = $conn->prepare($quizTypeSql);
    $quizTypeQuery->execute(array($quizID));
    if ($quizTypeQuery->fetchColumn() > 0) {
        $quizTypeSql = "SELECT QuizType FROM Quiz WHERE QuizID = ?";
        $quizTypeQuery = $conn->prepare($quizTypeSql);
        $quizTypeQuery->execute(array($quizID));
        $quizTypeResult = $quizTypeQuery->fetch(PDO::FETCH_OBJ);
        $quizType = $quizTypeResult->QuizType;

        if (in_array($quizType, $pointsBySection)) {
            $pointsSql = "SELECT Points AS SumPoints FROM Quiz NATURAL JOIN " . $quizType . "_Section WHERE QuizID = ?";
        } else if (in_array($quizType, $pointsByQuestion)) {
            $pointsSql = "SELECT SUM(Points) AS SumPoints FROM Quiz NATURAL JOIN SAQ_Section NATURAL JOIN SAQ_Question WHERE QuizID = ?";
        } else {
            throw new Exception("Unexpected Quiz Type. QuizID: " . $quizID);
        }
        $pointsQuery = $conn->prepare($pointsSql);
        $pointsQuery->execute(array($quizID));
        $pointsResult = $pointsQuery->fetch(PDO::FETCH_OBJ);
        if (strlen($pointsResult->SumPoints) > 0) {
            $points = $pointsResult->SumPoints;
        }
    }

    return $points;
}
/* Quiz */

/* Topic */
function getTopic(PDO $conn, $topicID)
{
    return getRecord($conn, $topicID, "Topic");
}

function getTopicByName(PDO $conn, $topicName)
{
    $topicSql = "SELECT * FROM Topic WHERE TopicName = ?";
    $topicQuery = $conn->prepare($topicSql);
    $topicQuery->execute(array(htmlspecialchars($topicName)));
    $topicResult = $topicQuery->fetch(PDO::FETCH_OBJ);
    return $topicResult;
}

function getTopics(PDO $conn)
{
    return getRecords($conn, "Topic");
}

/* Topic */

/* MCQ */
function createMCQSection(PDO $conn, $quizID, $points, $questionnaires)
{
    $updateSql = "INSERT INTO MCQ_Section(QuizID, Points, Questionnaires)
                    VALUES (?,?,?)";
    $updateSql = $conn->prepare($updateSql);
    $updateSql->execute(array($quizID, $points, $questionnaires));
}

function updateMCQSection(PDO $conn, $quizID, $points, $questionnaires)
{
    $updateSql = "UPDATE MCQ_Section
                    SET Points = ?, Questionnaires = ?
                    WHERE QuizID = ?";
    $updateSql = $conn->prepare($updateSql);
    $updateSql->execute(array($points, $questionnaires, $quizID));
}

function createMCQQuestion(PDO $conn, $quizID, $question)
{
    $updateSql = "INSERT INTO MCQ_Question(Question, QuizID)
                    VALUES (?,?)";
    $updateSql = $conn->prepare($updateSql);
    $updateSql->execute(array(htmlspecialchars($question), $quizID));
    return $conn->lastInsertId();
}

function updateMCQQuestion(PDO $conn, $mcqID, $correctChoice, $question)
{
    $updateSql = "UPDATE MCQ_Question
                    SET Question = ?, CorrectChoice = ?
                    WHERE MCQID = ?";
    $updateSql = $conn->prepare($updateSql);
    $updateSql->execute(array(htmlspecialchars($question), $correctChoice, $mcqID));
}

function deleteMCQQuestion(PDO $conn, $mcqID)
{
    $updateSql = "DELETE FROM MCQ_Question WHERE MCQID = ?";
    $updateSql = $conn->prepare($updateSql);
    $updateSql->execute(array($mcqID));
}

function getMCQQuestion(PDO $conn, $mcqID)
{
    $mcqQuesSql = "SELECT * FROM MCQ_Question WHERE MCQID = ?";
    $mcqQuesQuery = $conn->prepare($mcqQuesSql);
    $mcqQuesQuery->execute(array($mcqID));
    $mcqQuesResult = $mcqQuesQuery->fetch(PDO::FETCH_OBJ);
    return $mcqQuesResult;
}

function getMCQQuestions(PDO $conn, $quizID)
{
    $mcqQuesSql = "SELECT *
                    FROM MCQ_Section NATURAL JOIN MCQ_Question
                    LEFT JOIN MCQ_Option USING (MCQID)
                    WHERE QuizID = ?
                    ORDER BY MCQID";
    $mcqQuesQuery = $conn->prepare($mcqQuesSql);
    $mcqQuesQuery->execute(array($quizID));
    $mcqQuesResult = $mcqQuesQuery->fetchAll(PDO::FETCH_OBJ);
    return $mcqQuesResult;
}

function getMCQQuestionNum(PDO $conn, $quizID)
{
    $quesNumSql = "SELECT Count(*)
				       FROM   MCQ_Question
				       WHERE  QuizID = ?";

    $quesNumQuery = $conn->prepare($quesNumSql);
    $quesNumQuery->execute(array($quizID));
    $quesNumRes = $quesNumQuery->fetchColumn();

    return $quesNumRes;
}

function updateMCQQuestionRecord(PDO $conn, $MCQID, $studentID, $choice)
{
    $updateMCQQuesRecordSql = "INSERT INTO MCQ_Question_Record(StudentID, MCQID, Choice)
							       VALUES (?,?,?) ON DUPLICATE KEY UPDATE Choice = ?;";
    $updateMCQQuesRecordQuery = $conn->prepare($updateMCQQuesRecordSql);
    $updateMCQQuesRecordQuery->execute(array($studentID, $MCQID, htmlspecialchars($choice), htmlspecialchars($choice)));
}

function getMCQQuiz(PDO $conn, $quizID)
{
    $quizSql = "SELECT *, COUNT(MCQID) AS Questions
                   FROM Quiz NATURAL JOIN Topic NATURAL JOIN MCQ_Section LEFT JOIN MCQ_Question USING (QuizID) WHERE QuizID = ? GROUP BY QuizID";
    $quizQuery = $conn->prepare($quizSql);
    $quizQuery->execute(array($quizID));
    $quizResult = $quizQuery->fetch(PDO::FETCH_OBJ);
    return $quizResult;
}

function getMCQQuizzes(PDO $conn)
{
    $quizSql = "SELECT *, COUNT(MCQID) AS Questions
                   FROM Quiz NATURAL JOIN Topic NATURAL JOIN MCQ_Section LEFT JOIN MCQ_Question USING (QuizID) WHERE QuizType = 'MCQ' GROUP BY QuizID";
    $quizQuery = $conn->prepare($quizSql);
    $quizQuery->execute();
    $quizResult = $quizQuery->fetchAll(PDO::FETCH_OBJ);
    return $quizResult;
}

function getMCQSubmission(PDO $conn, $quizID, $studentID)
{
    $mcqSubmissionSql = "SELECT MCQID, Question, Content, CorrectChoice, Choice, Explanation
				             FROM   MCQ_Section NATURAL JOIN MCQ_Question
									            NATURAL JOIN MCQ_Option
									            NATURAL JOIN MCQ_Question_Record
				             WHERE StudentID = ? AND QuizID = ?
				             ORDER BY MCQID";

    $mcqSubmissionQuery = $conn->prepare($mcqSubmissionSql);
    $mcqSubmissionQuery->execute(array($studentID, $quizID));

    $mcqSubmissionRes = $mcqSubmissionQuery->fetchAll(PDO::FETCH_OBJ);
    return $mcqSubmissionRes;
}

function getMCQSubmissionCorrectNum(PDO $conn, $MCQIDArr, $answerArr)
{
    $score = 0;

    $mcqCorrectNumSql = "SELECT COUNT(*) FROM MCQ_Question 
                             WHERE `MCQID` = BINARY ? AND `CorrectChoice` = BINARY ?";

    for ($i = 0; $i < count($MCQIDArr); $i++) {
        $mcqCorrectNumQuery = $conn->prepare($mcqCorrectNumSql);
        $mcqCorrectNumQuery->execute(array($MCQIDArr[$i], htmlspecialchars($answerArr[$i])));
        $score = $score + (int)$mcqCorrectNumQuery->fetchColumn();
    }

    return $score;
}

/* MCQ */

/* Option */
function createOption(PDO $conn, $mcqID, $content, $explanation)
{
    $updateSql = "INSERT INTO MCQ_Option(Content, Explanation, MCQID)
             VALUES (?,?,?)";
    $updateSql = $conn->prepare($updateSql);
    $updateSql->execute(array($content, htmlspecialchars($explanation), $mcqID));
    return $conn->lastInsertId();
}

function updateOption(PDO $conn, $optionID, $content, $explanation)
{
    $updateSql = "UPDATE MCQ_Option 
                SET Content = ?, Explanation = ?
                WHERE OptionID = ?";
    $updateSql = $conn->prepare($updateSql);
    $updateSql->execute(array($content, htmlspecialchars($explanation), $optionID));
}

function deleteOption(PDO $conn, $optionID)
{
    $updateSql = "DELETE FROM MCQ_Option WHERE OptionID = ?";
    $updateSql = $conn->prepare($updateSql);
    $updateSql->execute(array($optionID));
}

function getOptions(PDO $conn, $mcqID)
{
    $optionSql = "SELECT *
                   FROM MCQ_Question NATURAL JOIN MCQ_Option WHERE MCQID = ?";
    $optionQuery = $conn->prepare($optionSql);
    $optionQuery->execute(array($mcqID));
    $optionResult = $optionQuery->fetchAll(PDO::FETCH_OBJ);
    return $optionResult;
}

function getMaxOptionNum(PDO $conn, $quizID)
{
    $optionNumSql = "SELECT MAX(OptionNum) AS MaxOptionNum FROM (SELECT COUNT(*) AS OptionNum FROM MCQ_Question NATURAL JOIN MCQ_Option WHERE QuizID = ? GROUP BY MCQID) AS OptionNumTable";
    $optionNumQuery = $conn->prepare($optionNumSql);
    $optionNumQuery->execute(array($quizID));
    $optionNumResult = $optionNumQuery->fetch(PDO::FETCH_OBJ);
    return $optionNumResult;
}

/* Option */

/* SAQ */
function createSAQSection(PDO $conn, $quizID)
{
    $updateSql = "INSERT INTO SAQ_Section(QuizID)
                    VALUES (?)";
    $updateSql = $conn->prepare($updateSql);
    $updateSql->execute(array($quizID));
}

function createSAQQuestion(PDO $conn, $quizID, $points, $question)
{
    $updateSql = "INSERT INTO SAQ_Question(Question, Points, QuizID)
                    VALUES (?,?,?)";
    $updateSql = $conn->prepare($updateSql);
    $updateSql->execute(array(htmlspecialchars($question), $points, $quizID));
    return $conn->lastInsertId();
}

function updateSAQQuestion(PDO $conn, $saqID, $points, $question)
{
    $updateSql = "UPDATE SAQ_Question
                    SET Question = ?, Points = ?
                    WHERE SAQID = ?";
    $updateSql = $conn->prepare($updateSql);
    $updateSql->execute(array(htmlspecialchars($question), $points, $saqID));
}

function deleteSAQQuestion(PDO $conn, $saqID)
{
    $updateSql = "DELETE FROM SAQ_Question WHERE SAQID = ?";
    $updateSql = $conn->prepare($updateSql);
    $updateSql->execute(array($saqID));
}

function getSAQQuestion(PDO $conn, $saqID)
{
    $saqQuesSql = "SELECT * FROM SAQ_Question WHERE SAQID = ?";
    $saqQuesQuery = $conn->prepare($saqQuesSql);
    $saqQuesQuery->execute(array($saqID));
    $saqQuesResult = $saqQuesQuery->fetch(PDO::FETCH_OBJ);
    return $saqQuesResult;
}

function getSAQQuestions(PDO $conn, $quizID)
{
    $saqQuesSql = "SELECT *
                    FROM SAQ_Section NATURAL JOIN SAQ_Question
                    WHERE QuizID = ?
                    ORDER BY SAQID";
    $saqQuesQuery = $conn->prepare($saqQuesSql);
    $saqQuesQuery->execute(array($quizID));
    $saqQuesResult = $saqQuesQuery->fetchAll(PDO::FETCH_OBJ);
    return $saqQuesResult;
}

function getSAQQuiz(PDO $conn, $quizID)
{
    $quizSql = "SELECT QuizID, TopicID, Week, QuizType, TopicName, SAQID, SUM(Points) AS Points, COUNT(SAQID) AS Questions
                   FROM Quiz NATURAL JOIN Topic NATURAL JOIN SAQ_Section LEFT JOIN SAQ_Question USING (QuizID) WHERE QuizID = ? GROUP BY QuizID";
    $quizQuery = $conn->prepare($quizSql);
    $quizQuery->execute(array($quizID));
    $quizResult = $quizQuery->fetch(PDO::FETCH_OBJ);
    return $quizResult;
}

function getSAQQuizzes(PDO $conn)
{
    $quizSql = "SELECT QuizID, TopicID, Week, QuizType, TopicName, SAQID, SUM(Points) AS Points, COUNT(SAQID) AS Questions
                   FROM Quiz NATURAL JOIN Topic NATURAL JOIN SAQ_Section LEFT JOIN SAQ_Question USING (QuizID) WHERE QuizType = 'SAQ' GROUP BY QuizID";
    $quizQuery = $conn->prepare($quizSql);
    $quizQuery->execute();
    $quizResult = $quizQuery->fetchAll(PDO::FETCH_OBJ);
    return $quizResult;
}

/* SAQ */

/* Matching */
function createMatchingSection(PDO $conn, $quizID, $description, $points)
{
    $updateSql = "INSERT INTO Matching_Section(QuizID, Description, Points)
                    VALUES (?,?,?)";
    $updateSql = $conn->prepare($updateSql);
    $updateSql->execute(array($quizID, htmlspecialchars($description), $points));
}

function updateMatchingSection(PDO $conn, $quizID, $description, $points)
{
    $updateSql = "UPDATE Matching_Section
                    SET Description = ?, Points = ?
                    WHERE QuizID = ?";
    $updateSql = $conn->prepare($updateSql);
    $updateSql->execute(array(htmlspecialchars($description), $points, $quizID));
}

function getMatchingSection(PDO $conn, $quizID)
{
    $matchingSectionSql = "SELECT *
                   FROM   Matching_Section
                   WHERE  QuizID = ?";
    $matchingSectionQuery = $conn->prepare($matchingSectionSql);
    $matchingSectionQuery->execute(array($quizID));
    $matchingSectionResult = $matchingSectionQuery->fetch(PDO::FETCH_OBJ);
    return $matchingSectionResult;
}

function createMatchingQuestion(PDO $conn, $quizID, $question)
{
    $updateSql = "INSERT INTO Matching_Question(Question, QuizID)
                    VALUES (?,?)";
    $updateSql = $conn->prepare($updateSql);
    $updateSql->execute(array(htmlspecialchars($question), $quizID));
    return $conn->lastInsertId();
}

function updateMatchingQuestion(PDO $conn, $matchingID, $question)
{
    $updateSql = "UPDATE Matching_Question
                    SET Question = ?
                    WHERE MatchingID = ?";
    $updateSql = $conn->prepare($updateSql);
    $updateSql->execute(array(htmlspecialchars($question), $matchingID));
}

function deleteMatchingQuestion(PDO $conn, $matchingID)
{
    $updateSql = "DELETE FROM Matching_Question WHERE MatchingID = ?";
    $updateSql = $conn->prepare($updateSql);
    $updateSql->execute(array($matchingID));
}

function getMatchingQuestion(PDO $conn, $matchingID)
{
    $matchingQuesSql = "SELECT * FROM Matching_Question WHERE MatchingID = ?";
    $matchingQuesQuery = $conn->prepare($matchingQuesSql);
    $matchingQuesQuery->execute(array($matchingID));
    $matchingQuesResult = $matchingQuesQuery->fetch(PDO::FETCH_OBJ);
    return $matchingQuesResult;
}

function getMatchingQuestions(PDO $conn, $quizID)
{
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

function getMatchingBuckets(PDO $conn, $quizID)
{
    $matchingQuesSql = "SELECT *
                    FROM Matching_Section NATURAL JOIN Matching_Question
                    WHERE QuizID = ?
                    ORDER BY MatchingID";
    $matchingQuesQuery = $conn->prepare($matchingQuesSql);
    $matchingQuesQuery->execute(array($quizID));
    $matchingQuesResult = $matchingQuesQuery->fetchAll(PDO::FETCH_OBJ);
    return $matchingQuesResult;
}

function getMatchingQuiz(PDO $conn, $quizID)
{
    $quizSql = "SELECT *, COUNT(MatchingID) AS Questions
                   FROM Quiz NATURAL JOIN Topic NATURAL JOIN Matching_Section LEFT JOIN Matching_Question USING (QuizID) WHERE QuizID = ?";
    $quizQuery = $conn->prepare($quizSql);
    $quizQuery->execute(array($quizID));
    $quizResult = $quizQuery->fetch(PDO::FETCH_OBJ);
    return $quizResult;
}

function getMatchingQuizzes(PDO $conn)
{
    $quizSql = "SELECT *, COUNT(MatchingID) AS Questions
                   FROM Quiz NATURAL JOIN Topic NATURAL JOIN Matching_Section LEFT JOIN Matching_Question USING (QuizID) WHERE QuizType = 'Matching' GROUP BY QuizID";
    $quizQuery = $conn->prepare($quizSql);
    $quizQuery->execute();
    $quizResult = $quizQuery->fetchAll(PDO::FETCH_OBJ);
    return $quizResult;
}

/* Matching */

/* Matching_Option */
function createMatchingOption(PDO $conn, $matchingID, $content)
{
    $updateSql = "INSERT INTO Matching_Option(Content, MatchingID)
             VALUES (?,?)";
    $updateSql = $conn->prepare($updateSql);
    $updateSql->execute(array(htmlspecialchars($content), $matchingID));
    return $conn->lastInsertId();
}

function updateMatchingOption(PDO $conn, $matchingID, $optionID, $content)
{
    $updateSql = "UPDATE Matching_Option 
                SET Content = ?, MatchingID = ?
                WHERE OptionID = ?";
    $updateSql = $conn->prepare($updateSql);
    $updateSql->execute(array(htmlspecialchars($content), $matchingID, $optionID));
}

function deleteMatchingOption(PDO $conn, $optionID)
{
    $updateSql = "DELETE FROM Matching_Option WHERE OptionID = ?";
    $updateSql = $conn->prepare($updateSql);
    $updateSql->execute(array($optionID));
}

function getMaxMatchingOptionNum(PDO $conn, $quizID)
{
    $optionNumSql = "SELECT MAX(OptionNum) AS MaxOptionNum FROM (SELECT COUNT(*) AS OptionNum FROM Matching_Question NATURAL JOIN Matching_Option WHERE QuizID = ? GROUP BY MatchingID) AS OptionNumTable";
    $optionNumQuery = $conn->prepare($optionNumSql);
    $optionNumQuery->execute(array($quizID));
    $optionNumResult = $optionNumQuery->fetch(PDO::FETCH_OBJ);
    return $optionNumResult->MaxOptionNum;
}

/* Matching_Option */

/* Learning_Material */
function createEmptyLearningMaterial(PDO $conn, $quizID)
{
    $content = '<p>Learning materials for this quiz has not been added.</p>';
    $updateSql = "INSERT INTO Learning_Material(Content,QuizID, Excluded) VALUES (?,?,?)";
    $updateSql = $conn->prepare($updateSql);
    $updateSql->execute(array($content, $quizID, 1));
}

function updateLearningMaterial(PDO $conn, $quizID, $content)
{
    $updateSql = "UPDATE Learning_Material 
            SET Content = ?
            WHERE QuizID = ?";
    $updateSql = $conn->prepare($updateSql);
    $updateSql->execute(array(htmlspecialchars($content), $quizID));
}

function getLearningMaterial(PDO $conn, $quizID)
{
    return getRecord($conn, $quizID, "Learning_Material", array("Quiz", "Topic"));
}

function getLearningMaterialByWeek(PDO $conn, $week)
{
    $learniningMaterialSql = "SELECT TopicName, QuizID, Content
                              FROM Quiz NATURAL JOIN Topic
                                        NATURAL JOIN Learning_Material
                              WHERE Week = ? AND Excluded = ?";

    $learningMaterialQuery = $conn->prepare($learniningMaterialSql);
    $learningMaterialQuery->execute(array($week, 0));
    $learningMaterialResult = $learningMaterialQuery->fetchAll(PDO::FETCH_OBJ);
    return $learningMaterialResult;
}
/* Learning_Material */


function calculateStudentScore(PDO $conn, $studentID)
{
    $score = 0;

    $quizSql = "SELECT * FROM Quiz NATURAL JOIN Quiz_Record WHERE StudentID = ? AND `Status`='GRADED'";
    $quizQuery = $conn->prepare($quizSql);
    $quizQuery->execute(array($studentID));
    $quizResult = $quizQuery->fetchAll(PDO::FETCH_OBJ);
    for ($i = 0; $i < count($quizResult); $i++) {
        $score += getStuQuizScore($conn, $quizResult[$i]->QuizID, $studentID);
    }

    return $score;
}

function getStudentScore(PDO $conn, $studentID)
{
    $score = 0;

    $scoreSql = "SELECT COUNT(*) FROM Student WHERE StudentID = ?";
    $scoreQuery = $conn->prepare($scoreSql);
    $scoreQuery->execute(array($studentID));
    if ($scoreQuery->fetchColumn() > 0) {
        $scoreSql = "SELECT * FROM Student WHERE StudentID = ?";
        $scoreQuery = $conn->prepare($scoreSql);
        $scoreQuery->execute(array($studentID));
        $scoreResult = $scoreQuery->fetch(PDO::FETCH_OBJ);
        $score = $scoreResult->Score;
    }

    return $score;
}

function updateStudentScore(PDO $conn, $studentID)
{
    $updateSql = "UPDATE Student 
                  SET Score = ?
                  WHERE StudentID = ?";
    $updateSql = $conn->prepare($updateSql);
    $updateSql->execute(array(calculateStudentScore($conn, $studentID), $studentID));
}

function refreshAllStudentsScore(PDO $conn)
{
    $studentResult = getStudents($conn);
    for ($i = 0; $i < count($studentResult); $i++) {
        $studentID = $studentResult[$i]->StudentID;
        updateStudentScore($conn, $studentID);
    }
}


function updateQuizRecord(PDO $conn, $quizID, $studentID, $status)
{
    $updateQuizRecordSql = "INSERT INTO Quiz_Record(QuizID, StudentID, Status)
							    VALUES (?,?,?) ON DUPLICATE KEY UPDATE Status = ?";
    $updateQuizRecordQuery = $conn->prepare($updateQuizRecordSql);
    $updateQuizRecordQuery->execute(array($quizID, $studentID, $status, $status));
}

function deleteQuizRecord(PDO $conn, $quizID, $studentID)
{
    $updateSql = "DELETE FROM Quiz_Record WHERE QuizID = ? AND StudentID = ?";
    $updateSql = $conn->prepare($updateSql);
    $updateSql->execute(array($quizID, $studentID));
}

function getQuizStatus(PDO $conn, $quizID, $studentID)
{
    $statusSql = "SELECT COUNT(*) FROM Quiz_Record
					  WHERE QuizID = ? AND StudentID = ?";
    $statusQuery = $conn->prepare($statusSql);
    $statusQuery->execute(array($quizID, $studentID));

    if ($statusQuery->fetchColumn() == 1) {
        $statusSql = "SELECT `Status` FROM Quiz_Record
						  WHERE QuizID = ? AND StudentID = ?";
        $statusQuery = $conn->prepare($statusSql);
        $statusQuery->execute(array($quizID, $studentID));
        $statusResult = $statusQuery->fetch(PDO::FETCH_OBJ);
        return $statusResult->Status;
    } else {
        return "UNANSWERED";
    }
}

function getQuizzesStatusByWeek(PDO $conn, $studentID, $week)
{
    $quizzesRes = array();

    $quizzesStatusSql = "SELECT Quiz.QuizID, QuizType, `Status`, TopicName FROM Quiz LEFT JOIN (SELECT * FROM Quiz_Record WHERE StudentID = ?) Student_Quiz_Record ON Quiz.QuizID = Student_Quiz_Record.QuizID 
                                                                          NATURAL JOIN Topic WHERE Week = ? ORDER BY Quiz.QuizID";
    $quizzesStatusQuery = $conn->prepare($quizzesStatusSql);
    $quizzesStatusQuery->execute(array($studentID, $week));
    $quizzesStatusRes = $quizzesStatusQuery->fetchAll(PDO::FETCH_OBJ);

    for($i = 0; $i < count($quizzesStatusRes) ; $i++) {
        $quizzesRes[$i]['QuizID'] = $quizzesStatusRes[$i]->QuizID;
        $quizzesRes[$i]['Status'] = $quizzesStatusRes[$i]->Status;
        $quizzesRes[$i]['TopicName'] = $quizzesStatusRes[$i]->TopicName;

        if($quizzesStatusRes[$i]->QuizType == "Misc") {
            switch(getMiscQuizType($conn, $quizzesStatusRes[$i]->QuizID)) {
                case "Calculator":
                    $quizzesRes[$i]['QuizType'] = "Calculator";
                    break;
                case "DrinkingTool":
                    $quizzesRes[$i]['QuizType']= "DrinkingTool";
                    break;
            }
        } else {
            $quizzesRes[$i]['QuizType'] = $quizzesStatusRes[$i]->QuizType;
        }

        $quizzesRes[$i]['Points'] = getQuizPoints($conn, $quizzesStatusRes[$i]->QuizID);
    }

    return $quizzesRes;
}

/* Poster */
function createPosterSection(PDO $conn, $quizID, $question, $points)
{
    $updateSql = "INSERT INTO Poster_Section(QuizID, Question, Points)
                    VALUES (?,?,?)";
    $updateSql = $conn->prepare($updateSql);
    $updateSql->execute(array($quizID, htmlspecialchars($question), $points));
}

function updatePosterDraft(PDO $conn, $quizID, $studentID, $zwibblerDoc)
{
    $posterRecordSaveSql = "INSERT INTO Poster_Record(QuizID, StudentID, ZwibblerDoc)
							    VALUES (?,?,?) ON DUPLICATE KEY UPDATE ZwibblerDoc= ?";
    $posterRecordSaveQuery = $conn->prepare($posterRecordSaveSql);
    $posterRecordSaveQuery->execute(array($quizID, $studentID, $zwibblerDoc, $zwibblerDoc));
}

function updatePosterSubmission(PDO $conn, $quizID, $studentID, $zwibblerDoc, $imageUrl)
{
    $posterRecordSubmittedSql = "INSERT INTO Poster_Record(QuizID, StudentID, ZwibblerDoc, ImageURL)
									 VALUES (?,?,?,?) ON DUPLICATE KEY UPDATE ZwibblerDoc = ? , ImageURL = ?";

    $posterRecordSubmittedQuery = $conn->prepare($posterRecordSubmittedSql);
    $posterRecordSubmittedQuery->execute(array($quizID, $studentID, $zwibblerDoc, $imageUrl, $zwibblerDoc, $imageUrl));
}

function getPosterDraft(PDO $conn, $quizID, $studentID)
{
    $posterSql = "SELECT COUNT(*)
					  FROM   Poster_Record
					  WHERE  StudentID=? AND QuizID=?";
    $posterQuery = $conn->prepare($posterSql);
    $posterQuery->execute(array($studentID, $quizID));

    if ($posterQuery->fetchColumn() != 1) {
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

/* SAQ-Grading */
function updateSAQQuestionRecord(PDO $conn, $saqID, $studentID, $answer)
{
    $updateSql = "INSERT INTO SAQ_Question_Record(StudentID, SAQID, Answer)
                                         VALUES (?,?,?) ON DUPLICATE KEY UPDATE Answer = ?";
    $updateSql = $conn->prepare($updateSql);
    $updateSql->execute(array($studentID, $saqID, htmlspecialchars($answer), htmlspecialchars($answer)));
}


function updateSAQSubmissionGrading(PDO $conn, $quizID, $saqID, $studentID, $feedback, $grading, $pageName)
{
    if (count($saqID) == count($grading) && count($saqID) == count($feedback)) {
        try {
            $conn->beginTransaction();
            for ($i = 0; $i < count($saqID); $i++) {
                updateSAQQuestionGrading($conn, $saqID[$i], $studentID, $feedback[$i], $grading[$i]);
            }
            updateQuizRecord($conn, $quizID, $studentID, "GRADED");
            $conn->commit();
        } catch (Exception $e) {
            debug_err($pageName, $e);
            $conn->rollBack();
        }
    } else
        throw new Exception("The length of feedback array, grading array and saqID array don't match. ");
}

function updateSAQQuestionGrading(PDO $conn, $saqID, $studentID, $feedback, $grading)
{
    $updateSql = "UPDATE SAQ_Question_Record
                  SET Feedback = ?, Grading = ?
                  WHERE SAQID = ? AND StudentID = ?";
    $updateSql = $conn->prepare($updateSql);
    $updateSql->execute(array(htmlspecialchars($feedback), $grading, $saqID, $studentID));
}


function deleteSAQQuestionRecord(PDO $conn, $saqID, $studentID)
{
    $updateSql = "DELETE FROM SAQ_Question_Record WHERE SAQID = ? AND StudentID = ?";
    $updateSql = $conn->prepare($updateSql);
    $updateSql->execute(array($saqID, $studentID));
}


function getSAQRecords(PDO $conn, $quizID, $studentID)
{
    $saqQuesRecordSql = "SELECT StudentID, SAQID, Answer, Feedback, Grading
                   FROM   SAQ_Question_Record NATURAL JOIN SAQ_Question
                   WHERE  QuizID = ? AND StudentID = ?
                   ORDER BY SAQID";
    $saqQuesRecordQuery = $conn->prepare($saqQuesRecordSql);
    $saqQuesRecordQuery->execute(array($quizID, $studentID));
    $saqQuesRecordResult = $saqQuesRecordQuery->fetchAll(PDO::FETCH_OBJ);
    return $saqQuesRecordResult;
}


function updateSAQDraft(PDO $conn, $quizID, $saqID, $studentID, $answer, $pageName)
{
    if (count($saqID) == count($answer)) {
        try {
            $conn->beginTransaction();
            for ($i = 0; $i < count($saqID); $i++) {
                updateSAQQuestionRecord($conn, $saqID[$i], $studentID, $answer[$i]);
            }
            updateQuizRecord($conn, $quizID, $studentID, "UNSUBMITTED");
            $conn->commit();
        } catch (Exception $e) {
            debug_err($pageName, $e);
            $conn->rollBack();
        }
    } else
        throw new Exception("The length of answer array and question array don't match. ");

}

function updateSAQSubmission(PDO $conn, $quizID, $saqID, $studentID, $answer, $pageName)
{
    try {
        $conn->beginTransaction();
        for ($i = 0; $i < count($saqID); $i++) {
            updateSAQQuestionRecord($conn, $saqID[$i], $studentID, $answer[$i]);
        }

        updateQuizRecord($conn, $quizID, $studentID, "UNGRADED");
        $conn->commit();
    } catch (Exception $e) {
        debug_err($pageName, $e);
        $conn->rollBack();
    }
}

function deleteSAQSubmission(PDO $conn, $quizID, $studentID, $pageName)
{
    try {
        $conn->beginTransaction();

        deleteQuizRecord($conn, $quizID, $studentID);
        $saqResult = getSAQQuestions($conn, $quizID);
        for ($saqIndex = 0; $saqIndex < count($saqResult); $saqIndex++) {
            $saqID = $saqResult[$saqIndex]->SAQID;
            deleteSAQQuestionRecord($conn, $saqID, $studentID);
        }
        $conn->commit();
    } catch (Exception $e) {
        debug_err($pageName, $e);
        $conn->rollBack();
    }

}

function getSAQSubmission(PDO $conn, $quizID, $studentID)
{
    $quizSql = "SELECT * FROM Quiz_Record NATURAL JOIN SAQ_Question NATURAL JOIN SAQ_Question_Record WHERE QuizID = ? AND StudentID = ?";
    $quizQuery = $conn->prepare($quizSql);
    $quizQuery->execute(array($quizID, $studentID));
    $quizResult = $quizQuery->fetchAll(PDO::FETCH_OBJ);
    return $quizResult;
}

function getSAQSubmissions(PDO $conn)
{
    $quizSql = "SELECT * FROM Quiz_Record NATURAL JOIN Quiz NATURAL JOIN Student NATURAL JOIN Class NATURAL JOIN Topic WHERE QuizType = 'SAQ' AND (`Status` = 'UNGRADED' OR `Status` = 'GRADED')";
    $quizQuery = $conn->prepare($quizSql);
    $quizQuery->execute();
    $quizResult = $quizQuery->fetchAll(PDO::FETCH_OBJ);
    return $quizResult;
}

/* SAQ-Grading */


/* Fact */

function getFactTopics(PDO $conn)
{
    $topicSql = "SELECT DISTINCT TopicID
				 FROM FACT;";

    $topicQuery = $conn->prepare($topicSql);
    $topicQuery->execute(array());
    $topicRes = $topicQuery->fetchAll(PDO::FETCH_OBJ);

    return $topicRes;
}

function getFactsByTopicID(PDO $conn, $topicID)
{
    $factSql = "SELECT *
				FROM FACT NATURAL JOIN Topic
				WHERE TopicID = ?;";

    $factQuery = $conn->prepare($factSql);
    $factQuery->execute(array($topicID));
    $factRes = $factQuery->fetchAll(PDO::FETCH_OBJ);

    return $factRes;
}

/* Fact */

/* Misc Quiz */
function getMiscQuizType(PDO $conn, $quizID)
{
    $miscQuizTypeSql = "SELECT COUNT(*) 
                            FROM   Misc_Section
                            WHERE  QuizID = ?";
    $miscQuizTypeQuery = $conn->prepare($miscQuizTypeSql);
    $miscQuizTypeQuery->execute(array($quizID));
    if ($miscQuizTypeQuery->fetchColumn() != 1) {
        throw new Exception("Failed to get misc quiz type");
    }

    $miscQuizTypeSql = "SELECT QuizSubType 
                            FROM   Misc_Section
                            WHERE  QuizID = ?";

    $miscQuizTypeQuery = $conn->prepare($miscQuizTypeSql);
    $miscQuizTypeQuery->execute(array($quizID));
    $miscQuizTypeQueryRes = $miscQuizTypeQuery->fetch(PDO::FETCH_OBJ);
    return $miscQuizTypeQueryRes->QuizSubType;
}

/* Misc Quiz */

/* Game */
function getGame(PDO $conn, $gameID)
{
    return getRecord($conn, $gameID, "Game");
}

function getGames(PDO $conn)
{
    return getRecords($conn, "Game");
}

function getStudentWeek(PDO $conn, $studentID)
{
    $classID = getStudent($conn, $studentID)->ClassID;
    $week = min(getClass($conn, $classID)->UnlockedProgress, getMaxWeek($conn)->WeekNum);
    return $week;
}

function getStudentGameScores(PDO $conn, $gameID, $studentID)
{
    $levels = getGame($conn, $gameID)->Levels;
    $scoreArray = array_fill(0, $levels, 0);

    for ($level = 1; $level <= $levels; $level++) {
        $retrieveScorePreSql = "SELECT COUNT(*) FROM Game_Record WHERE `GameID` = ? AND `StudentID` = ? AND `Level` = ?";
        $retrieveScorePreQuery = $conn->prepare($retrieveScorePreSql);
        $retrieveScorePreQuery->execute(array($gameID, $studentID, $level));
        if ($retrieveScorePreQuery->fetchColumn() > 0) {
            $retrieveScoreSql = "SELECT GameID,StudentID,`Level`,Score FROM Game_Record WHERE `GameID` = ? AND `StudentID` = ? AND `Level` = ?";
            $retrieveScoreQuery = $conn->prepare($retrieveScoreSql);
            $retrieveScoreQuery->execute(array($gameID, $studentID, $level));
            $retrieveScoreResult = $retrieveScoreQuery->fetch(PDO::FETCH_OBJ);
            $scoreArray[$level - 1] = $retrieveScoreResult->Score;
        } else {
            $scoreArray[$level - 1] = 0;
        }
    }

    return $scoreArray;
}

function updateStudentGameScores(PDO $conn, $gameID, $studentID, $score)
{

    $historyHighScore = getStudentGameScores($conn, $gameID, $studentID);
    $updateSql = "INSERT INTO Game_Record(GameID,StudentID,`Level`,Score)
                     VALUES (?,?,?,?) ON DUPLICATE KEY UPDATE Score = ?";
    $updateSql = $conn->prepare($updateSql);
    for ($level = 1; $level <= count($score); $level++) {
        if ($score[$level - 1] > $historyHighScore[$level - 1]) {
            if (!$updateSql->execute(array($gameID, $studentID, $level, $score[$level - 1], $score[$level - 1]))) {
                debug_alert("Error occurred to submit game score. Report this bug to researchers.");
            } else {
                debug_log("Game Record Submitted. gameID: $gameID  studentID: $studentID");
            }
        } else {
            debug_log("Score does not exceed high score. high score: " . $historyHighScore[$level - 1] . "  score: " . $score[$level - 1]);
        }
    }
}

/* Game */

/* Helper Function */
function deleteRecord(PDO $conn, $recordID, $tableName)
{
    $updateSql = "DELETE FROM $tableName WHERE " . $tableName . "ID = ?";
    $updateSql = $conn->prepare($updateSql);
    $updateSql->execute(array($recordID));
}

function getRecord(PDO $conn, $recordID, $tableName, array $joinTables = null)
{
    $tablePK = $tableName . "ID";
    if ($tableName == "Learning_Material") $tablePK = "QuizID";

    $tableSql = "SELECT COUNT(*)
				 FROM $tableName
				 WHERE $tablePK = ?";
    $tableQuery = $conn->prepare($tableSql);
    $tableQuery->execute(array($recordID));
    if ($tableQuery->fetchColumn() != 1) {
        throw new Exception("Fail to get record from $tableName where ID = $recordID");
    }

    $tableSql = "SELECT * FROM $tableName";
    if ($joinTables != null) {
        foreach ($joinTables as $joinTable) {
            $tableSql .= " NATURAL JOIN " . $joinTable;
        }
    }
    $tableSql .= " WHERE $tablePK = ?";
    $tableQuery = $conn->prepare($tableSql);
    $tableQuery->execute(array($recordID));
    $tableResult = $tableQuery->fetch(PDO::FETCH_OBJ);
    return $tableResult;
}

function getRecords(PDO $conn, $tableName, array $joinTables = null)
{
    $tableSql = "SELECT * FROM " . $tableName;
    if ($joinTables != null) {
        foreach ($joinTables as $joinTable) {
            $tableSql .= " NATURAL JOIN " . $joinTable;
        }
    }
    $tableQuery = $conn->prepare($tableSql);
    $tableQuery->execute();
    $tableResult = $tableQuery->fetchAll(PDO::FETCH_OBJ);
    return $tableResult;
}

function encodeURIComponent($str) {
    $revert = array('%21'=>'!', '%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')');
    return strtr(rawurlencode($str), $revert);
}

/* Helper Function */

/* Unit Test */
function generateRandomString($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function generateRandomSAQSubmissions(PDO $conn)
{
    $quizResult = getSAQQuizzes($conn);
    $studentResult = getStudents($conn);
    for ($quizIndex = 0; $quizIndex < count($quizResult); $quizIndex++) {
        $quizID = $quizResult[$quizIndex]->QuizID;
        $saqResult = getSAQQuestions($conn, $quizID);
        for ($studentIndex = 0; $studentIndex < count($studentResult); $studentIndex++) {
            $studentID = $studentResult[$studentIndex]->StudentID;
            if ($studentID >= 3) {
                for ($saqIndex = 0; $saqIndex < count($saqResult); $saqIndex++) {
                    $saqID = $saqResult[$saqIndex]->SAQID;
                    updateSAQQuestionRecord($conn, $saqID, $studentID, generateRandomString(300));
                }
                updateQuizRecord($conn, $quizID, $studentID, "UNGRADED");
            }
        }
    }
}

/* Unit Test */
?>