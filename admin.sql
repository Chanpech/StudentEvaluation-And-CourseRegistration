use choeng;

DELIMITER //

DROP procedure IF EXISTS createInstructor;
DROP procedure IF EXISTS createStudent;
DROP procedure IF EXISTS createCourse;
DROP procedure IF EXISTS assignInstructor;
DROP procedure IF EXISTS createMultipleChoice;
DROP procedure IF EXISTS createEssayQuestion;

create procedure createInstructor(i_name char(25), i_password varchar(20),i_account varchar(20) )
BEGIN
INSERT INTO Instructor values (i_name, i_password, i_account);
END//

create procedure createStudent(s_name char(25), s_password varchar(20),s_account varchar(20) )
BEGIN
INSERT INTO Student values (s_name, s_password, s_account);
END//

create procedure createCourse(id char(15), title varchar(20),credit float(4) )
BEGIN
INSERT INTO Course values (id, title, credit);
END//

create procedure assignInstructor(id char(15), i_name varchar(25))
BEGIN
INSERT INTO teaches values (id, i_name);
END//



#id, question_num, question, q_type
create procedure createMultipleChoice(question_num char(5), question varchar(255))
BEGIN
INSERT INTO Evaluation values (NULL, question_num, question, 0);
END//

create procedure createEssayQuestion(question_num char(5), question varchar(255))
BEGIN
INSERT INTO Evaluation values (NULL, question_num ,question, 1);
END//

DELIMITER ;

call createInstructor("Jon", NULL, NULL); 
call createInstructor("Lee", NULL, NULL); 

call createInstructor("", NULL, NULL); 

call createStudent("Mary", NULL, NULL);
call createStudent("Jordan", NULL, NULL);
call createStudent("Jame", NULL, NULL);
call createStudent("Pech", NULL, NULL);

call createStudent("Alice", NULL, NULL);

call createCourse("CS1000", NULL ,NULL);
call createCourse("CS3425", NULL ,NULL);

call createCourse("CS1011", NULL ,NULL);


call assignInstructor("CS1000", "Jon");
call assignInstructor("CS3425", "Jon");

call assignInstructor("CS1011", "Jon");
select * from teaches;

call createMultipleChoice("q1","Given the opportunity, I would take another course from this instructor.");
call createMultipleChoice("q2","The instructor engaged students by encouraging course preparation, reflection, or other activities outside of class.");
call createMultipleChoice("q3","The organization of the class helped me to learn.");
call createMultipleChoice("q4","The instructor encouraged students to seek additional help outside of class.");
call createMultipleChoice("q5","The instructional resources (textbooks, handouts, etc.) furthered my learning.");

call createEssayQuestion("q6","If you were meeting with another student about to start this class, what advice would you give him/her?");
call createEssayQuestion("q7","What aspects of this course should I change to improve student learning? Specifically, what would you suggest?");

call createEssayQuestion("q8", "How was the class overall?");

