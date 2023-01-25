<?php
function connectDB()
{
    $config = parse_ini_file("db.ini");
    $dbh = new PDO($config['dsn'], $config['username'], $config['password']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbh;
}
//return number of rows matching the given user and passwd.
function i_authenticate($user, $passwd) {
    try {
        $dbh = connectDB();
        $statement = $dbh->prepare("SELECT count(*) FROM Instructor ".
        "where i_name =:username and i_password = sha2(:passwd,256) ");
        $statement->bindParam(":username", $user);
        $statement->bindParam(":passwd", $passwd);
        $result = $statement->execute();
        $row=$statement->fetch();
        $dbh=null;
        return $row[0];
        }catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}
function s_authenticate($user, $passwd) {
    try {
        $dbh = connectDB();
        $statement = $dbh->prepare("SELECT count(*) FROM Student ".
        "where s_name =:username and s_password = sha2(:passwd,256) ");
        $statement->bindParam(":username", $user);
        $statement->bindParam(":passwd", $passwd);
        $result = $statement->execute();
        $row=$statement->fetch();
        $dbh=null;
        return $row[0];
        }catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

function get_students($user, $id){
    //connect to database
    //retrieve
    try{
        $dbh = connectDB();
        $statement = $dbh->prepare("SELECT teaches.id As Course, registers.s_name 
        AS Students FROM registers Right JOIN teaches ON registers.id = teaches.id
        WHERE teaches.i_name =:username AND registers.id =:courseId");

        $statement->bindParam(":courseId", $id);
        $statement->bindParam(":username", $user);
        $result = $statement->execute();

        return $statement->fetchAll();
        $dbh = null;
    } catch (PODException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
};

function get_courses($user){
    //Get the id from the instructor by the take. 
    //Then use  teaches to find the instructor and course id
    try{
        $dbh = connectDB();
        $statement = $dbh->prepare("SELECT  id as course FROM Course");
        $statement->execute();

        return  $statement->fetchAll();
        $dbh = null;
    } catch (PODException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

function get_survey($user){
    //Get the id from the instructor by the take. 
    //Then use  teaches to find the instructor and course id
    try{
        $dbh = connectDB();
        $statement = $dbh->prepare('Select registers.id AS Course, if(responses.status=1,"Complete","Incomplete"), IFNULL(cDateTime, "N/A") AS DateAndTime 
        from responses RIGHT JOIN registers ON responses.id = registers.id AND responses.s_name = registers.s_name
        WHERE registers.s_name =:username
        GROUP BY registers.id, responses.status, responses.cDateTime ');
        $statement->bindParam(":username", $user);
        $statement->execute();

        return  $statement->fetchAll();
        $dbh = null;
    } catch (PODException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}
function get_registered($user){
    try{
        $dbh = connectDB();
        $statement = $dbh->prepare("SELECT id AS Courses, status as Evaluation 
            FROM registers WHERE s_name =:username ");
        
        $statement->bindParam(":username", $user);
        $result = $statement->execute();

        return $statement->fetchAll();
        $dbh = null;
    } catch (PODException $e){
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

function get_teaches($user){
    try{
        $dbh = connectDB();
        $statement = $dbh->prepare("SELECT id AS Courses
            FROM teaches WHERE i_name =:username ");
        
        $statement->bindParam(":username", $user);
        $result = $statement->execute();

        return $statement->fetchAll();
        $dbh = null;
    } catch (PODException $e){
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

function register_course($user, $register){
    try{
        $dbh = connectDB();
        $dbh->beginTransaction();

        $statement = $dbh->prepare("SELECT count(*)
            FROM registers WHERE s_name =:username AND id=:course");
        $statement->bindParam(":username",$user);
        $statement->bindParam(":course",$register);
        $result = $statement->execute();
        $row = $statement->fetch();
        if($row[0] == 1){
            echo "Course $register already exist.";
            $dbh->rollBack();
            $dbh=null;
            return;
        }

        $statement = $dbh->prepare("INSERT INTO registers 
            VALUES (:course,:username,0) ");
        $statement->bindParam(":course", $register);
        $statement->bindParam(":username", $user);
        $result = $statement->execute();

        echo "Sucessfully registered.";
        $dbh->commit();
    } catch (Exception $e){
        echo "Failed" .$e->getMessage();
        $dbh->rollBack();
    }
}

function remove_course($user, $register){
    try{
        $dbh = connectDB();
        $dbh->beginTransaction();

        $statement = $dbh->prepare("DELETE FROM registers 
        WHERE id =:course AND s_name =:username");
        $statement->bindParam(":course", $register);
        $statement->bindParam(":username", $user);
        $result = $statement->execute();

        echo "Course $register was removed.";
        $dbh->commit();
    } catch (Exception $e){
        echo "Failed" .$e->getMessage();
        $dbh->rollBack();
    }
}
function i_setNewPassword($user, $oldPass, $newPass){
    //Get the id from the instructor by the take. 
    //Then use  teaches to find the instructor and course id
    try{
        $db = connectDB();
        $db->beginTransaction();
        if($oldPass == NULL){
            echo "NULL IS USED\n";
            $statement = $db->prepare("SELECT count(*) FROM Instructor ".
            "WHERE i_name =:username AND ISNULL(i_password)");
        }else{
            echo "ELSE IS USED\n";
            $statement = $db->prepare("SELECT count(*) FROM Instructor ".
            "WHERE i_name = :username and i_password = sha2(:passwd,256) ");
            $statement->bindParam(":passwd", $oldPass);
        }
        $statement->bindParam(":username", $user);
        $result = $statement->execute();
        $row = $statement->fetch();
        //Should check row but testing:
        if($row){
            $currentPassword = $row[0];
            if($currentPassword != 1){
                echo "Incorrect informations";
                $db->rollBack();
                $db=null;
                return;
            }
        }
        $statement = $db->prepare("update Instructor set i_password = sha2(:newpassword,256)" .
            "where i_name=:user");
        $statement->bindParam(":newpassword", $newPass);
        $statement->bindParam(":user", $user);
        $result = $statement->execute();

        echo "Password successfully changed! ";
        $db->commit();
    }catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        $db->rollBack();
        die();
    }
}


function s_setNewPassword($user, $oldPass, $newPass){
    //Get the id from the instructor by the take. 
    //Then use  teaches to find the instructor and course id
    try{
        $db = connectDB();
        $db->beginTransaction();
        if($oldPass == NULL){
            echo "NULL IS USED\n";
            $statement = $db->prepare("SELECT count(*) FROM Student ".
            "WHERE s_name =:username AND ISNULL(s_password)");
        }else{
            echo "ELSE IS USED \n" . $newPass;
            $statement = $db->prepare("SELECT count(*) FROM Student ".
            "WHERE s_name =:username AND s_password = sha2(:passwd,256) ");
            $statement->bindParam(":passwd", $oldPass);
        }
        $statement->bindParam(":username", $user);
        $result = $statement->execute();
        $row = $statement->fetch();
        //Should check row but testing:
        if($row){
            $currentPassword = $row[0];
            if($currentPassword != 1){
                echo "Incorrect informations";
                $db->rollBack();
                $db=null;
                return;
            }
        }
        $statement = $db->prepare("Update Student set s_password = sha2(:newpassword,256) " .
            "where s_name=:user");
        $statement->bindParam(":newpassword", $newPass);
        $statement->bindParam(":user", $user);
        $result = $statement->execute();

        echo "Password successfully changed! ";
        $db->commit();
    }catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        $db->rollBack();
        die();
    }
}

function get_question($type){
    try{
        $dbh =connectDB();
        $statement =$dbh->prepare("SELECT question_num, question, q_type 
            FROM Evaluation WHERE q_type =:question_type ");
        
        $statement->bindParam(":question_type", $type);
        $statement->execute();

        return $statement->fetchAll();
        $dbh = null;
    } catch (PODException $e){
        echo "Errror!". $e->getMessage(). "<br/>";
        die();
    }
}

function get_frequecy($id, $qNum, $response){
    try{
        $dbh=connectDB();
        $statement = $dbh->prepare(' SELECT count(*) FROM (
        select responses.id, responses.status, responses.question_num, responses.response AS response
        FROM responses RIGHT JOIN registers ON registers.id = responses.id AND registers.s_name = responses.s_name
        WHERE responses.question_num =:qNum AND responses.id =:course_id AND response =:response) AS tableOverall ');

        $statement->bindParam(":qNum", $qNum);
        $statement->bindParam(":course_id", $id);
        $statement->bindParam(":response", $response);

        $result = $statement->execute();
        $row = $statement->fetchAll();
        $dbh = null;
        return $row[0];
    }  catch (PODException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}
function get_total($qNum, $id, $response){
    try{
        $dbh=connectDB();
        $statement = $dbh->prepare('SELECT SUM(case when r_status = 1 then 1 else 0 end) as Total_Response
                FROM (
                Select responses.id AS r_id, registers.status AS r_status, registers.s_name AS r_sName
                from responses
                RIGHT JOIN registers 
                ON responses.id = registers.id
                WHERE registers.id=:courseId AND responses.question_num =:qNum
            GROUP BY responses.id, responses.status, registers.s_name) AS table1 ');
        $statement->bindParam(":qNum", $qNum);
        $statement->bindParam(":courseId", $id);

        $result = $statement->execute();
        $row = $statement->fetchAll();
        $dbh = null;
        return $row[0];
    } catch (PODException $e){
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}
function get_responseRate($qNum,$id){
    try{
        $dbh=connectDB();
        $statement = $dbh->prepare('SELECT (SUM(case when r_status = 1 then 1 else 0 end)/count(*)) as Response_Rate
                FROM (
                Select responses.id AS r_id, registers.status AS r_status, registers.s_name AS r_sName
                from responses
                RIGHT JOIN registers 
                ON responses.id = registers.id
                WHERE registers.id=:courseId AND responses.question_num =:qNum
            GROUP BY responses.id, responses.status, registers.s_name) AS table1 ');
        $statement->bindParam(":qNum", $qNum);
        $statement->bindParam(":courseId", $id);

        $result = $statement->execute();
        $row = $statement->fetchAll();
        $dbh = null;
        return $row[0];
    }catch (PODException $e){
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

function get_essayresponse($id, $qNum){

    try{
        $dbh=connectDB();
        $statement = $dbh->prepare('SELECT responses.response 
            FROM Evaluation LEFT JOIN responses
            ON responses.question_num = Evaluation.question_num
            WHERE Evaluation.q_type = 1 AND responses.id =:courseId AND Evaluation.question_num =:qNum');
        $statement->bindParam(":courseId", $id);
        $statement->bindParam(":qNum", $qNum);
        $result = $statement->execute();

        return $statement->fetchAll();
        $dbh = null; 

    }catch (PODException $e){
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}
function get_answer($id ){

    try{
        $dbh=connectDB();
        $statement = $dbh->prepare('SELECT count(responses.response)
            FROM Evaluation LEFT JOIN responses
            ON responses.question_num = Evaluation.question_num
            WHERE Evaluation.q_type = 1 AND responses.id =:courseId');
        $statement->bindParam(":courseId", $id);
        $statement->bindParam(":qNum", $qNum);
        $result = $statement->execute();
        $row = $statement->fetchAll();
        
        return $row[0];
        $dbh = null;

    }catch (PODException $e){
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}
function checkCompletion($course, $user){
    try{
        $dbh=connectDB();
        $statement = $dbh->prepare('Select count(*) from responses 
        Where s_name =:username AND id =:courseId');
        $statement->bindParam(":courseId", $course);
        $statement->bindParam(":username", $user);

        $result = $statement->execute();
        $row = $statement->fetch();

        return $row[0];
        $dbh = null;

    }catch (PODException $e){
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}
function checkEmpty($course){
    try{
        $dbh=connectDB();
        $statement = $dbh->prepare('SELECT count(*) FROM responses WHERE id=:courseId');
        $statement->bindParam(":courseId", $course);

        $result = $statement->execute();
        $row = $statement->fetch();

        return $row[0];
        $dbh = null;

    }catch (PODException $e){
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}
?>