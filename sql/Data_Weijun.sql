INSERT IGNORE INTO Quiz_Question(Question, CorrectChoice, QuizID) VALUES('1+1=?', 'A', 1);
INSERT IGNORE INTO Quiz_Question(Question, CorrectChoice, QuizID) VALUES('2+2=?', 'B', 1);
INSERT IGNORE INTO Quiz_Question(Question, CorrectChoice, QuizID) VALUES('3+3=?', 'C', 1);

INSERT IGNORE INTO `Option`(Content, QuestionID) VALUES('A. 2', 1);
INSERT IGNORE INTO `Option`(Content, QuestionID) VALUES('B. 3', 1);
INSERT IGNORE INTO `Option`(Content, QuestionID) VALUES('A. 5', 2);
INSERT IGNORE INTO `Option`(Content, QuestionID) VALUES('B. 4', 2);
INSERT IGNORE INTO `Option`(Content, QuestionID) VALUES('A. 6', 3);
INSERT IGNORE INTO `Option`(Content, QuestionID) VALUES('B. 7', 3);

INSERT IGNORE INTO Quiz_Question_Record(StudentID, QuestionID, Choice) VALUES(1, 1, 'A');
INSERT IGNORE INTO Quiz_Question_Record(StudentID, QuestionID, Choice) VALUES(2, 2, 'B');
INSERT IGNORE INTO Quiz_Question_Record(StudentID, QuestionID, Choice) VALUES(3, 3, 'A');

INSERT IGNORE INTO Short_Answer_Section(Week) VALUES(1);
INSERT IGNORE INTO Short_Answer_Section(Week) VALUES(2);
INSERT IGNORE INTO Short_Answer_Section(Week) VALUES(3);

INSERT IGNORE INTO Short_Answer_Section_Record(ShortAnswerID, StudentID, Finished) VALUES(1, 1, 0);
INSERT IGNORE INTO Short_Answer_Section_Record(ShortAnswerID, StudentID, Finished) VALUES(2, 2, 1);
INSERT IGNORE INTO Short_Answer_Section_Record(ShortAnswerID, StudentID, Finished) VALUES(3, 3, 0);

INSERT IGNORE INTO Short_Answer_Question(Question, Points, ShortAnswerID) VALUES('aaaa', 5, 1);
INSERT IGNORE INTO Short_Answer_Question(Question, Points, ShortAnswerID) VALUES('bbbb', 5, 2);
INSERT IGNORE INTO Short_Answer_Question(Question, Points, ShortAnswerID) VALUES('cccc', 5, 3);

INSERT IGNORE INTO Short_Answer_Question_Record(StudentID, SAQID, Answer, Feedback, Grading) VALUES(1, 1, '11111', 'qqqq', 3);
INSERT IGNORE INTO Short_Answer_Question_Record(StudentID, SAQID, Answer, Feedback, Grading) VALUES(2, 2, '22222', 'wwww', 4);
INSERT IGNORE INTO Short_Answer_Question_Record(StudentID, SAQID, Answer, Feedback, Grading) VALUES(3, 3, '33333', 'eeee', 5);

INSERT IGNORE INTO Bonus(Week) VALUES(1);
INSERT IGNORE INTO Bonus(Week) VALUES(2);
INSERT IGNORE INTO Bonus(Week) VALUES(3);

INSERT IGNORE INTO Bonus_Record(BonusID, StudentID, Finished) VALUES(1, 1, 0);
INSERT IGNORE INTO Bonus_Record(BonusID, StudentID, Finished) VALUES(2, 2, 0);
INSERT IGNORE INTO Bonus_Record(BonusID, StudentID, Finished) VALUES(3, 3, 1);

INSERT IGNORE INTO Bonus_Task(Question, Points, BonusID) VALUES('ggg', 10, 1);
INSERT IGNORE INTO Bonus_Task(Question, Points, BonusID) VALUES('ppp', 8, 2);
INSERT IGNORE INTO Bonus_Task(Question, Points, BonusID) VALUES('yyy', 7, 3);

INSERT IGNORE INTO Bonus_Task_Record(StudentID, BonusQuestionID, Answer, Feedback, Grading) VALUES(1, 1, 'cccccc', 'zzzzz', 8);
INSERT IGNORE INTO Bonus_Task_Record(StudentID, BonusQuestionID, Answer, Feedback, Grading) VALUES(2, 2, 'nnnnnn', 'hhhhh', 7);
INSERT IGNORE INTO Bonus_Task_Record(StudentID, BonusQuestionID, Answer, Feedback, Grading) VALUES(3, 3, 'oooooo', 'lllll', 6);


