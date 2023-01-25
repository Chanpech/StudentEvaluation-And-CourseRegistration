<html>
<style>
body  {
  background: rgb(227, 240, 255);
  color: black;
  margin: 0;
  padding: 0;
}
</style>
    <?php
    session_start();
    echo "<pre>";
    print_r($_SESSION);
    print_r($_POST);
    echo "</pre>";
    ?>
    <!-- show the login button or logout here -->
    <form action="accountfunctions.php" method="post">
        <?php
        if (!isset($_SESSION["username"])) {
        ?>
        <input type="submit" value='login' name="login">
        <?php
        }else {
        echo "Welcome ". $_SESSION["username"];
        ?>
        <input type="submit" value='logout' name="logout">
        <?php
        }
        ?>
    </form>
        <!-- show the main menu here -->
    <P> Main page  </p>
    <form action="instructoroperations.php" method="post">
        <p> Here are all of the pages that you'll be able to access</p>
        <p> What would you like to do? Click one of the buttons </p>
        <input type="submit" value='StudentList' name="student_list">
    </form>
    <form action="instructorevaluation.php" method="post">
        <input type="submit" value='Evaluation' name="evaluation">
    </form>
</html>