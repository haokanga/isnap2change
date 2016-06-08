#mysql -uroot -p.kHdGCD2Un%P
#mysql -uroot -p.kHdGCD2Un%P < isnap2change.sql
CREATE DATABASE IF NOT EXISTS isnap2changedb;
USE isnap2changedb;

SET FOREIGN_KEY_CHECKS=0;

# [Assert] Lines of Drop table declaration = Lines of Create table declaration
DROP TABLE IF EXISTS `School`;
DROP TABLE IF EXISTS `Class`;
DROP TABLE IF EXISTS `Token`;
DROP TABLE IF EXISTS `Student`;
DROP TABLE IF EXISTS `Teacher`;
DROP TABLE IF EXISTS `Researcher`;
DROP TABLE IF EXISTS `Fact`;
DROP TABLE IF EXISTS `Topic`;
DROP TABLE IF EXISTS `Learning_Material`;
DROP TABLE IF EXISTS `Quiz`;
DROP TABLE IF EXISTS `Quiz_Record`;
DROP TABLE IF EXISTS `MCQ_Section`;
DROP TABLE IF EXISTS `MCQ_Question`;
DROP TABLE IF EXISTS `Option`;
DROP TABLE IF EXISTS `MCQ_Question_Record`;
DROP TABLE IF EXISTS `SAQ_Section`;
DROP TABLE IF EXISTS `SAQ_Question`;
DROP TABLE IF EXISTS `SAQ_Question_Record`;
DROP TABLE IF EXISTS `Matching_Section`;
DROP TABLE IF EXISTS `Matching_Question`;
DROP TABLE IF EXISTS `Matching_Option`;
DROP TABLE IF EXISTS `Poster_Section`;
DROP TABLE IF EXISTS `Poster_Record`;
DROP TABLE IF EXISTS `Misc_Section`;
DROP TABLE IF EXISTS `Game`;
DROP TABLE IF EXISTS `Game_Record`;
DROP TABLE IF EXISTS `Bonus`;
DROP TABLE IF EXISTS `Bonus_Record`;
DROP TABLE IF EXISTS `Bonus_Task`;
DROP TABLE IF EXISTS `Bonus_Task_Record`;

CREATE TABLE IF NOT EXISTS `School` (
    SchoolID MEDIUMINT AUTO_INCREMENT,
    SchoolName VARCHAR(190) UNIQUE,
    CONSTRAINT School_SchoolID_PK PRIMARY KEY (SchoolID)
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS `Class` (
    ClassID MEDIUMINT AUTO_INCREMENT,
    ClassName VARCHAR(190) UNIQUE,
    SchoolID MEDIUMINT NOT NULL,
    # UnlockedProgress
    UnlockedProgress MEDIUMINT NOT NULL DEFAULT 10,
    CONSTRAINT Class_ClassID_PK PRIMARY KEY (ClassID),
    CONSTRAINT Class_SchoolID_FK FOREIGN KEY (SchoolID)
        REFERENCES School (SchoolID)
        ON DELETE CASCADE ON UPDATE CASCADE
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS `Token` (
    ClassID MEDIUMINT NOT NULL,
    `Type` ENUM('TEACHER', 'STUDENT'),
    TokenString TEXT NOT NULL,
    CONSTRAINT Token_PK PRIMARY KEY (ClassID, `Type`),
    CONSTRAINT Token_ClassID_FK FOREIGN KEY (ClassID)
        REFERENCES Class (ClassID)
        ON DELETE CASCADE ON UPDATE CASCADE
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS `Student` (
    StudentID MEDIUMINT AUTO_INCREMENT,
    Username TEXT NOT NULL,
    Nickname TEXT,
    FirstName TEXT,
    LastName TEXT,
    `Password` TEXT NOT NULL,
    Email TEXT,
    Gender TEXT,
    DOB DATE,
    Score MEDIUMINT DEFAULT 0,
    SubmissionTime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  
    ClassID MEDIUMINT NOT NULL,
    CONSTRAINT Student_StudentID_PK PRIMARY KEY (StudentID),
    CONSTRAINT Student_ClassID_FK FOREIGN KEY (ClassID)
        REFERENCES Class (ClassID)
        ON DELETE CASCADE ON UPDATE CASCADE
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS `Teacher` (
    TeacherID MEDIUMINT AUTO_INCREMENT,
    Username TEXT NOT NULL,
    `Password` TEXT NOT NULL,
    ClassID MEDIUMINT NOT NULL,
    CONSTRAINT Teacher_TeacherID_PK PRIMARY KEY (TeacherID),
    CONSTRAINT Teacher_ClassID_FK FOREIGN KEY (ClassID)
        REFERENCES Class (ClassID)
        ON DELETE CASCADE ON UPDATE CASCADE
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS `Researcher` (
    ResearcherID MEDIUMINT AUTO_INCREMENT,
    Username TEXT NOT NULL,
    `Password` TEXT NOT NULL,
    CONSTRAINT Researcher_ResearcherID_PK PRIMARY KEY (ResearcherID)
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS `Fact` (
    FactID MEDIUMINT AUTO_INCREMENT,
    Content TEXT,
    TopicID MEDIUMINT,
    CONSTRAINT Fact_FactID_PK PRIMARY KEY (FactID),
    CONSTRAINT Fact_TopicID_FK FOREIGN KEY (TopicID)
        REFERENCES Topic (TopicID)
        ON DELETE CASCADE ON UPDATE CASCADE
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS `Topic` (
    TopicID MEDIUMINT AUTO_INCREMENT,
    TopicName VARCHAR(190) UNIQUE,
    CONSTRAINT Topic_TopicID_PK PRIMARY KEY (TopicID)
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS `Quiz` (
    QuizID MEDIUMINT AUTO_INCREMENT,
    Week MEDIUMINT,
    QuizType ENUM('SAQ', 'MCQ', 'Matching', 'Poster', 'Misc'),    
    TopicID MEDIUMINT,
    CONSTRAINT Quiz_QuizID_PK PRIMARY KEY (QuizID),
    CONSTRAINT Quiz_TopicID_FK FOREIGN KEY (TopicID)
        REFERENCES Topic (TopicID)
        ON DELETE CASCADE ON UPDATE CASCADE
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS `Quiz_Record` (
    QuizID MEDIUMINT,
    StudentID MEDIUMINT,    
    `Status` ENUM('UNSUBMITTED', 'UNGRADED', 'GRADED') DEFAULT 'GRADED',
    CONSTRAINT Quiz_Record_PK PRIMARY KEY (QuizID , StudentID),
    CONSTRAINT Quiz_Record_QuizID_FK FOREIGN KEY (QuizID)
        REFERENCES Quiz (QuizID)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT Quiz_Record_StudentID_FK FOREIGN KEY (StudentID)
        REFERENCES Student (StudentID)
        ON DELETE CASCADE ON UPDATE CASCADE
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS `Learning_Material` (
    MaterialID MEDIUMINT AUTO_INCREMENT,
    Content LONGTEXT,
    QuizID MEDIUMINT,
    CONSTRAINT Learning_Material_MaterialID_PK PRIMARY KEY (MaterialID),
    CONSTRAINT Learning_Material_QuizID_FK FOREIGN KEY (QuizID)
        REFERENCES Quiz (QuizID)
        ON DELETE CASCADE ON UPDATE CASCADE
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS `MCQ_Section` (
    QuizID MEDIUMINT,
    Points MEDIUMINT DEFAULT 0,
    Questionnaires BOOLEAN DEFAULT 0,
    CONSTRAINT MCQ_Section_QuizID_PK PRIMARY KEY (QuizID),
    CONSTRAINT MCQ_Section_QuizID_FK FOREIGN KEY (QuizID)
        REFERENCES Quiz (QuizID)
        ON DELETE CASCADE ON UPDATE CASCADE
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS `MCQ_Question` (
    MCQID MEDIUMINT AUTO_INCREMENT,
    Question TEXT,
    CorrectChoice TEXT NOT NULL,
    QuizID MEDIUMINT,
    CONSTRAINT MCQ_Question_MCQID_PK PRIMARY KEY (MCQID),
    CONSTRAINT MCQ_Question_QuizID_FK FOREIGN KEY (QuizID)
        REFERENCES MCQ_Section (QuizID)
        ON DELETE CASCADE ON UPDATE CASCADE
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS `Option` (
    OptionID MEDIUMINT AUTO_INCREMENT,
    Content TEXT,    
    Explanation TEXT,
    MCQID MEDIUMINT,
    CONSTRAINT Option_OptionID_PK PRIMARY KEY (OptionID),
    CONSTRAINT Option_MCQID_FK FOREIGN KEY (MCQID)
        REFERENCES MCQ_Question (MCQID)
        ON DELETE CASCADE ON UPDATE CASCADE
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS `MCQ_Question_Record` (
    StudentID MEDIUMINT,
    MCQID MEDIUMINT,
    Choice TEXT,
    CONSTRAINT MCQ_Question_Record_PK PRIMARY KEY (StudentID , MCQID),
    CONSTRAINT MCQ_Question_Record_StudentID_FK FOREIGN KEY (StudentID)
        REFERENCES Student (StudentID)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT MCQ_Question_Record_MCQID_FK FOREIGN KEY (MCQID)
        REFERENCES MCQ_Question (MCQID)
        ON DELETE CASCADE ON UPDATE CASCADE
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS `SAQ_Section` (
    QuizID MEDIUMINT,
    CONSTRAINT SAQ_Section_QuizID_PK PRIMARY KEY (QuizID),
    CONSTRAINT SAQ_Section_QuizID_FK FOREIGN KEY (QuizID)
        REFERENCES Quiz (QuizID)
        ON DELETE CASCADE ON UPDATE CASCADE
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS `SAQ_Question` (
    SAQID MEDIUMINT AUTO_INCREMENT,
    Question TEXT,
    Points MEDIUMINT,
    QuizID MEDIUMINT,
    CONSTRAINT SAQ_Question_SAQID_PK PRIMARY KEY (SAQID),
    CONSTRAINT SAQ_Question_QuizID_FK FOREIGN KEY (QuizID)
        REFERENCES SAQ_Section (QuizID)
        ON DELETE CASCADE ON UPDATE CASCADE
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS `SAQ_Question_Record` (
    StudentID MEDIUMINT,
    SAQID MEDIUMINT,
    Answer TEXT,
    Feedback TEXT,
    Grading MEDIUMINT,
    CONSTRAINT SAQ_Question_Record_PK PRIMARY KEY (StudentID , SAQID),
    CONSTRAINT SAQ_Question_Record_StudentID_FK FOREIGN KEY (StudentID)
        REFERENCES Student (StudentID)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT SAQ_Question_Record_SAQID_FK FOREIGN KEY (SAQID)
        REFERENCES SAQ_Question (SAQID)
        ON DELETE CASCADE ON UPDATE CASCADE
)  ENGINE=INNODB;


CREATE TABLE IF NOT EXISTS `Matching_Section` (
    QuizID MEDIUMINT,
    Explanation TEXT,
    Points MEDIUMINT,
    MultipleChoices BOOLEAN DEFAULT 0,
    CONSTRAINT Matching_Section_QuizID_PK PRIMARY KEY (QuizID),
    CONSTRAINT Matching_Section_QuizID_FK FOREIGN KEY (QuizID)
        REFERENCES Quiz (QuizID)
        ON DELETE CASCADE ON UPDATE CASCADE
)  ENGINE=INNODB;

# Set A: terminology/category/bucket
CREATE TABLE IF NOT EXISTS `Matching_Question` (
    MatchingQuestionID MEDIUMINT AUTO_INCREMENT,
    Question TEXT,
    QuizID MEDIUMINT,
    CONSTRAINT Matching_Question_MatchingQuestionID_PK PRIMARY KEY (MatchingQuestionID),
    CONSTRAINT Matching_Question_QuizID_FK FOREIGN KEY (QuizID)
        REFERENCES Matching_Section (QuizID)
        ON DELETE CASCADE ON UPDATE CASCADE
)  ENGINE=INNODB;

# Set B: explanation/concept/item
CREATE TABLE IF NOT EXISTS `Matching_Option` (
    OptionID MEDIUMINT AUTO_INCREMENT,
    Content TEXT,
    MatchingQuestionID MEDIUMINT,
    CONSTRAINT Matching_Option_OptionID_PK PRIMARY KEY (OptionID),
    CONSTRAINT Matching_Option_MatchingQuestionID_FK FOREIGN KEY (MatchingQuestionID)
        REFERENCES Matching_Question (MatchingQuestionID)
        ON DELETE CASCADE ON UPDATE CASCADE
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS `Poster_Section` (
    QuizID MEDIUMINT,
    Question TEXT,
    Points MEDIUMINT,
    CONSTRAINT Poster_Section_QuizID_PK PRIMARY KEY (QuizID),
    CONSTRAINT Poster_Section_QuizID_FK FOREIGN KEY (QuizID)
        REFERENCES Quiz (QuizID)
        ON DELETE CASCADE ON UPDATE CASCADE
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS `Poster_Record` (
    StudentID MEDIUMINT,
    QuizID MEDIUMINT,
    ZwibblerDoc LONGTEXT,
    ImageURL LONGTEXT,
    Grading MEDIUMINT,
    CONSTRAINT Poster_Record_Record_PK PRIMARY KEY (StudentID , QuizID),
    CONSTRAINT Poster_Record_Record_StudentID_FK FOREIGN KEY (StudentID)
        REFERENCES Student (StudentID)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT Poster_Record_Record_QuizID_FK FOREIGN KEY (QuizID)
        REFERENCES Poster_Section (QuizID)
        ON DELETE CASCADE ON UPDATE CASCADE
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS `Misc_Section` (
    QuizID MEDIUMINT,
    QuizSubType TEXT,
    Points MEDIUMINT,
    CONSTRAINT Misc_Section_QuizID_PK PRIMARY KEY (QuizID),
    CONSTRAINT Misc_Section_QuizID_FK FOREIGN KEY (QuizID)
        REFERENCES Quiz (QuizID)
        ON DELETE CASCADE ON UPDATE CASCADE
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS `Game` (
    GameID MEDIUMINT AUTO_INCREMENT,
    Description TEXT,
    Week MEDIUMINT,
    Points MEDIUMINT,
    CONSTRAINT Game_GameID_PK PRIMARY KEY (GameID)
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS `Game_Record` (
    GameID MEDIUMINT,
    StudentID MEDIUMINT,
    `Level` MEDIUMINT DEFAULT 0,
    Score INT,
    CONSTRAINT Game_Record_PK PRIMARY KEY (GameID , StudentID, `Level`),
    CONSTRAINT Game_Record_GameID_FK FOREIGN KEY (GameID)
        REFERENCES Game (GameID)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT Game_Record_StudentID_FK FOREIGN KEY (StudentID)
        REFERENCES Student (StudentID)
        ON DELETE CASCADE ON UPDATE CASCADE
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS `Bonus` (
    BonusID MEDIUMINT AUTO_INCREMENT,
    Week MEDIUMINT,
    CONSTRAINT Bonus_BonusID_PK PRIMARY KEY (BonusID)
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS `Bonus_Record` (
    BonusID MEDIUMINT,
    StudentID MEDIUMINT,
    CONSTRAINT Bonus_Record_PK PRIMARY KEY (BonusID , StudentID),
    CONSTRAINT Bonus_Record_BonusID_FK FOREIGN KEY (BonusID)
        REFERENCES Bonus (BonusID)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT Bonus_Record_StudentID_FK FOREIGN KEY (StudentID)
        REFERENCES Student (StudentID)
        ON DELETE CASCADE ON UPDATE CASCADE
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS `Bonus_Task` (
    BonusQuestionID MEDIUMINT AUTO_INCREMENT,
    Question TEXT,
    Points MEDIUMINT,
    BonusID MEDIUMINT,
    CONSTRAINT Bonus_Task_BonusQuestionID_PK PRIMARY KEY (BonusQuestionID),
    CONSTRAINT Bonus_Task_BonusID_FK FOREIGN KEY (BonusID)
        REFERENCES Bonus (BonusID)
        ON DELETE CASCADE ON UPDATE CASCADE
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS `Bonus_Task_Record` (
    StudentID MEDIUMINT,
    BonusQuestionID MEDIUMINT,
    Answer TEXT,
    Feedback TEXT,
    Grading MEDIUMINT,
    CONSTRAINT Bonus_Task_Record_PK PRIMARY KEY (StudentID , BonusQuestionID),
    CONSTRAINT Bonus_Task_Record_StudentID FOREIGN KEY (StudentID)
        REFERENCES Student (StudentID)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT Bonus_Task_Record_BonusQuestionID FOREIGN KEY (BonusQuestionID)
        REFERENCES Bonus_Task (BonusQuestionID)
        ON DELETE CASCADE ON UPDATE CASCADE
)  ENGINE=INNODB;
 
SET FOREIGN_KEY_CHECKS=1;

# INSERT RAW DATA FOR TEST

# [Example] User Info
INSERT IGNORE INTO School(SchoolName) VALUES('Sample School');
INSERT IGNORE INTO School(SchoolName) VALUES('Sample Adelaide High School');
INSERT IGNORE INTO School(SchoolName) VALUES('Sample Woodville High School');
INSERT IGNORE INTO Class(ClassName,SchoolID) VALUES('Sample Class 1A',1);
INSERT IGNORE INTO Class(ClassName,SchoolID) VALUES('Sample Class 1B',1);
INSERT IGNORE INTO Class(ClassName,SchoolID) VALUES('Sample Class 1C',1);
INSERT IGNORE INTO Class(ClassName,SchoolID) VALUES('Sample Class 2C',2);
INSERT IGNORE INTO Token(`Type`,TokenString,ClassID) VALUES('STUDENT','TOKENSTRING01',1);
INSERT IGNORE INTO Token(`Type`,TokenString,ClassID) VALUES('TEACHER','TOKENSTRING02',1);
INSERT IGNORE INTO Token(`Type`,TokenString,ClassID) VALUES('STUDENT','TOKENSTRING03',2);
INSERT IGNORE INTO Token(`Type`,TokenString,ClassID) VALUES('TEACHER','TOKENSTRING04',2);
INSERT IGNORE INTO Token(`Type`,TokenString,ClassID) VALUES('STUDENT','TOKENSTRING03',3);
INSERT IGNORE INTO Token(`Type`,TokenString,ClassID) VALUES('TEACHER','TOKENSTRING04',3);
INSERT IGNORE INTO Token(`Type`,TokenString,ClassID) VALUES('STUDENT','TOKENSTRING03',4);
INSERT IGNORE INTO Token(`Type`,TokenString,ClassID) VALUES('TEACHER','TOKENSTRING04',4);

INSERT IGNORE INTO Student(Username,`Password`,Email,FirstName,LastName,Gender,DOB,ClassID) VALUES('Fernando','d59324e4d5acb950c4022cd5df834cc3','fernado@gmail.com','Fernando','Trump','Male',"2003-10-20",1);
INSERT IGNORE INTO Student(Username,`Password`,Email,FirstName,LastName,Gender,DOB,ClassID,Score) VALUES('Todd','d59324e4d5acb950c4022cd5df834cc3','toddyy@gmail.com','Todd','Webb','Male',"2003-11-20",1,55);
INSERT IGNORE INTO Student(Username,`Password`,Email,FirstName,LastName,Gender,DOB,ClassID,Score) VALUES('Theresa','d59324e4d5acb950c4022cd5df834cc3','theresa03@gmail.com','Theresa','Rios','Female',"2003-12-20",1,90);
INSERT IGNORE INTO Student(Username,`Password`,Email,FirstName,LastName,Gender,DOB,ClassID,Score) VALUES('Hai','d59324e4d5acb950c4022cd5df834cc3','isnap2demo@gmail.com','Hai','Lam','Male',"2003-10-22",1,30);
INSERT IGNORE INTO Student(Username,`Password`,Email,FirstName,LastName,Gender,DOB,ClassID,Score) VALUES('Lee','d59324e4d5acb950c4022cd5df834cc3','isnap2demo@gmail.com','Lee','Malone','Male',"2003-10-24",1,45);
INSERT IGNORE INTO Student(Username,`Password`,Email,FirstName,LastName,Gender,DOB,ClassID,Score) VALUES('Tim','d59324e4d5acb950c4022cd5df834cc3','isnap2demo@gmail.com','Tim','Mason','Male',"2003-10-25",1,60);
INSERT IGNORE INTO Student(Username,`Password`,Email,FirstName,LastName,Gender,DOB,ClassID) VALUES('Clinton','d59324e4d5acb950c4022cd5df834cc3','isnap2demo@gmail.com','Clinton','Snyder','Male',"2003-10-28",1);
INSERT IGNORE INTO Student(Username,`Password`,Email,FirstName,LastName,Gender,DOB,ClassID) VALUES('Elbert','d59324e4d5acb950c4022cd5df834cc3','isnap2demo@gmail.com','Elbert','Chapman','Male',"2003-10-22",1);
INSERT IGNORE INTO Student(Username,`Password`,Email,FirstName,LastName,Gender,DOB,ClassID) VALUES('Ervin','d59324e4d5acb950c4022cd5df834cc3','isnap2demo@gmail.com','Ervin','Murray','Male',"2003-11-20",1);
INSERT IGNORE INTO Student(Username,`Password`,Email,FirstName,LastName,Gender,DOB,ClassID) VALUES('Sheila','d59324e4d5acb950c4022cd5df834cc3','isnap2demo@gmail.com','Sheila','Frank','Female',"2003-10-20",1);
INSERT IGNORE INTO Student(Username,`Password`,Email,FirstName,LastName,Gender,DOB,ClassID) VALUES('Grace','d59324e4d5acb950c4022cd5df834cc3','isnap2demo@gmail.com','Grace','Austin','Female',"2003-10-29",1);
INSERT IGNORE INTO Student(Username,`Password`,Email,FirstName,LastName,Gender,DOB,ClassID) VALUES('Ruby','d59324e4d5acb950c4022cd5df834cc3','isnap2demo@gmail.com','Ruby','Chavez','Female',"2003-10-20",1);
INSERT IGNORE INTO Student(Username,`Password`,Email,FirstName,LastName,Gender,DOB,ClassID) VALUES('Sonya','d59324e4d5acb950c4022cd5df834cc3','isnap2demo@gmail.com','Sonya','Kelly','Female',"2003-10-20",1);
INSERT IGNORE INTO Student(Username,`Password`,Email,FirstName,LastName,Gender,DOB,ClassID) VALUES('Donna','d59324e4d5acb950c4022cd5df834cc3','isnap2demo@gmail.com','Donna','Pratt','Female',"2003-10-20",1);
INSERT IGNORE INTO Student(Username,`Password`,Email,FirstName,LastName,Gender,DOB,ClassID) VALUES('Stacy','d59324e4d5acb950c4022cd5df834cc3','isnap2demo@gmail.com','Stacy','Figueroa','Female',"2003-10-20",1);
INSERT IGNORE INTO Student(Username,`Password`,Email,FirstName,LastName,Gender,DOB,ClassID) VALUES('Fannie','d59324e4d5acb950c4022cd5df834cc3','isnap2demo@gmail.com','Fannie','Waters','Female',"2003-10-28",1);
INSERT IGNORE INTO Student(Username,`Password`,Email,FirstName,LastName,Gender,DOB,ClassID) VALUES('June','d59324e4d5acb950c4022cd5df834cc3','isnap2demo@gmail.com','June','West','Female',"2003-10-20",1);
INSERT IGNORE INTO Student(Username,`Password`,Email,FirstName,LastName,Gender,DOB,ClassID) VALUES('Melinda','d59324e4d5acb950c4022cd5df834cc3','isnap2demo@gmail.com','Melinda','Kelley','Female',"2003-10-20",1);
INSERT IGNORE INTO Student(Username,`Password`,Email,FirstName,LastName,Gender,DOB,ClassID) VALUES('Leo','d59324e4d5acb950c4022cd5df834cc3','isnap2demo@gmail.com','Leo','Potter','Male',"2002-04-22",1);
INSERT IGNORE INTO Student(Username,`Password`,Email,FirstName,LastName,Gender,DOB,ClassID) VALUES('Hector','d59324e4d5acb950c4022cd5df834cc3','isnap2demo@gmail.com','Hector','Byrd','Male',"2002-04-20",1);
INSERT IGNORE INTO Student(Username,`Password`,Email,FirstName,LastName,Gender,DOB,ClassID) VALUES('Otis','d59324e4d5acb950c4022cd5df834cc3','isnap2demo@gmail.com','Otis','Lawrence','Male',"2002-04-20",2);
INSERT IGNORE INTO Student(Username,`Password`,Email,FirstName,LastName,Gender,DOB,ClassID) VALUES('Cassandra','d59324e4d5acb950c4022cd5df834cc3','isnap2demo@gmail.com','Cassandra','James','Female',"2002-04-20",2);
INSERT IGNORE INTO Student(Username,`Password`,Email,FirstName,LastName,Gender,DOB,ClassID) VALUES('Marilyn','d59324e4d5acb950c4022cd5df834cc3','isnap2demo@gmail.com','Marilyn','Ryan','Female',"2002-04-20",1);
INSERT IGNORE INTO Teacher(Username,`Password`,ClassID) VALUES('Lynette','d59324e4d5acb950c4022cd5df834cc3',1);
INSERT IGNORE INTO Teacher(Username,`Password`,ClassID) VALUES('Rachael','d59324e4d5acb950c4022cd5df834cc3',2);
INSERT IGNORE INTO Researcher(Username,`Password`) VALUES('Ann','d59324e4d5acb950c4022cd5df834cc3');
INSERT IGNORE INTO Researcher(Username,`Password`) VALUES('Patricia','d59324e4d5acb950c4022cd5df834cc3');

# [Formal] Topic
INSERT IGNORE INTO Topic(TopicName) VALUES('Smoking');
INSERT IGNORE INTO Topic(TopicName) VALUES('Nutrition');
INSERT IGNORE INTO Topic(TopicName) VALUES('Alcohol');
INSERT IGNORE INTO Topic(TopicName) VALUES('Physical Activity');
INSERT IGNORE INTO Topic(TopicName) VALUES('Introduction');


# [Formal] insert MCQ section with multiple questions
INSERT IGNORE INTO Quiz(Week,QuizType,TopicID) VALUES(1,'MCQ',2);
SET @QUIZ_LAST_INSERT_ID = LAST_INSERT_ID();
INSERT IGNORE INTO MCQ_Section(QuizID,Points,Questionnaires) VALUES(@QUIZ_LAST_INSERT_ID,30,0);
INSERT IGNORE INTO MCQ_Question(Question, CorrectChoice, QuizID) VALUES('Which of these breakfast foods will provide you with the most energy?', 'Whole grain cereal or oatmeal', @QUIZ_LAST_INSERT_ID);
SET @MCQ_QUESTION_LAST_INSERT_ID = LAST_INSERT_ID();
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('Candy bar', 'Candy bars will give you an instant burst of energy but will not last!', @MCQ_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('Whole grain cereal or oatmeal', 'Whole grains take your body longer to digest, giving you energy all morning!', @MCQ_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('Potato chips', 'Whole grains take your body longer to digest, giving you energy all morning!', @MCQ_QUESTION_LAST_INSERT_ID);


INSERT IGNORE INTO MCQ_Question(Question, CorrectChoice, QuizID) VALUES('Which type of food should take up the most space on your plate?', 'Fruits and veggies', @QUIZ_LAST_INSERT_ID);
SET @MCQ_QUESTION_LAST_INSERT_ID = LAST_INSERT_ID();
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('Fruits and veggies', 'Get munching on carrots, apples, and other tasty fresh foods! The veggies and fruits should take up at least half of your plate.', @MCQ_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('Meats', 'Get munching on carrots, apples, and other tasty fresh foods! The veggies and fruits should take up at least half of your plate.', @MCQ_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('Grains', 'Get munching on carrots, apples, and other tasty fresh foods! The veggies and fruits should take up at least half of your plate.', @MCQ_QUESTION_LAST_INSERT_ID);

INSERT IGNORE INTO MCQ_Question(Question, CorrectChoice, QuizID) VALUES('What should I do if I hate broccoli?', 'Give peas a chance!', @QUIZ_LAST_INSERT_ID);
SET @MCQ_QUESTION_LAST_INSERT_ID = LAST_INSERT_ID();
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('Feed it to your dog.', 'Not everyone likes broccoli. But there are so many different kinds of vegetables, you are bound to find one you like!', @MCQ_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('Give up on eating vegetables.', 'Not everyone likes broccoli. But there are so many different kinds of vegetables, you are bound to find one you like!', @MCQ_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('Give peas a chance!', 'Not everyone likes broccoli. But there are so many different kinds of vegetables, you are bound to find one you like!', @MCQ_QUESTION_LAST_INSERT_ID);

INSERT IGNORE INTO MCQ_Question(Question, CorrectChoice, QuizID) VALUES('If I want to stay healthy, can I still eat French fries?', 'Sure, just not every day.', @QUIZ_LAST_INSERT_ID);
SET @MCQ_QUESTION_LAST_INSERT_ID = LAST_INSERT_ID();
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('No fast food, ever.', 'Eating healthy doesn\'t mean cutting out ALL fried foods. Foods like French fries are ok if you eat a small amount once or twice a month.', @MCQ_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('No, but American fries are ok.', 'Eating healthy doesn\'t mean cutting out ALL fried foods. Foods like French fries are ok if you eat a small amount once or twice a month.', @MCQ_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('Sure, just not every day.', 'Eating healthy doesn\'t mean cutting out ALL fried foods. Foods like French fries are ok if you eat a small amount once or twice a month.', @MCQ_QUESTION_LAST_INSERT_ID);

INSERT IGNORE INTO MCQ_Question(Question, CorrectChoice, QuizID) VALUES('What\'s a nutritious afterschool snack?', 'An apple, cheese, and whole grain crackers.', @QUIZ_LAST_INSERT_ID);
SET @MCQ_QUESTION_LAST_INSERT_ID = LAST_INSERT_ID();
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('Potato chips and soda.', 'Eating healthy snacks is important. Snacks give you energy and help you feel full so you don\'t overeat at dinner.', @MCQ_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('An apple, cheese, and whole grain crackers.', 'Eating healthy snacks is important. Snacks give you energy and help you feel full so you don\'t overeat at dinner.', @MCQ_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('A doughnut or a brownie.', 'Eating healthy snacks is important. Snacks give you energy and help you feel full so you don\'t overeat at dinner.', @MCQ_QUESTION_LAST_INSERT_ID);

INSERT IGNORE INTO MCQ_Question(Question, CorrectChoice, QuizID) VALUES('How much veggies and fruit should you eat daily?', '1 to 2 cups of veggies and 1 to 2 pieces of fruit every day.', @QUIZ_LAST_INSERT_ID);
SET @MCQ_QUESTION_LAST_INSERT_ID = LAST_INSERT_ID();
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('1 to 2 cups of veggies and 1 to 2 pieces of fruit every day.', 'Fortunately, there are so many types of fruits and vegetables that you\'ll never get bored!', @MCQ_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('Eat veggies or fruit once a month.', 'Fortunately, there are so many types of fruits and vegetables that you\'ll never get bored!', @MCQ_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('At least 100 cups a day.', 'Fortunately, there are so many types of fruits and vegetables that you\'ll never get bored!', @MCQ_QUESTION_LAST_INSERT_ID);

INSERT IGNORE INTO MCQ_Question(Question, CorrectChoice, QuizID) VALUES('Which of these foods is the best source of calcium?', 'Yogurt', @QUIZ_LAST_INSERT_ID);
SET @MCQ_QUESTION_LAST_INSERT_ID = LAST_INSERT_ID();
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('Bread', 'Calcium is important for building bones. You can get your daily dose from a variety of foods, including yogurt, milk, and almonds.', @MCQ_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('Yogurt', 'Calcium is important for building bones. You can get your daily dose from a variety of foods, including yogurt, milk, and almonds.', @MCQ_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('Apples', 'Calcium is important for building bones. You can get your daily dose from a variety of foods, including yogurt, milk, and almonds.', @MCQ_QUESTION_LAST_INSERT_ID);

INSERT IGNORE INTO MCQ_Question(Question, CorrectChoice, QuizID) VALUES('Which of these foods has lots of fiber?', 'Beans and apples', @QUIZ_LAST_INSERT_ID);
SET @MCQ_QUESTION_LAST_INSERT_ID = LAST_INSERT_ID();
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('White rice', 'Eating foods that have fiber helps with digestion and keeps you from getting hungry too soon.', @MCQ_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('Pasta', 'Eating foods that have fiber helps with digestion and keeps you from getting hungry too soon.', @MCQ_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('Beans and apples', 'Eating foods that have fiber helps with digestion and keeps you from getting hungry too soon.', @MCQ_QUESTION_LAST_INSERT_ID);

INSERT IGNORE INTO MCQ_Question(Question, CorrectChoice, QuizID) VALUES('What should you drink the most of each day?', 'Water', @QUIZ_LAST_INSERT_ID);
SET @MCQ_QUESTION_LAST_INSERT_ID = LAST_INSERT_ID();
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('Milk', 'You should drink 6-8 cups of water a day. Cheers!', @MCQ_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('Water', 'You should drink 6-8 cups of water a day. Cheers!', @MCQ_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('Orange Juice', 'You should drink 6-8 cups of water a day. Cheers!', @MCQ_QUESTION_LAST_INSERT_ID);



INSERT IGNORE INTO Quiz(Week,QuizType,TopicID) VALUES(1,'MCQ',5);
SET @QUIZ_LAST_INSERT_ID = LAST_INSERT_ID();
INSERT IGNORE INTO MCQ_Section(QuizID,Points,Questionnaires) VALUES(@QUIZ_LAST_INSERT_ID,20,0);
INSERT IGNORE INTO MCQ_Question(Question, CorrectChoice, QuizID) VALUES('Alcohol has an immediate effect on the:', 'Brain', @QUIZ_LAST_INSERT_ID);
SET @MCQ_QUESTION_LAST_INSERT_ID = LAST_INSERT_ID();
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('Knees', "Wrong", @MCQ_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('Fingers', "Wrong", @MCQ_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('Chest', "Wrong", @MCQ_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('Brain', "Correct", @MCQ_QUESTION_LAST_INSERT_ID);

INSERT IGNORE INTO MCQ_Question(Question, CorrectChoice, QuizID) VALUES('Alcohol increases the risk of:', 'All of the above.', @QUIZ_LAST_INSERT_ID);
SET @MCQ_QUESTION_LAST_INSERT_ID = LAST_INSERT_ID();
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('A person being involved in anti-social behaviour.', "Wrong", @MCQ_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('Injury due to falls, burns, car crashes.', "Wrong", @MCQ_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('Violence and fighting.', "Wrong", @MCQ_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('All of the above.', "Correct", @MCQ_QUESTION_LAST_INSERT_ID);

INSERT IGNORE INTO MCQ_Question(Question, CorrectChoice, QuizID) VALUES('When a person continues to drink:', 'Their blood alcohol content (BAC) increases.', @QUIZ_LAST_INSERT_ID);
SET @MCQ_QUESTION_LAST_INSERT_ID = LAST_INSERT_ID();
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('Their  blood alcohol content (BAC) decreases.', "Wrong", @MCQ_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('Their blood alcohol content (BAC) increases.', "Correct", @MCQ_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('Their blood alcohol content (BAC) remains the same', "Wrong", @MCQ_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('Their blood alcohol content (BAC) reduces to zero', "Wrong", @MCQ_QUESTION_LAST_INSERT_ID);

INSERT IGNORE INTO MCQ_Question(Question, CorrectChoice, QuizID) VALUES('Alcohol is a:', 'Drug that targets the brain.', @QUIZ_LAST_INSERT_ID);
SET @MCQ_QUESTION_LAST_INSERT_ID = LAST_INSERT_ID();
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('Drug that has no effects on you.', "Wrong", @MCQ_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('Drug that targets the brain.', "Correct", @MCQ_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('Drug that you do not need to worry about.', "Wrong", @MCQ_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('Drug that does not affect your behaviour.', "Wrong", @MCQ_QUESTION_LAST_INSERT_ID);

INSERT IGNORE INTO MCQ_Question(Question, CorrectChoice, QuizID) VALUES('Alcohol is broken down by:', 'Liver', @QUIZ_LAST_INSERT_ID);
SET @MCQ_QUESTION_LAST_INSERT_ID = LAST_INSERT_ID();
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('Blood', "Wrong", @MCQ_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('Heart', "Wrong", @MCQ_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('Liver', "Correct", @MCQ_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('Kidney', "Wrong", @MCQ_QUESTION_LAST_INSERT_ID);


# [Example] Student Progress
# StudentID = 1 has not finished QuizID neither 1, 2 nor 3
INSERT IGNORE INTO Quiz_Record(QuizID,StudentID) VALUES(1,2);
INSERT IGNORE INTO Quiz_Record(QuizID,StudentID) VALUES(1,3);
INSERT IGNORE INTO Quiz_Record(QuizID,StudentID) VALUES(1,4);
INSERT IGNORE INTO Quiz_Record(QuizID,StudentID) VALUES(1,5);
INSERT IGNORE INTO Quiz_Record(QuizID,StudentID) VALUES(1,6);

INSERT IGNORE INTO Quiz_Record(QuizID,StudentID) VALUES(2,2);
INSERT IGNORE INTO Quiz_Record(QuizID,StudentID) VALUES(2,3);
INSERT IGNORE INTO Quiz_Record(QuizID,StudentID) VALUES(2,4);
INSERT IGNORE INTO Quiz_Record(QuizID,StudentID) VALUES(2,5);
INSERT IGNORE INTO Quiz_Record(QuizID,StudentID) VALUES(2,6);

# [Formal] insert SAQ section with questions
INSERT IGNORE INTO Quiz(Week,QuizType,TopicID) VALUES(1,'SAQ',1);
SET @QUIZ_LAST_INSERT_ID = LAST_INSERT_ID();
INSERT IGNORE INTO SAQ_Section(QuizID) VALUES(@QUIZ_LAST_INSERT_ID);
INSERT IGNORE INTO SAQ_Question(Question, Points, QuizID) VALUES('Based on the video, list 3 problems or challenges that these teenagers face as a result of their smoking?', 10, @QUIZ_LAST_INSERT_ID);
INSERT IGNORE INTO SAQ_Question(Question, Points, QuizID) VALUES('List 1 strategy that you could use to help convince a peer to stop smoking?', 10, @QUIZ_LAST_INSERT_ID);
INSERT IGNORE INTO SAQ_Question(Question, Points, QuizID) VALUES('List 3 the different ways that you have seen anti-smoking messages presented to the public. With each suggest if you think they have been ‘effective’ or ‘not effective’. Eg. Poster-Effective.', 20, @QUIZ_LAST_INSERT_ID);

# [Sample] Answer and Grading Feedback of SAQ 
INSERT IGNORE INTO Quiz_Record(QuizID, StudentID,`Status`) VALUES(3, 1, 'GRADED');
INSERT IGNORE INTO SAQ_Question_Record(StudentID, SAQID, Answer, Feedback, Grading) VALUES(1, 1, "[ANSWER] Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque non justo et tellus venenatis consequat. Suspendisse laoreet rhoncus nulla, quis vulputate arcu interdum vel. Aenean at nisl at enim imperdiet rhoncus in non risus. Nam augue nisi, blandit sed feugiat eu, dapibus tristique ipsum. Vestibulum molestie orci risus, accumsan convallis sem sagittis mattis. Nulla ac justo sit amet erat lacinia vulputate. Aliquam accumsan pellentesque magna ac ultricies. Cras consequat feugiat suscipit. Vivamus suscipit lobortis nunc at aliquet. Nullam orci diam, viverra sed interdum ac, vehicula vel nisi. Cras blandit erat eget purus maximus condimentum. Nullam mattis pellentesque velit ac euismod. Nam vehicula est vel iaculis hendrerit. Vivamus pellentesque leo nec eleifend sodales. Phasellus eget condimentum metus.", "+10: Good job!", 10);
INSERT IGNORE INTO SAQ_Question_Record(StudentID, SAQID, Answer, Feedback, Grading) VALUES(1, 2, "[ANSWER] Nunc rhoncus turpis eu risus pharetra, et pharetra libero euismod. Donec ac tellus consequat, aliquam ligula in, semper erat. Praesent ut justo auctor, imperdiet nisi quis, bibendum dolor. Nunc iaculis aliquet est ac maximus. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Suspendisse vel elit felis. Duis accumsan arcu cursus dapibus vulputate. Maecenas sit amet euismod orci. Sed imperdiet justo quis eros porta tristique eu a mi. Donec at est lacus. Vivamus viverra, purus ut tempor auctor, tellus massa hendrerit elit, tristique ornare mauris dolor vitae ante.", "+10: Well done!", 10);
INSERT IGNORE INTO SAQ_Question_Record(StudentID, SAQID, Answer, Feedback, Grading) VALUES(1, 3, "[ANSWER] Nam odio tortor, finibus sit amet metus vitae, egestas venenatis arcu. Maecenas sodales, mi vitae tincidunt interdum, urna ipsum sagittis orci, semper mollis nisl ex ut felis. Vivamus lectus justo, interdum sit amet enim id, euismod posuere erat. Pellentesque auctor elit eget finibus placerat. Vivamus sodales dolor non ligula molestie aliquam. Ut at metus ut mauris consequat sollicitudin. Suspendisse non ipsum at neque molestie feugiat.", "+20: Nice try. <br> -2: You should also mention Poster-Effective.", 18);

INSERT IGNORE INTO Quiz(Week,QuizType,TopicID) VALUES(3,'SAQ',4);
SET @QUIZ_LAST_INSERT_ID = LAST_INSERT_ID();
INSERT IGNORE INTO SAQ_Section(QuizID) VALUES(@QUIZ_LAST_INSERT_ID);
INSERT IGNORE INTO SAQ_Question(Question, Points, QuizID) VALUES('How much exercise do you think you do a day?', 10, @QUIZ_LAST_INSERT_ID);
INSERT IGNORE INTO SAQ_Question(Question, Points, QuizID) VALUES('Do you think that you are exercising enough? Why/whynot?', 10, @QUIZ_LAST_INSERT_ID);
INSERT IGNORE INTO SAQ_Question(Question, Points, QuizID) VALUES('What are the benefits of exercising? List 5 examples.', 20, @QUIZ_LAST_INSERT_ID);
INSERT IGNORE INTO SAQ_Question(Question, Points, QuizID) VALUES('What changes can you make to your daily routine to incorporate more exercise into your life?', 20, @QUIZ_LAST_INSERT_ID);



# [Formal] Week 6
INSERT IGNORE INTO Quiz(Week,QuizType,TopicID) VALUES(6,'Calculator',3);
INSERT IGNORE INTO Quiz(Week,QuizType,TopicID) VALUES(6,'MCQ',3);
# [Formal] Matching quesitons
INSERT IGNORE INTO Quiz(Week,QuizType,TopicID) VALUES(6,'Matching',2);
SET @QUIZ_LAST_INSERT_ID = LAST_INSERT_ID();
INSERT IGNORE INTO Matching_Section(QuizID, Explanation, Points) VALUES(@QUIZ_LAST_INSERT_ID, 'Match the diseases to the causes. You may have to do some research on other websites to find out the answers.', 20);
INSERT IGNORE INTO Matching_Question(Question, QuizID) VALUES('Kwashiorkor', @QUIZ_LAST_INSERT_ID);
SET @MATCHING_QUESTION_LAST_INSERT_ID = LAST_INSERT_ID();
INSERT IGNORE INTO `Matching_Option`(Content, MatchingQuestionID) VALUES('A disease that occurs if your body doesn’t get enough proteins', @MATCHING_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO Matching_Question(Question, QuizID) VALUES('Marasmus', @QUIZ_LAST_INSERT_ID);
SET @MATCHING_QUESTION_LAST_INSERT_ID = LAST_INSERT_ID();
INSERT IGNORE INTO `Matching_Option`(Content, MatchingQuestionID) VALUES('Occurs in young children who don’t get enough calories every day', @MATCHING_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO Matching_Question(Question, QuizID) VALUES('Scurvy', @QUIZ_LAST_INSERT_ID);
SET @MATCHING_QUESTION_LAST_INSERT_ID = LAST_INSERT_ID();
INSERT IGNORE INTO `Matching_Option`(Content, MatchingQuestionID) VALUES('Caused by a lack of vitamin C', @MATCHING_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO Matching_Question(Question, QuizID) VALUES('Rickets', @QUIZ_LAST_INSERT_ID);
SET @MATCHING_QUESTION_LAST_INSERT_ID = LAST_INSERT_ID();
INSERT IGNORE INTO `Matching_Option`(Content, MatchingQuestionID) VALUES('This condition is brought on by a lack of vitamin D', @MATCHING_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO Matching_Question(Question, QuizID) VALUES('Beriberi', @QUIZ_LAST_INSERT_ID);
SET @MATCHING_QUESTION_LAST_INSERT_ID = LAST_INSERT_ID();
INSERT IGNORE INTO `Matching_Option`(Content, MatchingQuestionID) VALUES('Caused by the deficiency of vitamin B1 (thiamine) ', @MATCHING_QUESTION_LAST_INSERT_ID);

# [Example] Week 7 MultipleChoices Matching
INSERT IGNORE INTO Quiz(Week,QuizType,TopicID) VALUES(7,'Matching',2);
SET @QUIZ_LAST_INSERT_ID = LAST_INSERT_ID();
INSERT IGNORE INTO Matching_Section(QuizID, Explanation, Points, MultipleChoices) VALUES(@QUIZ_LAST_INSERT_ID, 'Classify the lists of foods into the 5 main food groups', 20, 1);
INSERT IGNORE INTO Matching_Question(Question, QuizID) VALUES('Protein', @QUIZ_LAST_INSERT_ID);
SET @MATCHING_QUESTION_LAST_INSERT_ID = LAST_INSERT_ID();
INSERT IGNORE INTO `Matching_Option`(Content, MatchingQuestionID) VALUES('Beef', @MATCHING_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO `Matching_Option`(Content, MatchingQuestionID) VALUES('Beef', @MATCHING_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO `Matching_Option`(Content, MatchingQuestionID) VALUES('Beef', @MATCHING_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO `Matching_Option`(Content, MatchingQuestionID) VALUES('Beef', @MATCHING_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO Matching_Question(Question, QuizID) VALUES('Fat', @QUIZ_LAST_INSERT_ID);
SET @MATCHING_QUESTION_LAST_INSERT_ID = LAST_INSERT_ID();
INSERT IGNORE INTO `Matching_Option`(Content, MatchingQuestionID) VALUES('Chips', @MATCHING_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO `Matching_Option`(Content, MatchingQuestionID) VALUES('Chips', @MATCHING_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO `Matching_Option`(Content, MatchingQuestionID) VALUES('Chips', @MATCHING_QUESTION_LAST_INSERT_ID);
#INSERT IGNORE INTO `Matching_Option`(Content, MatchingQuestionID) VALUES('Chips', @MATCHING_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO Matching_Question(Question, QuizID) VALUES('Vitamin', @QUIZ_LAST_INSERT_ID);
SET @MATCHING_QUESTION_LAST_INSERT_ID = LAST_INSERT_ID();
INSERT IGNORE INTO `Matching_Option`(Content, MatchingQuestionID) VALUES('Orange', @MATCHING_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO `Matching_Option`(Content, MatchingQuestionID) VALUES('Orange', @MATCHING_QUESTION_LAST_INSERT_ID);
#INSERT IGNORE INTO `Matching_Option`(Content, MatchingQuestionID) VALUES('Orange', @MATCHING_QUESTION_LAST_INSERT_ID);
#INSERT IGNORE INTO `Matching_Option`(Content, MatchingQuestionID) VALUES('Orange', @MATCHING_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO Matching_Question(Question, QuizID) VALUES('Minerals', @QUIZ_LAST_INSERT_ID);
SET @MATCHING_QUESTION_LAST_INSERT_ID = LAST_INSERT_ID();
INSERT IGNORE INTO `Matching_Option`(Content, MatchingQuestionID) VALUES('Fish', @MATCHING_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO `Matching_Option`(Content, MatchingQuestionID) VALUES('Fish', @MATCHING_QUESTION_LAST_INSERT_ID);
#INSERT IGNORE INTO `Matching_Option`(Content, MatchingQuestionID) VALUES('Fish', @MATCHING_QUESTION_LAST_INSERT_ID);
#INSERT IGNORE INTO `Matching_Option`(Content, MatchingQuestionID) VALUES('Fish', @MATCHING_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO Matching_Question(Question, QuizID) VALUES('Carbohydrate', @QUIZ_LAST_INSERT_ID);
SET @MATCHING_QUESTION_LAST_INSERT_ID = LAST_INSERT_ID();
INSERT IGNORE INTO `Matching_Option`(Content, MatchingQuestionID) VALUES('Rice', @MATCHING_QUESTION_LAST_INSERT_ID);
#INSERT IGNORE INTO `Matching_Option`(Content, MatchingQuestionID) VALUES('Rice', @MATCHING_QUESTION_LAST_INSERT_ID);
#INSERT IGNORE INTO `Matching_Option`(Content, MatchingQuestionID) VALUES('Rice', @MATCHING_QUESTION_LAST_INSERT_ID);
#INSERT IGNORE INTO `Matching_Option`(Content, MatchingQuestionID) VALUES('Rice', @MATCHING_QUESTION_LAST_INSERT_ID);



# [Formal] Learning_Material
INSERT IGNORE INTO Learning_Material(Content,QuizID) VALUES('<p>Eating a balanced diet is vital for your health and wellbeing. The food we eat is responsible for providing us with the energy to do all the tasks of daily life. For optimum performance and growth a balance of protein, essential fats, vitamins and minerals are required. We need a wide variety of different foods to provide the right amounts of nutrients for good health. The different types of food and how much of it you should be aiming to eat is demonstrated on the pyramid below. (my own words)</p>
<p><img style="display: block; margin-left: auto; margin-right: auto;" src="https://cmudream.files.wordpress.com/2016/05/0.jpg" alt="" width="632" height="884" /></p>
<p>There are three main layers of the food pyramid. The bottom layer is the most important one for your daily intake of food. It contains vegetables, fruits, grains and legumes. You should be having most of your daily food from this layer. These foods are all derived or grow on plants and contain important nutrients such as vitamins, minerals and antioxidants. They are also responsible for being the main contributor of carbohydrates and fibre to our diet.<br />The middle layer is comprised of dairy based products such as milk, yoghurt, cheese. These are essential to providing our bodies with calcium and protein and important vitamins and minerals.<br />They layer also contains lean meat, poultry, fish, eggs, nuts, seeds, legumes. These foods are our main source of protein and are also responsible for providing other nutrients to us including iodine, iron, zinc, B12 vitamins and healthy fats.<br />The top layer, which is the smallest layer, is the layer you should me eating the least off. This layer is made up of food which has unsaturated fats such as sugar, butter, margarine and oils; small amounts of these unsaturated fats are needed for healthy brain and hear function.<br />(my own words)<br />Source: The Healthy Living Pyramid. Nutrition Australia. [Accessed 28/04/2016 http://www.nutritionaustralia.org/national/resource/healthy-living-pyramid]</p>',1);
INSERT IGNORE INTO Learning_Material(Content,QuizID) VALUES('https://www.youtube.com/watch?v=1ey0EDVjyeY&index=89&list=PLIGEVr8ox1oGsi-XcwSjudMi_uCPxGzSs',2);
INSERT IGNORE INTO Learning_Material(Content,QuizID) VALUES('
<p>Learning materials for week 3.</p>',3);
INSERT IGNORE INTO Learning_Material(Content,QuizID) VALUES('
<p>Learning materials for this quiz has not been added.</p>',4);
INSERT IGNORE INTO Learning_Material(Content,QuizID) VALUES('
<p>Learning materials for this quiz has not been added.</p>',5);
INSERT IGNORE INTO Learning_Material(Content,QuizID) VALUES('
<p>Learning materials for this quiz has not been added.</p>',6);
INSERT IGNORE INTO Learning_Material(Content,QuizID) VALUES('
<p>Nutrition: All over the world people suffer from illnesses that are caused by eating the wrong food or not having enough to eat. In developing countries deficiency diseases arise when people do not get the right nutrients. Conversely, overconsumption of foods rich in fat and cholesterols can lead to heart diseases, obesity, strokes and cancer. (Own words)</p>',7);
INSERT IGNORE INTO Learning_Material(Content,QuizID) VALUES('
<p>Learning materials for this quiz has not been added.</p>',8);

# [Formal] Games
INSERT IGNORE INTO Game(Description,Week,Points) VALUES('Fruit Ninja',1,10);
INSERT IGNORE INTO Game(Description,Week,Points) VALUES('Candy Crush',1,10);

# [Example] Game_Record
INSERT IGNORE INTO Game_Record(GameID,StudentID,`Level`,Score) VALUES(1,2,1,30);
INSERT IGNORE INTO Game_Record(GameID,StudentID,`Level`,Score) VALUES(1,3,1,30);
INSERT IGNORE INTO Game_Record(GameID,StudentID,`Level`,Score) VALUES(1,4,1,30);
INSERT IGNORE INTO Game_Record(GameID,StudentID,`Level`,Score) VALUES(2,2,1,35);
INSERT IGNORE INTO Game_Record(GameID,StudentID,`Level`,Score) VALUES(2,3,1,30);
INSERT IGNORE INTO Game_Record(GameID,StudentID,`Level`,Score) VALUES(2,4,1,30);
INSERT IGNORE INTO Game_Record(GameID,StudentID,`Level`,Score) VALUES(2,5,1,40);

# [Example] add Bonus and tasks
INSERT IGNORE INTO Bonus(Week) VALUES(1);
SET @BONUS_LAST_INSERT_ID = LAST_INSERT_ID();
INSERT IGNORE INTO Bonus_Task(Question, Points, BonusID) VALUES('Prepare a meal for your mom.', 10, @BONUS_LAST_INSERT_ID);

INSERT IGNORE INTO Bonus(Week) VALUES(2);
SET @BONUS_LAST_INSERT_ID = LAST_INSERT_ID();
INSERT IGNORE INTO Bonus_Task(Question, Points, BonusID) VALUES('Attend a basketball game.', 10, @BONUS_LAST_INSERT_ID);

INSERT IGNORE INTO Bonus(Week) VALUES(3);
SET @BONUS_LAST_INSERT_ID = LAST_INSERT_ID();
INSERT IGNORE INTO Bonus_Task(Question, Points, BonusID) VALUES('Attend a football game', 10, @BONUS_LAST_INSERT_ID);

# [Example] update Submission time
UPDATE `isnap2changedb`.`student` SET `Score`='80' WHERE `StudentID`='10';
UPDATE `isnap2changedb`.`student` SET `Score`='70', `SubmissionTime`='2016-06-05 14:48:43' WHERE `StudentID`='12';
UPDATE `isnap2changedb`.`student` SET `Score`='70', `SubmissionTime`='2016-06-05 14:48:43' WHERE `StudentID`='8';
UPDATE `isnap2changedb`.`student` SET `Score`='20' WHERE `StudentID`='14';
UPDATE `isnap2changedb`.`student` SET `Score`='70', `SubmissionTime`='2016-06-01 14:48:42' WHERE `StudentID`='16';
UPDATE `isnap2changedb`.`student` SET `Score`='10' WHERE `StudentID`='18';
UPDATE `isnap2changedb`.`student` SET `SubmissionTime`='2016-06-02 14:48:43' WHERE `StudentID`='2';
UPDATE `isnap2changedb`.`student` SET `SubmissionTime`='2016-06-03 14:48:43' WHERE `StudentID`='3';
UPDATE `isnap2changedb`.`student` SET `SubmissionTime`='2016-06-01 14:49:43' WHERE `StudentID`='4';
UPDATE `isnap2changedb`.`student` SET `SubmissionTime`='2016-06-07 14:48:43' WHERE `StudentID`='5';
UPDATE `isnap2changedb`.`student` SET `SubmissionTime`='2016-06-11 14:48:43' WHERE `StudentID`='6';

# [Example] insert facts
INSERT INTO `isnap2changedb`.`fact` (`Content`, `TopicID`) VALUES ('Each day, more than 3,200 people under 18 smoke their first cigarette, and approximately 2,100 youth and young adults become daily smokers.', '1');
INSERT INTO `isnap2changedb`.`fact` (`Content`, `TopicID`) VALUES ('Nearly 9 out of 10 lung cancers are caused by smoking. Smokers today are much more likely to develop lung cancer than smokers were in 1964.', '1');
INSERT INTO `isnap2changedb`.`fact` (`Content`, `TopicID`) VALUES ('A large part of the population is Omega-3 deficient. Avoiding a deficiency in these essential fatty acids can help prevent many diseases.', '2');
INSERT INTO `isnap2changedb`.`fact` (`Content`, `TopicID`) VALUES ('Trans Fats are chemically processed fats that cause all sorts of damage in the body. You should avoid them like the plague.', '2');
INSERT INTO `isnap2changedb`.`fact` (`Content`, `TopicID`) VALUES ('Excessive alcohol use is responsible for 2.5 million years of potential life lost annually, or an average of about 30 years of potential life lost for each death', '3');
INSERT INTO `isnap2changedb`.`fact` (`Content`, `TopicID`) VALUES ('Up to 40% of all hospital beds in the United States (except for those being used by maternity and intensive care patients) are being used to treat health conditions that are related to alcohol consumption', '3');
INSERT INTO `isnap2changedb`.`fact` (`Content`, `TopicID`) VALUES ('People aged 18-64 years old should exercice at least 150 min per week at least, each of the session lasting 10 min as a minimum,', '4');
INSERT INTO `isnap2changedb`.`fact` (`Content`, `TopicID`) VALUES ('Supportive environments and communities may help people to be more physically active.', '4');

# [Example] insert a poster task into Quiz
INSERT INTO `isnap2changedb`.`quiz` (`Week`, `QuizType`, `TopicID`) VALUES ('2', 'Poster', '3');

INSERT IGNORE INTO Quiz(Week,QuizType,TopicID) VALUES(1,'MCQ',5);
SET @QUIZ_LAST_INSERT_ID = LAST_INSERT_ID();
INSERT IGNORE INTO MCQ_Section(QuizID,Points,Questionnaires) VALUES(@QUIZ_LAST_INSERT_ID,20,0);
INSERT IGNORE INTO MCQ_Question(Question, CorrectChoice, QuizID) VALUES('0 option:', '0', @QUIZ_LAST_INSERT_ID);
SET @MCQ_QUESTION_LAST_INSERT_ID = LAST_INSERT_ID();

INSERT IGNORE INTO MCQ_Question(Question, CorrectChoice, QuizID) VALUES('1 option:', '1', @QUIZ_LAST_INSERT_ID);
SET @MCQ_QUESTION_LAST_INSERT_ID = LAST_INSERT_ID();
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('1', "Correct", @MCQ_QUESTION_LAST_INSERT_ID);

INSERT IGNORE INTO MCQ_Question(Question, CorrectChoice, QuizID) VALUES('2 option', '2', @QUIZ_LAST_INSERT_ID);
SET @MCQ_QUESTION_LAST_INSERT_ID = LAST_INSERT_ID();
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('1', "Wrong", @MCQ_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('2', "Correct", @MCQ_QUESTION_LAST_INSERT_ID);

INSERT IGNORE INTO MCQ_Question(Question, CorrectChoice, QuizID) VALUES('3 option', '3',@QUIZ_LAST_INSERT_ID);
SET @MCQ_QUESTION_LAST_INSERT_ID = LAST_INSERT_ID();
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('1', "Wrong", @MCQ_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('3', "Correct", @MCQ_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('2', "Wrong", @MCQ_QUESTION_LAST_INSERT_ID);

INSERT IGNORE INTO MCQ_Question(Question, CorrectChoice, QuizID) VALUES('4 option', '4', @QUIZ_LAST_INSERT_ID);
SET @MCQ_QUESTION_LAST_INSERT_ID = LAST_INSERT_ID();
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('1', "Wrong", @MCQ_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('2', "Wrong", @MCQ_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('3', "Wrong", @MCQ_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('4', "Correct", @MCQ_QUESTION_LAST_INSERT_ID);

INSERT IGNORE INTO MCQ_Question(Question, CorrectChoice, QuizID) VALUES('5 option', '5', @QUIZ_LAST_INSERT_ID);
SET @MCQ_QUESTION_LAST_INSERT_ID = LAST_INSERT_ID();
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('1', "Wrong", @MCQ_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('2', "Wrong", @MCQ_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('3', "Wrong", @MCQ_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('4', "Wrong", @MCQ_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('5', "Correct", @MCQ_QUESTION_LAST_INSERT_ID);

INSERT IGNORE INTO Learning_Material(Content,QuizID) VALUES('
<p>Learning materials for this quiz has not been added.</p>',9);

# [Example] insert a poster task into Poster_Section
INSERT INTO `isnap2changedb`.`poster_section` (`QuizID`, `Points`) VALUES ('9', '20');

# [Example] insert a learning material for a Poster task with QuizID = 9
INSERT INTO `isnap2changedb`.`learning_material` (`Content`, `QuizID`) VALUES ('<p>Learning materials for this quiz has not been added.</p>', '9');
/*
#TEST

#TEST 1[PASSED]: ON DELETE CASCADE
#DELETE FROM Student WHERE StudentID = 1;

#TEST 2[PASSED]: ON DELETE CASCADE IN MULTIPLE TABLE;
#DELETE FROM MCQ_Section WHERE QuizID = 1;

#TEST 3[PASSED]: INSERT MCQ_Section QUESTION
START TRANSACTION;
INSERT IGNORE INTO MCQ_Question(Question, CorrectChoice, QuizID) VALUES('this is a test question for insert new question', 'A', 1);
SET @MCQ_QUESTION_LAST_INSERT_ID = LAST_INSERT_ID();
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('answeroftest1', @MCQ_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('answeroftest2', @MCQ_QUESTION_LAST_INSERT_ID);
COMMIT;
#SELECT LAST_INSERT_ID();

#QUERY GET UNFINISHED QUIZZES
SELECT 
    QuizID, QuizType
FROM
    Quiz
WHERE
    Week = 1
        AND NOT EXISTS( SELECT 
            QuizID, QuizType
        FROM
            Quiz_Record
                NATURAL JOIN
            Quiz
        WHERE
            StudentID = 1 AND Week = 1);

SELECT 
    (SELECT 
            COUNT(*)
        FROM
            Quiz
        WHERE
            Week = 1) - COUNT(*)
FROM
    Quiz_Record
        NATURAL JOIN
    Quiz
WHERE
    StudentID = 1 AND Week = 1;
*/

