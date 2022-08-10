<?php
    session_start();
    require_once "pdo.php";

    if (!isset($_SESSION['email'])) {
        die("ACCESS DENIED");
    }

    if (isset($_POST['cancel'])) {
        header('Location: index.php');

        return;
    }

    if ((isset($_POST['make'])) && (isset($_POST['model'])) && (isset($_POST['year'])) && (isset($_POST['mileage']))) {
        if (is_numeric($_POST["year"]) && is_numeric($_POST["mileage"])) {
            if (strlen($_POST["make"]) > 0 && strlen($_POST["model"]) > 0) {

                $sql = "UPDATE autos SET make = :mk, model = :md, 
                        year = :yr, mileage = :mi 
                        WHERE autos_id = :id";

                $stmt = $pdo->prepare($sql);

                $stmt->execute(array(
                    ':mk' => $_POST['make'],
                    ':md' => $_POST['model'],  
                    ':yr' => $_POST['year'],
                    ':mi' => $_POST['mileage'],
                    ':id' => $_POST['autos_id']));

                $_SESSION['success'] = "Record edited";

                header("Location: index.php");

                return;
            }
            else {
                $_SESSION['error'] = "All fields are required";

                header("Location: edit.php");

                return;
            }
        }
        else {
            $_SESSION['error'] = "Mileage and year must be numeric";

            header("Location: edit.php");

            return;
        }
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
        <h1> Editing Automobile </h1>

        <form method = "post">
            <label for = "make"> Make: </label>
            <input type = "text" name = "make" value = "<?= htmlentities($row['make']) ?>"> 
            <br>
            <label for = "model"> Model: </label>
            <input type = "text" name = "model" value = "<?= htmlentities($row['model']) ?>">
            <br> 
            <label for = "year"> Year: </label>
            <input type = "text" name = "year" value = "<?= htmlentities($row['year']) ?>">
            <br>
            <label for = "mileage"> Mileage: </label>
            <input type = "text" name = "mileage" value = "<?= htmlentities($row['mileage']) ?>">
            <br>
            <input type = 'hidden' name = 'autos_id' value = "<?= $row['autos_id'] ?>">
            <input type = "submit" value = "Save">
            <input type = "submit" name = "cancel" value = "Cancel">
        </form>
    </body>
</html>