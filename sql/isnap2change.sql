#mysql -uroot -p.kHdGCD2Un%P
#mysql -uroot -p.kHdGCD2Un%P < isnap2change.sql
CREATE DATABASE IF NOT EXISTS isnap2changedb;
USE isnap2changedb;

SET FOREIGN_KEY_CHECKS=0;

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
DROP TABLE IF EXISTS `Game`;
DROP TABLE IF EXISTS `Game_Record`;
DROP TABLE IF EXISTS `Bonus`;
DROP TABLE IF EXISTS `Bonus_Record`;
DROP TABLE IF EXISTS `Bonus_Task`;
DROP TABLE IF EXISTS `Bonus_Task_Record`;

CREATE TABLE IF NOT EXISTS `School` (
    SchoolID MEDIUMINT AUTO_INCREMENT,
    SchoolName TEXT,
    CONSTRAINT School_SchoolID_PK PRIMARY KEY (SchoolID)
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS `Class` (
    ClassID MEDIUMINT AUTO_INCREMENT,
    ClassName TEXT,
    SchoolID MEDIUMINT NOT NULL,
    CONSTRAINT Class_ClassID_PK PRIMARY KEY (ClassID),
    CONSTRAINT Class_SchoolID_FK FOREIGN KEY (SchoolID)
        REFERENCES School (SchoolID)
        ON DELETE CASCADE ON UPDATE CASCADE
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS `Token` (
    TokenID MEDIUMINT AUTO_INCREMENT,
    `Type` TEXT NOT NULL,
    TokenString TEXT NOT NULL,
    ClassID MEDIUMINT NOT NULL,
    CONSTRAINT Token_TokenID_PK PRIMARY KEY (TokenID),
    CONSTRAINT Token_ClassID_FK FOREIGN KEY (ClassID)
        REFERENCES Class (ClassID)
        ON DELETE CASCADE ON UPDATE CASCADE
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS `Student` (
    StudentID MEDIUMINT AUTO_INCREMENT,
    Username TEXT NOT NULL,
    `Password` TEXT NOT NULL,
    FName TEXT,
    LName TEXT,
    Gender TEXT,
    DOB DATE,
    Score MEDIUMINT DEFAULT 0,
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
    FName TEXT,
    LName TEXT,
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
    FName TEXT,
    LName TEXT,
    CONSTRAINT Researcher_ResearcherID_PK PRIMARY KEY (ResearcherID)
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS `Fact` (
    FactID MEDIUMINT AUTO_INCREMENT,
    Content TEXT,
    CONSTRAINT Fact_FactID_PK PRIMARY KEY (FactID)
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS `Topic` (
    TopicID MEDIUMINT AUTO_INCREMENT,
    TopicName TEXT,
    CONSTRAINT Topic_TopicID_PK PRIMARY KEY (TopicID)
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS `Quiz` (
    QuizID MEDIUMINT AUTO_INCREMENT,
    Week TINYINT,
    QuizType ENUM('MCQ', 'SAQ'),    
    TopicID MEDIUMINT,
    CONSTRAINT Quiz_QuizID_PK PRIMARY KEY (QuizID),
    CONSTRAINT Quiz_TopicID_FK FOREIGN KEY (TopicID)
        REFERENCES Topic (TopicID)
        ON DELETE CASCADE ON UPDATE CASCADE
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS `Quiz_Record` (
    QuizID MEDIUMINT,
    StudentID MEDIUMINT,    
    Status ENUM('UNGRADED', 'GRADED'),
    Score MEDIUMINT DEFAULT 0,
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
    Points MEDIUMINT,
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
    QuizID MEDIUMINT AUTO_INCREMENT,
    Week TINYINT,
    CONSTRAINT SAQ_Section_QuizID_PK PRIMARY KEY (QuizID)
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS `SAQ_Question` (
    SAQID MEDIUMINT AUTO_INCREMENT,
    Question TEXT,
    Points INT,
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
    Grading INT,
    CONSTRAINT SAQ_Question_Record_PK PRIMARY KEY (StudentID , SAQID),
    CONSTRAINT SAQ_Question_Record_StudentID_FK FOREIGN KEY (StudentID)
        REFERENCES Student (StudentID)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT SAQ_Question_Record_SAQID_FK FOREIGN KEY (SAQID)
        REFERENCES SAQ_Question (SAQID)
        ON DELETE CASCADE ON UPDATE CASCADE
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS `Game` (
    GameID MEDIUMINT AUTO_INCREMENT,
    Description TEXT,
    Week TINYINT,
    Points MEDIUMINT,
    TopicID MEDIUMINT,
    CONSTRAINT Game_GameID_PK PRIMARY KEY (GameID),
    CONSTRAINT Game_TopicID_FK FOREIGN KEY (TopicID)
        REFERENCES Topic (TopicID)
        ON DELETE CASCADE ON UPDATE CASCADE
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS `Game_Record` (
    GameID MEDIUMINT,
    StudentID MEDIUMINT,
    `Level` TINYINT DEFAULT 0,
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
    Week TINYINT,
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
    Points INT,
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
    Grading INT,
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

# User Info
INSERT IGNORE INTO School(SchoolName) VALUES('Adelaide High School');
INSERT IGNORE INTO School(SchoolName) VALUES('Woodville High School');
INSERT IGNORE INTO Class(ClassName,SchoolID) VALUES('Adelaide High 1A',1);
INSERT IGNORE INTO Class(ClassName,SchoolID) VALUES('Adelaide High 1B',1);
INSERT IGNORE INTO Class(ClassName,SchoolID) VALUES('Adelaide High 1C',1);
INSERT IGNORE INTO Class(ClassName,SchoolID) VALUES('Adelaide High 2A',1);
INSERT IGNORE INTO Class(ClassName,SchoolID) VALUES('Adelaide High 2B',1);
INSERT IGNORE INTO Class(ClassName,SchoolID) VALUES('Adelaide High 2C',1);
INSERT IGNORE INTO Class(ClassName,SchoolID) VALUES('Woodville High 2C',1);
INSERT IGNORE INTO Token(`Type`,TokenString,ClassID) VALUES('STUDENT','TOKENSTRING01',1);
INSERT IGNORE INTO Token(`Type`,TokenString,ClassID) VALUES('TEACHER','TOKENSTRING02',1);
INSERT IGNORE INTO Token(`Type`,TokenString,ClassID) VALUES('STUDENT','TOKENSTRING03',2);
INSERT IGNORE INTO Token(`Type`,TokenString,ClassID) VALUES('TEACHER','TOKENSTRING04',2);
INSERT IGNORE INTO Student(Username,`Password`,FName,LName,Gender,DOB,ClassID) VALUES('Fernando','d59324e4d5acb950c4022cd5df834cc3','Fernando','Fernando','Male',"2003-10-20",1);
INSERT IGNORE INTO Student(Username,`Password`,FName,LName,Gender,DOB,ClassID,Score) VALUES('Todd','d59324e4d5acb950c4022cd5df834cc3','Todd','Webb','Male',"2003-11-20",1,55);
INSERT IGNORE INTO Student(Username,`Password`,FName,LName,Gender,DOB,ClassID,Score) VALUES('Theresa','d59324e4d5acb950c4022cd5df834cc3','Theresa','Rios','Female',"2003-12-20",1,90);
INSERT IGNORE INTO Student(Username,`Password`,FName,LName,Gender,DOB,ClassID,Score) VALUES('Hai','d59324e4d5acb950c4022cd5df834cc3','Hai','Lam','Male',"2003-10-22",1,30);
INSERT IGNORE INTO Student(Username,`Password`,FName,LName,Gender,DOB,ClassID,Score) VALUES('Lee','d59324e4d5acb950c4022cd5df834cc3','Lee','Malone','Male',"2003-10-24",1,45);
INSERT IGNORE INTO Student(Username,`Password`,FName,LName,Gender,DOB,ClassID,Score) VALUES('Tim','d59324e4d5acb950c4022cd5df834cc3','Tim','Mason','Male',"2003-10-25",1,60);
INSERT IGNORE INTO Student(Username,`Password`,FName,LName,Gender,DOB,ClassID) VALUES('Clinton','d59324e4d5acb950c4022cd5df834cc3','Clinton','Snyder','Male',"2003-10-28",1);
INSERT IGNORE INTO Student(Username,`Password`,FName,LName,Gender,DOB,ClassID) VALUES('Elbert','d59324e4d5acb950c4022cd5df834cc3','Elbert','Chapman','Male',"2003-10-22",1);
INSERT IGNORE INTO Student(Username,`Password`,FName,LName,Gender,DOB,ClassID) VALUES('Ervin','d59324e4d5acb950c4022cd5df834cc3','Ervin','Murray','Male',"2003-11-20",1);
INSERT IGNORE INTO Student(Username,`Password`,FName,LName,Gender,DOB,ClassID) VALUES('Sheila','d59324e4d5acb950c4022cd5df834cc3','Sheila','Frank','Female',"2003-10-20",1);
INSERT IGNORE INTO Student(Username,`Password`,FName,LName,Gender,DOB,ClassID) VALUES('Grace','d59324e4d5acb950c4022cd5df834cc3','Grace','Austin','Female',"2003-10-29",1);
INSERT IGNORE INTO Student(Username,`Password`,FName,LName,Gender,DOB,ClassID) VALUES('Ruby','d59324e4d5acb950c4022cd5df834cc3','Ruby','Chavez','Female',"2003-10-20",1);
INSERT IGNORE INTO Student(Username,`Password`,FName,LName,Gender,DOB,ClassID) VALUES('Sonya','d59324e4d5acb950c4022cd5df834cc3','Sonya','Kelly','Female',"2003-10-20",1);
INSERT IGNORE INTO Student(Username,`Password`,FName,LName,Gender,DOB,ClassID) VALUES('Donna','d59324e4d5acb950c4022cd5df834cc3','Donna','Pratt','Female',"2003-10-20",1);
INSERT IGNORE INTO Student(Username,`Password`,FName,LName,Gender,DOB,ClassID) VALUES('Stacy','d59324e4d5acb950c4022cd5df834cc3','Stacy','Figueroa','Female',"2003-10-20",1);
INSERT IGNORE INTO Student(Username,`Password`,FName,LName,Gender,DOB,ClassID) VALUES('Fannie','d59324e4d5acb950c4022cd5df834cc3','Fannie','Waters','Female',"2003-10-28",1);
INSERT IGNORE INTO Student(Username,`Password`,FName,LName,Gender,DOB,ClassID) VALUES('June','d59324e4d5acb950c4022cd5df834cc3','June','West','Female',"2003-10-20",1);
INSERT IGNORE INTO Student(Username,`Password`,FName,LName,Gender,DOB,ClassID) VALUES('Melinda','d59324e4d5acb950c4022cd5df834cc3','Melinda','Kelley','Female',"2003-10-20",1);
INSERT IGNORE INTO Student(Username,`Password`,FName,LName,Gender,DOB,ClassID) VALUES('Leo','d59324e4d5acb950c4022cd5df834cc3','Leo','Potter','Male',"2002-04-22",1);
INSERT IGNORE INTO Student(Username,`Password`,FName,LName,Gender,DOB,ClassID) VALUES('Hector','d59324e4d5acb950c4022cd5df834cc3','Hector','Byrd','Male',"2002-04-20",1);
INSERT IGNORE INTO Student(Username,`Password`,FName,LName,Gender,DOB,ClassID) VALUES('Otis','d59324e4d5acb950c4022cd5df834cc3','Otis','Lawrence','Male',"2002-04-20",2);
INSERT IGNORE INTO Student(Username,`Password`,FName,LName,Gender,DOB,ClassID) VALUES('Cassandra','d59324e4d5acb950c4022cd5df834cc3','Cassandra','James','Female',"2002-04-20",2);
INSERT IGNORE INTO Student(Username,`Password`,FName,LName,Gender,DOB,ClassID) VALUES('Marilyn','d59324e4d5acb950c4022cd5df834cc3','Marilyn','Ryan','Female',"2002-04-20",1);
INSERT IGNORE INTO Teacher(Username,`Password`,FName,LName,ClassID) VALUES('Lynette','d59324e4d5acb950c4022cd5df834cc3','Lynette','Coleman',1);
INSERT IGNORE INTO Teacher(Username,`Password`,FName,LName,ClassID) VALUES('Rachael','d59324e4d5acb950c4022cd5df834cc3','Rachael','Horton',2);
INSERT IGNORE INTO Researcher(Username,`Password`,FName,LName) VALUES('Ann','d59324e4d5acb950c4022cd5df834cc3','Ann','Gordon');
INSERT IGNORE INTO Researcher(Username,`Password`,FName,LName) VALUES('Patricia','d59324e4d5acb950c4022cd5df834cc3','Patricia','Hayes');

# Topic
INSERT IGNORE INTO Topic(TopicName) VALUES('Smoking');
INSERT IGNORE INTO Topic(TopicName) VALUES('Nutrition');
INSERT IGNORE INTO Topic(TopicName) VALUES('Alcohol');
INSERT IGNORE INTO Topic(TopicName) VALUES('Physical Activity');
INSERT IGNORE INTO Topic(TopicName) VALUES('Introduction');


# Example to insert MCQ section with multiple questions
INSERT IGNORE INTO Quiz(Week,QuizType,TopicID) VALUES(1,'MCQ',2);
SET @QUIZ_LAST_INSERT_ID = LAST_INSERT_ID();
INSERT IGNORE INTO MCQ_Section(QuizID,Points,Questionnaires) VALUES(@QUIZ_LAST_INSERT_ID,30,1);
INSERT IGNORE INTO MCQ_Question(Question, CorrectChoice, QuizID) VALUES('Which of these breakfast foods will provide you with the most energy?', 'Whole grain cereal or oatmeal', @QUIZ_LAST_INSERT_ID);
SET @MCQ_QUESTION_LAST_INSERT_ID = LAST_INSERT_ID();
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('Candy bar', 'Whole grains take your body longer to digest, giving you energy all morning!', @MCQ_QUESTION_LAST_INSERT_ID);
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

INSERT IGNORE INTO MCQ_Question(Question, CorrectChoice, QuizID) VALUES('Alcohol is broken down by:', 'Heart', @QUIZ_LAST_INSERT_ID);
SET @MCQ_QUESTION_LAST_INSERT_ID = LAST_INSERT_ID();
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('Blood', "Wrong", @MCQ_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('Heart', "Correct", @MCQ_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('Liver', "Wrong", @MCQ_QUESTION_LAST_INSERT_ID);
INSERT IGNORE INTO `Option`(Content, Explanation, MCQID) VALUES('Kidney', "Wrong", @MCQ_QUESTION_LAST_INSERT_ID);

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

# Example to insert SAQ section with multiple questions
INSERT IGNORE INTO Quiz(Week,QuizType,TopicID) VALUES(1,'SAQ',1);
SET @QUIZ_LAST_INSERT_ID = LAST_INSERT_ID();
INSERT IGNORE INTO SAQ_Section(QuizID) VALUES(@QUIZ_LAST_INSERT_ID);
INSERT IGNORE INTO SAQ_Question(Question, Points, QuizID) VALUES('Based on the video, list 3 problems or challenges that these teenagers face as a result of their smoking?', 10, @QUIZ_LAST_INSERT_ID);
INSERT IGNORE INTO SAQ_Question(Question, Points, QuizID) VALUES('List 1 strategy that you could use to help convince a peer to stop smoking?', 10, @QUIZ_LAST_INSERT_ID);
INSERT IGNORE INTO SAQ_Question(Question, Points, QuizID) VALUES('List 3 the different ways that you have seen anti-smoking messages presented to the public. With each suggest if you think they have been ‘effective’ or ‘not effective’. Eg. Poster-Effective.', 20, @QUIZ_LAST_INSERT_ID);

INSERT IGNORE INTO Quiz(Week,QuizType,TopicID) VALUES(3,'SAQ',4);
SET @QUIZ_LAST_INSERT_ID = LAST_INSERT_ID();
INSERT IGNORE INTO SAQ_Section(QuizID) VALUES(@QUIZ_LAST_INSERT_ID);
INSERT IGNORE INTO SAQ_Question(Question, Points, QuizID) VALUES('How much exercise do you think you do a day?', 10, @QUIZ_LAST_INSERT_ID);
INSERT IGNORE INTO SAQ_Question(Question, Points, QuizID) VALUES('Do you think that you are exercising enough? Why/whynot?', 10, @QUIZ_LAST_INSERT_ID);
INSERT IGNORE INTO SAQ_Question(Question, Points, QuizID) VALUES('What are the benefits of exercising? List 5 examples.', 20, @QUIZ_LAST_INSERT_ID);
INSERT IGNORE INTO SAQ_Question(Question, Points, QuizID) VALUES('What changes can you make to your daily routine to incorporate more exercise into your life?', 20, @QUIZ_LAST_INSERT_ID);

# Learning_Material
INSERT IGNORE INTO Learning_Material(Content,QuizID) VALUES('<p>Eating a balanced diet is vital for your health and wellbeing. The food we eat is responsible for providing us with the energy to do all the tasks of daily life. For optimum performance and growth a balance of protein, essential fats, vitamins and minerals are required. We need a wide variety of different foods to provide the right amounts of nutrients for good health. The different types of food and how much of it you should be aiming to eat is demonstrated on the pyramid below. (my own words)</p>
<p><img style="display: block; margin-left: auto; margin-right: auto;" src="https://cmudream.files.wordpress.com/2016/05/0.jpg" alt="" width="632" height="884" /></p>
<p>There are three main layers of the food pyramid. The bottom layer is the most important one for your daily intake of food. It contains vegetables, fruits, grains and legumes. You should be having most of your daily food from this layer. These foods are all derived or grow on plants and contain important nutrients such as vitamins, minerals and antioxidants. They are also responsible for being the main contributor of carbohydrates and fibre to our diet.<br />The middle layer is comprised of dairy based products such as milk, yoghurt, cheese. These are essential to providing our bodies with calcium and protein and important vitamins and minerals.<br />They layer also contains lean meat, poultry, fish, eggs, nuts, seeds, legumes. These foods are our main source of protein and are also responsible for providing other nutrients to us including iodine, iron, zinc, B12 vitamins and healthy fats.<br />The top layer, which is the smallest layer, is the layer you should me eating the least off. This layer is made up of food which has unsaturated fats such as sugar, butter, margarine and oils; small amounts of these unsaturated fats are needed for healthy brain and hear function.<br />(my own words)<br />Source: The Healthy Living Pyramid. Nutrition Australia. [Accessed 28/04/2016 http://www.nutritionaustralia.org/national/resource/healthy-living-pyramid]</p>',1);
INSERT IGNORE INTO Learning_Material(Content,QuizID) VALUES('https://www.youtube.com/watch?v=1ey0EDVjyeY&index=89&list=PLIGEVr8ox1oGsi-XcwSjudMi_uCPxGzSs',2);
INSERT IGNORE INTO Learning_Material(Content,QuizID) VALUES('
<p>Learning materials for this quiz has not been added.',4);


INSERT IGNORE INTO Game(Description,Week,Points,TopicID) VALUES('Fruit Ninja',1,10,5);
INSERT IGNORE INTO Game(Description,Week,Points,TopicID) VALUES('Candy Crush',1,10,5);

INSERT IGNORE INTO Game_Record(GameID,StudentID,`Level`,Score) VALUES(1,2,1,30);
INSERT IGNORE INTO Game_Record(GameID,StudentID,`Level`,Score) VALUES(1,3,1,30);
INSERT IGNORE INTO Game_Record(GameID,StudentID,`Level`,Score) VALUES(1,4,1,30);
INSERT IGNORE INTO Game_Record(GameID,StudentID,`Level`,Score) VALUES(2,2,1,35);
INSERT IGNORE INTO Game_Record(GameID,StudentID,`Level`,Score) VALUES(2,3,1,30);
INSERT IGNORE INTO Game_Record(GameID,StudentID,`Level`,Score) VALUES(2,4,1,30);
INSERT IGNORE INTO Game_Record(GameID,StudentID,`Level`,Score) VALUES(2,5,1,40);

# Example to add Bonus and tasks
INSERT IGNORE INTO Bonus(Week) VALUES(1);
SET @BONUS_LAST_INSERT_ID = LAST_INSERT_ID();
INSERT IGNORE INTO Bonus_Task(Question, Points, BonusID) VALUES('Prepare a meal for your mom.', 10, @BONUS_LAST_INSERT_ID);

INSERT IGNORE INTO Bonus(Week) VALUES(2);
SET @BONUS_LAST_INSERT_ID = LAST_INSERT_ID();
INSERT IGNORE INTO Bonus_Task(Question, Points, BonusID) VALUES('Attend a basketball game.', 10, @BONUS_LAST_INSERT_ID);

INSERT IGNORE INTO Bonus(Week) VALUES(3);
SET @BONUS_LAST_INSERT_ID = LAST_INSERT_ID();
INSERT IGNORE INTO Bonus_Task(Question, Points, BonusID) VALUES('Attend a football game', 10, @BONUS_LAST_INSERT_ID);


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

