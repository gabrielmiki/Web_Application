<?php
    session_start();
    require_once "pdo.php";

    if (isset($_POST['cancel'])) {
        header('Location: logout.php');

        return;
    }

    $salt = 'XyZzy12*_';

    if (isset($_POST['email']) && isset($_POST['pass'])) {
        if (strlen($_POST['email']) > 0 && strlen($_POST['pass']) > 0) {    
            if (strripos($_POST['email'], "@") !== false) {

                $check = hash('md5', $salt . $_POST['pass']);

                $stmt = $pdo->prepare('SELECT user_id, name FROM users
                                       WHERE email = :em AND password = :pw');

                $stmt->execute(array( ':em' => $_POST['email'], ':pw' => $check));

                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($row !== false) {
                    $_SESSION['name'] = $row['name'];
           
                    $_SESSION['user_id'] = $row['user_id'];

                    error_log("Login success " . $_POST['email']);
                      
                    header("Location: index.php");
           
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

        <form method = 'post'>
            <label for = "email"> Email </label>
            <input type = "text" name = "email" id = "email">
            <br>
            <lable for = "pass"> Password </label>
            <input type = "password" name = "pass" id = "pass">
            <br>
            <input type = "submit" onclick = "return doValidate();" value = "Log In">
            <input type = "submit" name = "cancel" value = "Cancel">
        </form>

        <script>
            function doValidate() {
                console.log('Validating...');

                try {
                    adrs = document.getElementById('email').value;
                    pw = document.getElementById('pass').value;

                    console.log('Vlidating pw = ' + pw + ' and adrs = ' + adrs);

                    if (adrs == null || adrs == "" || pw == null || pw == "") {
                        alert('Both fields must be filled out');

                        return false;
                    }

                    if (adrs.indexOf('@') == -1) {
                        alert('Invalid email address');

                        return false;
                    }

                    return true;
                }
                catch (e) {
                    return false;
                }

                return false;
            }
        </script>
    </body>
</html>