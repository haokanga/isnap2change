USE isnap2changedb;
/**
select * from Student;
select * from Quiz;
select * from SAQ_Section;
SELECT Week, COUNT(*) AS Count FROM Quiz GROUP BY Week;
SELECT MAX(Week) AS WeekNum FROM Quiz;
SET SQL_SAFE_UPDATES=0;
UPDATE Quiz SET Week = NULL WHERE Week = 1;
SET SQL_SAFE_UPDATES=1;
*/
SELECT * FROM Student natural JOIN Class;
SELECT * FROM Quiz NATURAL JOIN MCQ_Section;
SELECT * FROM Quiz NATURAL JOIN (SELECT QuizID, Points FROM MCQ_Section AS MCQPoints UNION SELECT QuizID , Points FROM Matching_Section AS MatchingPoints) AS QuizPoints;

SELECT SUM(Points) FROM Quiz NATURAL JOIN SAQ_Section NATURAL JOIN SAQ_Question;
SELECT SUM(Points) FROM Quiz NATURAL JOIN SAQ_Section NATURAL JOIN SAQ_Question WHERE QuizID = 3;
SELECT * FROM MCQ_Question;
SELECT MAX(OptionNum) FROM (SELECT COUNT(*) AS OptionNum FROM MCQ_Question natural JOIN `Option` GROUP BY MCQID) AS OptionNumbTable;

               
SELECT *
				   FROM   MCQ_Section NATURAL JOIN MCQ_Question
								  NATURAL JOIN `Option`
			       WHERE  QuizID = 1
			       ORDER BY MCQID;                   
                   
                   
SELECT MAX(OptionNum) AS MaxOptionNum FROM (SELECT COUNT(*) AS OptionNum FROM MCQ_Question natural JOIN `Option` WHERE QuizID = 1 GROUP BY MCQID) AS OptionNumbTable;                   

SELECT * FROM `Option`;
SELECT * FROM MCQ_Question;

SELECT QuizID, Week, TopicName, Points, Questionnaires, COUNT(MCQID) AS Questions
               FROM Quiz NATURAL JOIN Topic NATURAL JOIN MCQ_Section LEFT JOIN MCQ_Question USING (QuizID) WHERE QuizType = 'MCQ' GROUP BY QuizID;
SELECT Quiz.QuizID, Week, TopicName, Points, Questionnaires, Question, CorrectChoice
               FROM Quiz NATURAL JOIN Topic NATURAL JOIN MCQ_Section LEFT JOIN MCQ_Question ON MCQ_Section.QuizID = MCQ_Question.QuizID AND QuizType = 'MCQ';
         
SELECT MCQID,Question,`Option`,Explanation FROM MCQ_Question NATURAL JOIN `Option` WHERE MCQID = 1;         

# SELECT * FROM Quiz_Record NATURAL JOIN (MCQ_Section UNION Matching_Section UNION Poster_Section) ;
# WHERE StudentID = 1

SELECT * FROM Quiz NATURAL JOIN (SELECT QuizID, Points FROM MCQ_Section UNION SELECT QuizID , Points FROM Matching_Section UNION SELECT QuizID , Points FROM Poster_Section UNION SELECT QuizID , Points FROM Misc_Section ) AS QuizPoints WHERE QuizID = 1;

SELECT * FROM Quiz natural join Quiz_Record WHERE QuizID=1 AND StudentID = 2 AND `Status`='GRADED' ;

SELECT QuizID, StudentID, SUM(Grading) AS SumPoints FROM Quiz NATURAL JOIN SAQ_Section NATURAL JOIN SAQ_Question NATURAL JOIN SAQ_Question_Record WHERE QuizID = 3 AND StudentID = 1;












