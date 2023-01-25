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

if(!isset($_SESSION['username'])){
    header("LOCATION:accountfunctions.php");
}

    ?>
    <h1> Student List <h1>
        <h2> Here you can view all of students in each of your respected course. </h2>
        <h3> First select a course</h3>

    <form action="InstructorMain.php" method="post">
        <input type="submit" name="back" value="Back">
    </form>
    <?php

    //print_r($accounts);
    $courses = get_teaches($_SESSION["username"]);
    ?>
        <form action="instructoroperations.php" method="post">
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

    if(empty($_POST["courses"])){
        echo "Please select a course!";
        return ;
    }
    if(isset($_POST["set"])){
        echo "Course is set";
        $_SESSION["setCourse"]= $_POST["courses"];
    }else{
        echo "Please select a course!";
    }

     $students = get_students($_SESSION["username"], $_SESSION["setCourse"]);
      ?> 

      <table>
          <tr>
          <th>Courses</th>
          <th>List of student</th>
          </tr>
      <?php
      foreach($students as $row) {
          echo "<tr>";
          echo "<td>" . $row[0] . "</td>";
          echo "<td>" . $row[1] . "</td>";
          echo "</tr>";
      }
      echo "<table>";



?>