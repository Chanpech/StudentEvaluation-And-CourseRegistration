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

?>
    <div style="background-image: url('https://wallpapercave.com/wp/E97OfW1.jpg');"> 
    <h1><font color = "#6600cc"> Final project</font></h1>
    <p><font size = "5" color = "#9966ff">Mini simulation of banweb</font></p>
    </div>
  <center>  
<h2>Welcome to student and teacher portal.</h2>
<h3>Are you a student or a teacher?</h3>
<form method="post" action="selectMain.php">
    <input type="radio" id="instructor" name="choice" value="Instructor">Instructor
    <input type="radio" id="student" name="choice" value="Student">Student
    <input type="submit" name="initiate" value="Go"> <br>
</form>
</center>
<?php
if(isset($_POST["initiate"])){
    if(strcmp($_POST["choice"], "Instructor") == 0 ){
      header("LOCATION:accountfunctions.php");
      return;
    }else{
      header("LOCATION:saccountfunctions.php");
      return;
    }
}
