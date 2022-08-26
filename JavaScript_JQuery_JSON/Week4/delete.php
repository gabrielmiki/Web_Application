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

    if (isset($_POST['delete']) && isset($_POST['profile_id'])){
        $sql = "DELETE FROM Profile WHERE profile_id = :id";

        $stmt = $pdo->prepare($sql);

        $stmt->execute(array(':id' => $_POST['profile_id']));

        $_SESSION['success'] = "Record deleted";

        header('Location: index.php');

        return;
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
        <title> Gabriel de Almeida Miki's View Page </title>
    </head>

    <body>
        <h1> Deletting Profile </h1>

        <p> First Name: <?= htmlentities($row['first_name']) ?> </p>

        <p> Last Name: <?= htmlentities($row['last_name']) ?> </p>

        <form method = "post">
            <input type = "submit" name = "delete" value = "Delete">
            <input type = "submit" name = "cancel" value = "Cancel">
            <input type = "hidden" name = "profile_id" value = "<?= $row['profile_id'] ?>">
        </form>
    </body>
</html>