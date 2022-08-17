<?php
    session_start();
    require_once 'pdo.php';

    if (!isset($_SESSION['name'])) {
        die('ACESS DENIED');
    }

    if (isset($_POST['cancel'])) {
        header('Location: index.php');

        return;
    }

    if (isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary'])) {
        if (strlen($_POST["first_name"]) > 0 && strlen($_POST["last_name"]) > 0 && strlen($_POST["email"]) > 0 && strlen($_POST["headline"]) > 0 && strlen($_POST["summary"]) > 0) {
            if (strripos($_POST['email'], "@") !== false) {
                $sql = "UPDATE Profile SET first_name = :fn, last_name = :ln,
                        email = :em, headline = :he, summary = :su
                        WHERE profile_id = :id";

                $stmt = $pdo->prepare($sql);

                $stmt->execute(array(
                    ':fn' => $_POST['first_name'],  
                    ':ln' => $_POST['last_name'],
                    ':em' => $_POST['email'],
                    ':he' => $_POST['headline'],
                    ':su' => $_POST['summary'],
                    ':id' => $_POST['profile_id']));

                $_SESSION['success'] = "Record edited";

                header("Location: index.php");

                return;
            }
            else {
                $_SESSION['error'] = 'Email address must contain @';

                header('Location: edit.php');

                return;
            }
        }
        else {
            $_SESSION['error'] = 'All fields are required';

            header('Location: edit.php');

            return;
        }
    }

    $stmt = $pdo->prepare("SELECT * FROM Profile
                           WHERE profile_id = :id");

    $stmt->execute(array(":id" => $_GET['profile_id']));

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row === false) {
        $_SESSION['error'] = "Bad value for profile id";

        header('Locatio: index.php');

        return;
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title> Gabriel de Almeida Miki's Edit Page </title>
    </head>

    <body>
        <h1> Editing Profile for <?= $_SESSION['name'] ?> </h1>

        <form method = "post">
            <label for = "first_name"> First Name: </label>
            <input type = "text" name = "first_name" value = "<?= htmlentities($row['first_name']) ?>">
            <br>
            <label for = "last_name"> Last Name: </lable>
            <input type = "text" name = "last_name" value = "<?= htmlentities($row['last_name']) ?>">
            <br>
            <label for = "email"> Email: </lable>
            <input type = "text" name = "email" value = "<?= htmlentities($row['email']) ?>">
            <br>
            <label for = "headline"> Headline </lable>
            <input type = "text" name = "headline" value = "<?= htmlentities($row['headline']) ?>">
            <br>
            <label for = "summary"> Summary </label>
            <input type = "text" name = "summary" value = "<?= htmlentities($row['summary']) ?>">
            <br>
            <input type = "submit" value = "Save">
            <input type = "submit" name = "cancel" value = "Cancel">
            <input type = "hidden" name = "profile_id" value = "<?= $row['profile_id'] ?>">
        </form>
    </body>
</html>