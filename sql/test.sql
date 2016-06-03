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
SELECT COUNT(*) AS OptionNum FROM MCQ_Question natural JOIN `Option` GROUP BY MCQID;