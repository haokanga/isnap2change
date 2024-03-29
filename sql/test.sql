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

SELECT QuizID, Week, TopicName, Points, Questionnaire, COUNT(MCQID) AS Questions
               FROM Quiz NATURAL JOIN Topic NATURAL JOIN MCQ_Section LEFT JOIN MCQ_Question USING (QuizID) WHERE QuizType = 'MCQ' GROUP BY QuizID;
SELECT Quiz.QuizID, Week, TopicName, Points, Questionnaire, Question, CorrectChoice
               FROM Quiz NATURAL JOIN Topic NATURAL JOIN MCQ_Section LEFT JOIN MCQ_Question ON MCQ_Section.QuizID = MCQ_Question.QuizID AND QuizType = 'MCQ';
         
SELECT MCQID,Question,`Option`,Explanation FROM MCQ_Question NATURAL JOIN `Option` WHERE MCQID = 1;         

# SELECT * FROM Quiz_Record NATURAL JOIN (MCQ_Section UNION Matching_Section UNION Poster_Section) ;
# WHERE StudentID = 1


SELECT * FROM Quiz NATURAL JOIN Quiz_Record WHERE StudentID = 2 AND `Status`='GRADED';
SELECT * FROM Quiz NATURAL JOIN Quiz_Record;

SELECT QuizType FROM Quiz WHERE QuizID = 9;


SELECT * FROM Quiz natural join Quiz_Record WHERE QuizID=1 AND StudentID = 2 AND `Status`='GRADED' ;




SELECT * FROM Quiz NATURAL JOIN (SELECT QuizID, Points FROM MCQ_Section UNION SELECT QuizID , Points FROM Matching_Section UNION SELECT QuizID , Points FROM Poster_Section UNION SELECT QuizID , Points FROM Misc_Section ) AS QuizPoints 
#WHERE QuizID = 1
;

SELECT SUM(Points) AS SumPoints FROM Quiz NATURAL JOIN SAQ_Section NATURAL JOIN SAQ_Question 
WHERE QuizID = 4
;

SELECT * FROM Class;
SELECT *  FROM Class NATURAL JOIN School NATURAL JOIN Token;

INSERT INTO Quiz_Record(QuizID, StudentID, Status)
							    VALUES (10,10,'GRADED') ON DUPLICATE KEY UPDATE Status = 'GRADED';
SELECT * FROM    Quiz_Record;                             
                                
                                
                                SELECT *
				   FROM   MCQ_Section NATURAL JOIN MCQ_Question
								  NATURAL JOIN `Option`
			       WHERE  QuizID = 1
			       ORDER BY MCQID;  
SELECT QuizID, Week, TopicName, Points, Questionnaire, COUNT(MCQID) AS Questions
                   FROM Quiz NATURAL JOIN Topic NATURAL JOIN MCQ_Section LEFT JOIN MCQ_Question USING (QuizID) WHERE QuizType = 'MCQ' GROUP BY QuizID ;      
SELECT * FROM MCQ_Question NATURAL JOIN `Option` WHERE MCQID = 1;                   
SELECT * FROM Quiz;
SELECT *
FROM MCQ_Section NATURAL JOIN MCQ_Question
LEFT JOIN `Option` USING(MCQID)
WHERE QuizID = 6
ORDER BY MCQID;
SELECT *
FROM MCQ_Section NATURAL JOIN MCQ_Question
WHERE QuizID = 6
ORDER BY MCQID;
INSERT INTO MCQ_Question(Question, QuizID)
                    VALUES ('q1',6);
                    
SELECT * FROM Learning_Material;
SELECT QuizID, TopicID, Week, QuizType, TopicName, SAQID, SUM(Points) AS Points, COUNT(SAQID) AS Questions
                   FROM Quiz NATURAL JOIN Topic NATURAL JOIN SAQ_Section LEFT JOIN SAQ_Question USING (QuizID) WHERE QuizType = 'SAQ' GROUP BY QuizID;
                   
SELECT *
FROM Matching_Section NATURAL JOIN Matching_Question
WHERE QuizID = 8
ORDER BY MatchingID;

SELECT * FROM Quiz;                   
SELECT MAX(OptionNum) AS MaxOptionNum FROM (SELECT COUNT(*) AS OptionNum FROM Matching_Question natural JOIN Matching_Option WHERE QuizID = 8 GROUP BY MatchingID) AS OptionNumTable;   
   
SELECT * FROM Quiz_Record NATURAL JOIN Quiz NATURAL JOIN Student NATURAL JOIN Class NATURAL JOIN Topic WHERE QuizType = 'SAQ' AND (`Status` = 'UNGRADED' OR `Status` = 'GRADED');
SELECT * FROM Quiz_Record NATURAL JOIN SAQ_Question NATURAL JOIN SAQ_Question_Record WHERE QuizID = 3 AND StudentID = 3;

SELECT * FROM SAQ_Question_Record WHERE SAQID = 1 AND StudentID  =3;
UPDATE SAQ_Question_Record
                  SET Feedback = 'QUACK', Grading =  2
                  WHERE SAQID = 1 AND StudentID = 3;
SELECT * FROM Fact LEFT JOIN SubFact USING (FactID)
                WHERE SnapFact = 0;                  
SELECT * FROM Topic 
                LEFT JOIN (SELECT * FROM Fact LEFT JOIN SubFact USING (FactID) WHERE SnapFact = 0) AS VerboseFacts USING (TopicID);
                
SELECT *, COUNT(*) AS SubFacts
				 FROM Fact NATURAL JOIN SubFact
				 WHERE SnapFact = 0 AND TopicID = 1;
                 
SELECT * FROM Topic 
                LEFT JOIN (
                  SELECT * FROM Fact 
                  LEFT JOIN SubFact USING (FactID) 
                  WHERE SnapFact = 0) AS VerboseFacts 
                USING (TopicID) WHERE TopicName != 'Introduction';                 
SELECT COUNT(*)
				 FROM Fact
				 WHERE SnapFact = 0 AND TopicID = 3;             
SELECT * 
                        FROM   Quiz LEFT JOIN Learning_Material USING (QuizID);
             
SELECT * , COUNT(*) AS SubmissionNum FROM poster_section NATURAL JOIN quiz NATURAL JOIN topic NATURAL JOIN quiz_record GROUP BY QuizID;
*/

SELECT * , COUNT(*) AS Ungraded FROM poster_section NATURAL JOIN topic NATURAL JOIN quiz LEFT JOIN quiz_record USING (QuizID) WHERE `Status` = 'UNGRADED' GROUP BY QuizID;
SELECT COUNT(*) AS Ungraded FROM poster_section NATURAL JOIN topic NATURAL JOIN quiz NATURAL JOIN quiz_record WHERE `Status` = 'UNGRADED' AND QuizID = 9;
SELECT COUNT(*) AS Ungraded FROM poster_section NATURAL JOIN topic NATURAL JOIN quiz NATURAL JOIN quiz_record WHERE `Status` = 'UNGRADED' AND QuizID = 12;            