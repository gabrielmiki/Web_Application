<?php
    session_start();
    require_once 'pdo.php';

    $stmt = $pdo->prepare("SELECT * FROM Profile
                           WHERE profile_id = :id");

    $stmt->execute(array(":id" => $_GET['profile_id']));

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row === false) {
        $_SESSION['error'] = "Bad value for profile id";

        header('Location: index.php');

        return;
    }

?>

<!DOCTYPE html>
<html>
    <head>
        <title> Gabriel de Almeida Miki's View Page </title>
    </head>

    <body>
        <h1> Profile Information </h1>

        <?php
            echo ('<p> First Name: ' . htmlentities($row['first_name']) . ' </p>');
            echo ('<p> Last Name: ' . htmlentities($row['last_name']) . ' </p>');
            echo ('<p> Headline: <br>' . htmlentities($row['headline']) . ' </p>');
            echo ('<p> Summary: <br>' . htmlentities($row['summary']) . ' </p>');
        ?>

        <a href = "index.php"> Done </a>
    </body>
</html>
