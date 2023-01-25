<style>
table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
}
</style>

<?php
    require "db.php";
    session_start();
    echo "<pre>";
    print_r($_SESSION);
    print_r($_POST);
    echo "</pre>";
    
    $courses = get_teaches($_SESSION["username"]);
    ?>
        <form action="instructorevaluation.php" method="post">
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
        <form action="InstructorMain.php" method="post">
            <input type="submit" name="back" value="Back">
        </form>
    <?php
if(empty($_POST["courses"])){
    echo "Please select a course!";
    return ;
}
    if(isset($_POST["set"])){
        echo "Course is set";
        $_SESSION["setCourse"]= $_POST["courses"];
        if(checkEmpty($_SESSION["setCourse"]) == 0){
            echo "<p>No response is avaiable </p>";
            return;
        }
    }


    ?>
    <h1>Evaluation statistic</h1>
<?php

 $multipleChoice = 0;
 $questions = get_question($multipleChoice);
 
 $increment = 1;
 $id =  $_SESSION["setCourse"];

 $responses = array("Strongly Disagree", "Disagree", "Neutral", "Agree", "Strongly Agree");

 foreach($questions as $description){
     $qNum = $description[0];
     $frequency = 0;
     echo '<h3> Question '. $increment .'.) </h3>';
     echo '<p>'. $description[1].'</p>';
     ?> 

    <table>
        <tr>
        <th>Response Option</th>
        <th>Frequnecy</th>
        <th>Percentage</th>
        </tr>
    <?php
     foreach($responses as $r){
         $frequency = get_frequecy($id, $qNum, $r);
         $total= get_total($qNum, $id, $r);
         $percentage = $frequency[0] / $total[0];
         echo "<tr>";
         echo "<td>" . $r . "</td>";
         echo "<td>" . $frequency[0] . "</td>";
         echo "<td>" . $percentage. "</td>";
         echo "</tr>";
     }
     echo "</tr></table>";
     $rate= get_responseRate($qNum,$id);
     echo '<p>Response Rate: ' . $rate[0] . '</p>';
     $increment = $increment + 1;
   
 }

 $essayQuestion = 1;
 $questions = get_question($essayQuestion);
 foreach($questions as $description){
     $qNum = $description[0];
     echo '<h3> Question '. $increment .'.) </h3>';
     echo '<p style="font-size: 25px;">' . $description[1] . '</p>';
     echo '<p>All cumulative response: </p><br>';

     $essayResponse = get_essayresponse($_SESSION["setCourse"], $qNum);
     foreach($essayResponse as $r){
        echo '<p>'.$r[0].'</p>';
     } 
     $increment = $increment + 1;
 }
 echo '<br><br><center><input type="submit" name="finish" value="Submit"> </center>';
