/* /connect to database
    $description = "Question 1 ";
    $question_id = "Q1";
    foreach(){
        
    }
    echo '<P>' . $description . '</p>';
    echo '<input type="radio" id="SD" name="'. $question_id .'" value="Strongly Disagree">';
    echo '<P>' . $description . '</p>';
    echo '<input type="radio" id="D" name="'. $question_id .'" value="Disagree">';
    echo '<P>' . $description . '</p>';
    echo '<input type="radio" id="N" name="'. $question_id .'" value="Neutral">';
    echo '<P>' . $description . '</p>';
    echo '<input type="radio" id="A" name="'. $question_id .'" value="Agree">';
    echo '<P>' . $description . '</p>';
    echo '<input type="radio" id="SA" name="'. $question_id .'" value="Strongly Agree">';
    
    ?> 
    <form method="post" action=> 
        <input type="submit" name="Finish" >
    </form>
    <?php
    //On a different page this is what the above return
    $_POST["Eval"->"Finish"];
    

    //After processed these questions we do
    unset($_POST["eval"]);
    for each(array_keys($_POST); as $Q){
        $answer = $_POST[$Q]; //This will give you a question and an answer 
        //We'll take this and put it in update or select.

        //Use session variable to remember which user logged in.
    }

*/

<form method=POST action=accountoperations>
    <p>Question....</p>
    <input type="radio" id="SD" name="Q1" value="Strongly Disagree">
    <label for="SD"> Strongly Disagree </label>
    <input type="radio" id="D" name="Q1" value="Disagree">
    <lable for="D"> Disagree </lable>
    <input type="radio" id="N" name="Q1" value="Neutral">
    <label for="N"> Strongly Disagree </label>
    <input type="radio" id="D" name="Q1" value="Disagree">
    <lable for="D"> Disagree </lable>
    <input type="radio" id="D" name="Q1" value="Disagree">
    <lable for="D"> Disagree </lable>
</form>
<?php
