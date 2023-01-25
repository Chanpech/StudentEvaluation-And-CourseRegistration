<style>
body  {
  background: rgb(198, 223, 247);
  color: black;
  margin: 0;
  padding: 0;
}
</style>
<?php
require "db.php";
session_start();
echo "<pre>";
/*debugging purpose I believe */
print_r($_SESSION);
print_r($_POST);
echo "</pre>";
// user clicked the login button */

if ( isset($_POST["login"]) ) {
 //check the username and passwd, if correct, redirect to main.php page
 if (i_authenticate($_POST["username"], $_POST["password"]) ==1) {
    $_SESSION["username"]=$_POST["username"];
    header("LOCATION:InstructorMain.php");
    return;
 }else {
    echo '<p style="color:red">incorrect username and password</p>';
 }
}
// user clicked the logout button */

?>
    <div style="background-image: url('https://wallpapercave.com/wp/E97OfW1.jpg');"> 
      <h2>Instructor Login </h2>
   </div>
<form method=post action=accountfunctions.php>
    Username: <input type="username" name="username"> <br>
     Password: <input type="password" name="password"> <br>
    <input type="submit" name="login" value="Login"> <br>
</form>
<form method=post action=accountoperations.php>
   <input type="submit" name="setInitPassword" value="Set Initial Password"><br>
    <input type="submit" name="change_Password" value="Change Password"><br>
</form>

<form action="selectMain.php" method="post">
    <input type="submit" name="back" value="Back">
    </form>
<?php