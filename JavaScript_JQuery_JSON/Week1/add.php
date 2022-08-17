<?php
    session_start();
    require_once "pdo.php";

    if (!isset($_SESSION['name'])) {
        die('ACESS DENIED');
    }

    if (isset($_POST['cancel'])) {
        header('Location: logout.php');

        return;
    }

    if (isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary'])) {
        if (strlen($_POST["first_name"]) > 0 && strlen($_POST["last_name"]) > 0 && strlen($_POST["email"]) > 0 && strlen($_POST["headline"]) > 0 && strlen($_POST["summary"]) > 0) {
            if (strripos($_POST['email'], "@") !== false) {
                $stmt = $pdo->prepare('INSERT INTO Profile
                                       (user_id, first_name, last_name, email, headline, summary)
                                       VALUES ( :uid, :fn, :ln, :em, :he, :su)');

                $stmt->execute(array(
                    ':uid' => $_SESSION['user_id'],
                    ':fn' => $_POST['first_name'],
                    ':ln' => $_POST['last_name'],
                    ':em' => $_POST['email'],
                    ':he' => $_POST['headline'],
                    ':su' => $_POST['summary']));

                $_SESSION['success'] = 'Record added';

                header('Location: index.php');

                return;
            }
            else {
                $_SESSION['error'] = 'Email address must contain @';

                header('Location: add.php');

                return;
            }
        }
        else {
            $_SESSION['error'] = 'All fields are required';

            header('Location: add.php');

            return;
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title> Gabriel de Almeida Miki's Add Page </title>
    </head>

    <body>
        <h1> Adding Profile for UMSI </h1>

        <?php
            if (isset($_SESSION['error'])) {
                echo('<p style="color: red;">' . htmlentities($_SESSION['error']) . "</p>\n");

                unset($_SESSION['error']);
            }
        ?>

        <form method = "post"> 
            <label for = "first_name"> First Name </label>
            <input type = "text" name = "first_name" id = "first_name">
            <br>
            <label for = "last_name"> Last Name</label>
            <input type = "text" name = "last_name" id = "last_name">
            <br>
            <label for = "email"> Email </label>
            <input type = "text" name = "email" id = "email">
            <br>
            <label for = "headline"> Headline </label>
            <br>
            <input type = "text" name = "headline" id = "headline">
            <br>
            <label for = "summary"> Summary </label>
            <br>
            <textarea name = "summary" id = "summary" rows = "8" cols = "80"> </textarea>
            <br>
            <input type = "submit" value = "Add">
            <input type = "submit" name = "cancel" value = "Cancel">
        </form>
    </body>
</html>