<?php
    session_start();

    if (isset($_POST['cancel']) ) {
        header("Location: index.php");

        return;
    }

    $salt = 'XyZzy12*_';
    $stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';  // Pw is php123

    if (isset($_POST['email']) && isset($_POST['pass'])) {
        if (strlen($_POST['email']) > 0 && strlen($_POST['pass']) > 0) {    
            if (strripos($_POST['email'], "@") !== false) {

                $check = hash('md5', $salt . $_POST['pass']);

                if ($check == $stored_hash) {
                    $_SESSION['email'] = $_POST['email'];

                    header("Location: view.php");

                    error_log("Login success " . $_POST['email']);

                    return;
                } 
                else {
                    $_SESSION["error"] = "Incorrect password";

                    error_log("Login fail " . $_POST['email'] . " $check");

                    header("Location: login.php");

                    return;
                }
            }
            else {
                $_SESSION["error"] = "E-mail must have an at-sign (@)"; 

                header("Location: login.php");

                return;
            }
        }
        else {
            $_SESSION["error"] = "Email and password are required";

            header("Location: login.php");

            return;
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title> Gabriel de Almeida Miki's Login Page </title>
    </head>
        
    <body>
        <h1> Please Log In </h1>

        <?php
            if (isset($_SESSION['error'])) {
                echo('<p style="color: red;">' . htmlentities($_SESSION['error']) . "</p>\n");

                unset($_SESSION['error']);
            }
        ?>

        <form method = "post"> 
            <label for = "email"> User Name </label>
            <input type = "text" name = "email" id = "email">
            <br>
            <label for = "pass"> Password </label>
            <input type = "text" name = "pass" id = "pass">
            <br>
            <input type = "submit" value = "Log In">
            <input type = "submit" name = "cancel" value = "Cancel">
        </form>

        <p> For a password hint, view source and find a password hint in the HTML comments. </p>
        <!-- Hint: The password is the three character name of the programming language used in this class (all lowercase) 
        followed by 123. -->
    </body>
</html>