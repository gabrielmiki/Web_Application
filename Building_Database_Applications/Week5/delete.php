<?php
    session_start();
    require_once "pdo.php";

    if (isset($_POST['cancel'])) {
        header('Location: index.php');

        return;
    }

   if (isset($_POST['delete']) && isset($_POST['autos_id'])){
        $sql = "DELETE FROM autos WHERE autos_id = :id";

        $stmt = $pdo->prepare($sql);

        $stmt->execute(array(':id' => $_POST['autos_id']));

        $_SESSION['success'] = "Record deleted";

        header('Location: index.php');

        return;
   }

    $stmt = $pdo->prepare("SELECT * FROM autos
    WHERE autos_id = :id");

    $stmt->execute(array(":id" => $_GET['autos_id']));

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row === false) {
        $_SESSION['error'] = "Bad value for auto id";

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
        <p> Confirm: Deleting <?= htmlentities($row['make']) ?> </p>

        <form method = "post">
            <input type = 'hidden' name = 'autos_id' value = "<?= htmlentities($row['autos_id']) ?>">
            <input type = 'submit' name = "delete" value = 'Delete'>
            <a href = 'index.php'> Cancel </a> 
        </form>
    </body>
</html>