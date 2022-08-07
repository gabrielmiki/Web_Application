<?php
if (isset($_POST['cancel']) ) {
    header("Location: index.php");

    return;
}

$salt = 'XyZzy12*_';
$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';  // Pw is php123

$failure = false;

if (isset($_POST['who']) && isset($_POST['pass'])) {
    if (strlen($_POST['who']) > 0 && strlen($_POST['pass']) > 0) {    
        if (strripos($_POST['who'], "@") !== false) {

            $check = hash('md5', $salt . $_POST['pass']);

            if ($check == $stored_hash) {
                header("Location: auto.php?email=" . urlencode($_POST['who']));

                error_log("Login success " . $_POST['who']);

                return;
            } 
            else {
                $failure = "Incorrect password";

                error_log("Login fail " . $_POST['who'] . " $check");
            }
        }
        else {
            $failure = "E-mail must have an at-sign (@)"; 
        }
    }
    else {
        $failure = "Email and password are required";
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
            if ($failure !== false) {
                echo('<p style="color: red;">' . htmlentities($failure) . "</p>\n");
            }
        ?>

        <form method = "post"> 
            <label for = "who"> User Name </label>
            <input type = "text" name = "who" id = "who">
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