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
    header("LOCATION:saccountfunctions.php");
}

if(isset($_POST["setInitPassword"])){
    ?>
    <header>Set Initial Password</header>
    <p> Enter your username and the initial password </p>
    <form method="post" action=studentoperations.php>
        Username: <input type="username" name="username"> <br>
        Password: <input type="password" name="initial_password"> <br>
        <input type="submit" name="setpassword" value="Set"> <br>
    </form>
    <form action="saccountfunctions.php" method="post">
        <input type="submit" name="back" value="Back">
    </form>
    <?php
}


if(isset($_POST["change_Password"])){
    ?>
    <header>Change Password</header>
    <p> Enter your username and the new password </p>
    <form method="post" action=studentoperations.php>
        Username: <input type="username" name="username"> <br>
        Old password: <input type="password" name="old_password"> <br>
        New password: <input type="password" name="new_password"> <br>
        <input type="submit" name="changePassd" value="Change"> <br>
    </form>

    <form action="saccountfunctions.php" method="post">
        <input type="submit" name="back" value="Back">
    </form>
    <?php
}


if(isset($_POST["changePassd"])){
    echo "WORKING Change";
    $user = $_POST["username"];
    $oldPass = $_POST["old_password"];
    $newPass = $_POST["new_password"];
    s_setNewPassword($user, $oldPass, $newPass);
    echo "<br><h3> Successfully Changed </h3>";
    ?>
    <form action="saccountfunctions.php" method="post">
        <input type="submit" name="back" value="Back">
    </form>
    <?php
}
if(isset($_POST["setpassword"])){
    echo "WORKING INTIAL";
    $user = $_POST["username"];
    $newPass = $_POST["initial_password"];
    s_setNewPassword($user, NULL, $newPass);
    ?>
    <form action="saccountfunctions.php" method="post">
        <input type="submit" name="back" value="Back">
    </form>
    <?php
}


if(isset($_POST["survey_status"])){
    ?>
    <h1> Survey status for all of your registered course. </h1>
    <table>
        <tr>
        <th>Course</th>
        <th>Evaluation Status</th>
        <th>Completion Date/Time</th>
        </tr>
    <?php
    $current = get_survey($_SESSION["username"]);
    print_r($current);
    foreach($current as $row){
        echo "<tr>";
        echo "<td>" . $row[0] . "</td>";
        echo "<td>" . $row[1] . "</td>";
        echo "<td>" . $row[2] . "</td>";
        echo "</tr>";
    }
    echo "<table>";    

    ?>
    <form action="studentMain.php" method="post">
        <input type="submit" name="back" value="Back">
    </form>
    <form action="filloutsurvey.php" method="post">
        <input type="submit" name="take_survey" value="Take survey">
    </form>
    
    <?php
}
if(isset($_POST["take_survey"])){
    ?> 
    <h2> Fall 2021 Main Evaluation Group </h2>
    <h3> Course Name: c_name</h3>
    <h4> Professor: i_name</h4>
    <form action="studentMain.php" method="post">
        <input type="submit" name="back" value="Back">
    </form>
    <?php

    $multipleChoice = 0;
    $questions = get_question($multipleChoice);
    foreach($questions as $description){
        echo '<p style="font-size: 25px;">' . $description[1] . '</p>';
        echo 'Strongly Disagree<input type="radio" id="SD" name="'. $description[0].'" value="Strongly Disagree">';
        echo 'Disagree<input type="radio" id="D" name="'. $description[0].'" value="Disagree">';
        echo 'Neutral<input type="radio" id="N" name="'. $description[0].'" value="Neutral">';
        echo 'Agree<input type="radio" id="A" name="'. $description[0] .'" value="Agree">';
        echo 'Strongly Agree<input type="radio" id="SA" name="'. $description[0] .'" value="Strongly Agree">';
    }
    echo '<br><br><center><input type="submit" name="finish" value="Submit"> </center>';

    ?> 

    <?php
}



?>