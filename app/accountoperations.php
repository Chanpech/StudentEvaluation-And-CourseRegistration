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
if(isset($_POST["setInitPassword"])){
    ?>
    <header>Set Initial Password</header>
    <p> Enter your username and the initial password </p>
    <form method="post" action=accountoperations.php>
        Username: <input type="username" name="username"> <br>
        Password: <input type="password" name="initial_password"> <br>
        <input type="submit" name="setpassword" value="Set"> <br>
    </form>
    <form action="accountfunctions.php" method="post">
        <input type="submit" name="back" value="Back">
    </form>
    <?php
}


if(isset($_POST["change_Password"])){
    ?>
    <header>Change Password</header>
    <p> Enter your username and the new password </p>
    <form method="post" action=accountoperations.php>
        Username: <input type="username" name="username"> <br>
        Old password: <input type="password" name="old_password"> <br>
        New password: <input type="password" name="new_password"> <br>
        <input type="submit" name="changePasswd" value="Change"> <br>
    </form>

    <form action="accountfunctions.php" method="post">
        <input type="submit" name="back" value="Back">
    </form>
    <?php
}


//This process the transfer when "Confirm" is clicked.
//
if(isset($_POST["changePasswd"])){
    $user = $_POST["username"];
    $oldPass = $_POST["old_password"];
    $newPass = $_POST["new_password"];
    i_setNewPassword($user, $oldPass, $newPass);
    ?>
    <form action="InstructorMain.php" method="post">
        <input type="submit" name="back" value="Back">
    </form>
    <?php
}
if(isset($_POST["initial_password"])){
    echo "WORKING INTIAL";
    $user = $_POST["username"];
    $newPass = $_POST["initial_password"];
    i_setNewPassword($user, NULL, $newPass);
    ?>
    <form action="InstructorMain.php" method="post">
        <input type="submit" name="back" value="Back">
    </form>
    <?php
}



?>