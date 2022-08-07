<!DOCTYPE html>
<html> 
    <head>
        <title> Gabriel de Almeida Miki Guessing Game </title>
    </head>

    <body> 
        <h2> Guess Me! </h2>
        
        <?php
            if (!isset($_GET["guess"])) {
                echo "Missing guess parameter";
            }
            elseif (strlen($_GET["guess"]) < 1) {
                echo "Your guess is too short";
            }
            elseif (!is_numeric($_GET["guess"])) {
                echo "Your guess is not a number";
            }
            elseif ($_GET["guess"] < 45) {
                echo "Your guess is too low";
            }
            elseif ($_GET["guess"] > 45) {
                echo "Your guess is too high";
            }
            else {
                echo "Congratulations - You are right";
            }
        ?>
    </body>
</html>