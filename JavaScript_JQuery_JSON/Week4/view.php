<?php
    session_start();
    require_once 'pdo.php';
    require_once "utils.php";

    $stmt = $pdo->prepare("SELECT * FROM Profile
                           WHERE profile_id = :prof AND user_id = :uid");

    $stmt->execute(array(
        ":prof" => $_REQUEST['profile_id'],
        ":uid" => $_SESSION['user_id']));

    $profile = $stmt->fetch(PDO::FETCH_ASSOC);
    $positions = loadPos($pdo, $_REQUEST['profile_id']);
    $educations = loadEdu($pdo, $_REQUEST['profile_id']);

    if ($profile === false || $positions === false || $educations === false) {
        $_SESSION['error'] = "Bad value for profile id";

        header('Locatio: index.php');

        return;
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title> Gabriel de Almeida Miki's View Page </title>
        <?php require_once "head.php"; ?>
    </head>

    <body>
        <h1> Profile Information </h1>

        <?php
            echo ('<p> First Name: ' . htmlentities($profile['first_name']) . ' </p>');
            echo ('<p> Last Name: ' . htmlentities($profile['last_name']) . ' </p>');
            echo ('<p> Headline: <br>' . htmlentities($profile['headline']) . ' </p>');
            echo ('<p> Summary: <br>' . htmlentities($profile['summary']) . ' </p>');

            echo ('<p> Position </p> <ul>');

            foreach ($positions as $position) {
                echo ('<li> ' . $position['year'] . ': ' . htmlentities($position['description'] . ' </li>'));
            }
            
            echo ('</ul>');

            echo ('<p> Education </p> <ul>');

            foreach ($educations as $education) {
                echo ('<li> ' . $education['year'] . ': ' . htmlentities($education['name'] . ' </li>'));
            }
            
            echo ('</ul>');
        ?>

        <a href = "index.php"> Done </a>
    </body>
</html>
