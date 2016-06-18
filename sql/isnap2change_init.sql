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
DROP TABLE IF EXISTS `MCQ_Option`;
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
    QuizID MEDIUMINT,
    Content LONGTEXT,
    CONSTRAINT Learning_Material_QuizID_PK PRIMARY KEY (QuizID),
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
    CorrectChoice TEXT DEFAULT NULL,
    QuizID MEDIUMINT,
    CONSTRAINT MCQ_Question_MCQID_PK PRIMARY KEY (MCQID),
    CONSTRAINT MCQ_Question_QuizID_FK FOREIGN KEY (QuizID)
        REFERENCES MCQ_Section (QuizID)
        ON DELETE CASCADE ON UPDATE CASCADE
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS `MCQ_Option` (
    OptionID MEDIUMINT AUTO_INCREMENT,
    Content TEXT,    
    Explanation TEXT,
    MCQID MEDIUMINT,
    CONSTRAINT MCQ_Option_OptionID_PK PRIMARY KEY (OptionID),
    CONSTRAINT MCQ_Option_MCQID_FK FOREIGN KEY (MCQID)
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
    Description TEXT,
    Points MEDIUMINT,
    CONSTRAINT Matching_Section_QuizID_PK PRIMARY KEY (QuizID),
    CONSTRAINT Matching_Section_QuizID_FK FOREIGN KEY (QuizID)
        REFERENCES Quiz (QuizID)
        ON DELETE CASCADE ON UPDATE CASCADE
)  ENGINE=INNODB;

# Set A: terminology/category/bucket
CREATE TABLE IF NOT EXISTS `Matching_Question` (
    MatchingID MEDIUMINT AUTO_INCREMENT,
    Question TEXT NOT NULL,
    QuizID MEDIUMINT,
    CONSTRAINT Matching_Question_MatchingID_PK PRIMARY KEY (MatchingID),
    CONSTRAINT Matching_Question_QuizID_FK FOREIGN KEY (QuizID)
        REFERENCES Matching_Section (QuizID)
        ON DELETE CASCADE ON UPDATE CASCADE
)  ENGINE=INNODB;

# Set B: explanation/concept/item
CREATE TABLE IF NOT EXISTS `Matching_Option` (
    OptionID MEDIUMINT AUTO_INCREMENT,
    Content TEXT NOT NULL,
    MatchingID MEDIUMINT,
    CONSTRAINT Matching_Option_OptionID_PK PRIMARY KEY (OptionID),
    CONSTRAINT Matching_Option_MatchingID_FK FOREIGN KEY (MatchingID)
        REFERENCES Matching_Question (MatchingID)
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
INSERT IGNORE INTO Class(ClassName,SchoolID) VALUES('Sample Class 1A',1);
INSERT IGNORE INTO Class(ClassName,SchoolID) VALUES('Sample Class 1B',1);
INSERT IGNORE INTO Token(`Type`,TokenString,ClassID) VALUES('STUDENT','TOKENSTRING01',1);
INSERT IGNORE INTO Token(`Type`,TokenString,ClassID) VALUES('TEACHER','TOKENSTRING02',1);
INSERT IGNORE INTO Token(`Type`,TokenString,ClassID) VALUES('STUDENT','TOKENSTRING03',2);
INSERT IGNORE INTO Token(`Type`,TokenString,ClassID) VALUES('TEACHER','TOKENSTRING04',2);

INSERT IGNORE INTO Student(Username,`Password`,Email,FirstName,LastName,Gender,DOB,ClassID) VALUES('Fernando','d59324e4d5acb950c4022cd5df834cc3','fernado@gmail.com','Fernando','Trump','Male',"2003-10-20",1);
INSERT IGNORE INTO Student(Username,`Password`,Email,FirstName,LastName,Gender,DOB,ClassID) VALUES('Todd','d59324e4d5acb950c4022cd5df834cc3','toddyy@gmail.com','Todd','Webb','Male',"2003-11-20",1);
INSERT IGNORE INTO Student(Username,`Password`,Email,FirstName,LastName,Gender,DOB,ClassID) VALUES('Theresa','d59324e4d5acb950c4022cd5df834cc3','theresa03@gmail.com','Theresa','Rios','Female',"2003-12-20",1);
INSERT IGNORE INTO Student(Username,`Password`,Email,FirstName,LastName,Gender,DOB,ClassID) VALUES('Hai','d59324e4d5acb950c4022cd5df834cc3','isnap2demo@gmail.com','Hai','Lam','Male',"2003-10-22",1);
INSERT IGNORE INTO Student(Username,`Password`,Email,FirstName,LastName,Gender,DOB,ClassID) VALUES('Lee','d59324e4d5acb950c4022cd5df834cc3','isnap2demo@gmail.com','Lee','Malone','Male',"2003-10-24",1);
INSERT IGNORE INTO Student(Username,`Password`,Email,FirstName,LastName,Gender,DOB,ClassID) VALUES('Tim','d59324e4d5acb950c4022cd5df834cc3','isnap2demo@gmail.com','Tim','Mason','Male',"2003-10-25",1);
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

# [Formal] Games
INSERT IGNORE INTO Game(Description,Week,Points) VALUES('Fruit Ninja',1,10);
INSERT IGNORE INTO Game(Description,Week,Points) VALUES('Candy Crush',1,10);

