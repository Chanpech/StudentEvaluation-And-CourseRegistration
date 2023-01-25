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
    <h1> Register Course </h1>
    <h4> Select all of the course you would like to register for. </h4>
    <?php
    $courses = get_courses($_SESSION["username"]);
    print_r($courses);

    ?>
    <form action="course_register.php" method="post">
        <b class="thick"> Courses </b> <br>
        <select name="courses" id="courses" multiple>>
    <?php
    foreach($courses as $row){
        echo '<option value="'. $row[0].'">' . $row[0]. "</option>";
    }
    echo "</select>";

    ?>
        <br><br>
        <input type="submit" name="add" value="Register">
        <input type="submit" name="drop" value="Drop">
    </form>

    <b class="thick"> Current Registered Courses </b> <br>
    <table>
        <tr>
        <th>Course</th>
        </tr>
    <?php
    $current = get_registered($_SESSION["username"]);
    print_r($current);
    foreach($current as $row){
        echo "<tr>";
        echo "<td>" . $row[0] . "</td>";
        echo "</tr>";
    }
    echo "<table>";

    ?>
    <form action="studentMain.php" method="post">
    <input type="submit" name="back" value="Back">
    </form>
<?php

if(isset($_POST["add"])){
    if(empty($_POST["courses"])){
        echo "No course was selected!";
        return;
    }
    $user =$_SESSION["username"];
    $register = $_POST["courses"];
    register_course($user, $register);
}
if(isset($_POST["drop"])){
    if(empty($_POST["courses"])){
        echo "No course was selected!";
        return;
    }
    $user =$_SESSION["username"];
    $register = $_POST["courses"];
    remove_course($user, $register);
}
