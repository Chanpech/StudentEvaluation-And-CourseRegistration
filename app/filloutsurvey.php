<style>
table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
}

</style>
<?php
require "db.php";

session_start();

print_r($_SESSION);
print_r($_POST);
?> 
    </form>
    <form action="studentMain.php" method="post">
        <input type="submit" name="back" value="Back">
    </form>
    <?php

$courses = get_registered($_SESSION["username"]);
?>
    <form action="filloutsurvey.php" method="post">
        <b class="thick"> Courses </b> <br>
        <select name="courses" id="courses" multiple>>
    <?php
    foreach($courses as $row){
        echo '<option value="'. $row[0].'">' . $row[0]. "</option>";
    }
    echo "</select>";

    ?>
        <br><br>
        <input type="submit" name="set" value="Set">
    </form>
<?php
    if(isset($_POST["finish"])){
        ?>
        <h2> Finished! </h2>
        <?php
        try{
            $dbh = connectDB();
            $dbh->beginTransaction();
            unset($_POST["finish"]);
            //question_num, question, q_type 

            foreach(array_keys($_POST) as $Q){
                $answer = $_POST[$Q];
                if(empty($Q)){
                    echo "Question was empty";
                    $dbh->rollBack();
                    $dbh=null;
                    return;
                }
                $statement = $dbh->prepare("UPDATE registers SET status = 1 WHERE s_name =:username AND id=:setId ");
                $statement->bindParam(":username", $_SESSION["username"]);
                $statement->bindParam(":setId", $_SESSION["setCourse"]);
                $result = $statement->execute();

                $statement = $dbh->prepare("INSERT INTO responses values (:setCourse, 1, :username,
                :questionNumber, :qresponse, CURRENT_TIMESTAMP )");
                $statement->bindParam(":setCourse", $_SESSION["setCourse"]);
                $statement->bindParam(":username", $_SESSION['username']);
                $statement->bindParam(":questionNumber", $Q);
                $statement->bindParam(":qresponse", $answer);
                
                $result = $statement->execute();
                echo "Successfuly completed the evaluation!";
            }
            $dbh->commit();
        } catch (PDOException $e) {
            echo "Failed" .$e->getMessage();
            $dbh->rollBack();
        }
        $dbh=null;
    }

if(empty($_POST["courses"])){
    echo "Please select a course!";
    return ;
}
if(isset($_POST["set"])){
    $_SESSION["setCourse"]= $_POST["courses"];
    echo "<b>Course is set to" .$_SESSION["setCourse"]."<b> <br>";
    $completion = checkCompletion($_SESSION["setCourse"], $_SESSION["username"]);
    if($completion[0] > 0){ 
        echo "Survey already completed.";
        return;
    }
}
?>
    <h2> Fall 2021 Main Evaluation Group </h2>
    <h3> Course Name: </h3>
    <?php
    echo $_SESSION["setCourse"];
    ?>

    <form action="filloutsurvey.php" method="post">
    <?php
    


    $multipleChoice = 0;
    $questions = get_question($multipleChoice);
    foreach($questions as $description){
        echo '<p style="font-size: 25px;">' . $description[1] . '</p>';
        echo '<input type="radio" id="SD" name="'. $description[0].'" value="Strongly Disagree">Strongly Disagree';
        echo '<input type="radio" id="D" name="'. $description[0].'" value="Disagree">Disagree';
        echo '<input type="radio" id="N" name="'. $description[0].'" value="Neutral">Neutral';
        echo '<input type="radio" id="A" name="'. $description[0] .'" value="Agree">Agree';
        echo '<input type="radio" id="SA" name="'. $description[0] .'" value="Strongly Agree">Strongly Agree';
    }
   
    $essayQuestion = 1;
    $questions = get_question($essayQuestion);
    foreach($questions as $description){
        echo '<p style="font-size: 25px;">' . $description[1] . '</p>';
        echo '<textarea id="'. $description[0] .'" name="'. $description[0] .'" rows="10" cols="100">
        </textarea>';
    }
    echo '<br><br><center><input type="submit" name="finish" value="Submit"> </center>';

    

