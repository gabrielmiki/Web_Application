<?php
    session_start();
    require_once "pdo.php";

    if (!isset($_SESSION['email'])) {
        die("ACCESS DENIED");
    }

    if (isset($_POST['cancel']) ) {
        header('Location: index.php');

        return;
    }

    if ((isset($_POST['make'])) && (isset($_POST['model'])) && (isset($_POST['year'])) && (isset($_POST['mileage']))) {
        if (strlen($_POST["make"]) > 0 && strlen($_POST["model"]) > 0 && strlen($_POST["year"]) > 0 && strlen($_POST["mileage"]) > 0) {
            if (is_numeric($_POST["year"]) && is_numeric($_POST["mileage"])) {

                $sql = "INSERT INTO autos (make, model, year, mileage) 
                        VALUES ( :mk, :md, :yr, :mi)";

                $stmt = $pdo->prepare($sql);

                $stmt->execute(array(
                    ':mk' => $_POST['make'],
                    ':md' => $_POST['model'],
                    ':yr' => $_POST['year'],
                    ':mi' => $_POST['mileage']));

                $_SESSION['success'] = "Record added";

                header("Location: index.php");

                return;
            }
            else {
                $_SESSION['error'] = "Mileage and year must be numeric";

                header("Location: add.php");

                return;
            }
        }
        else {            
            $_SESSION['error'] = "All fields are required";

            header("Location: add.php");

            return;
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title> Gabriel de Almeida Miki's Auto Database </title>
    </head>

    <body>
        <?php
            if (isset($_SESSION['email'])) {
                echo "<h1> Tracking Autos for: ";
                echo htmlentities($_SESSION['email']);
                echo "</h1>\n";
            }
        ?> 

        <?php
            if (isset($_SESSION['error'])) {
                echo('<p style = "color: red"> ' . htmlentities($_SESSION['error']) . ' </p>');

                unset($_SESSION['error']);
            }
        ?>

        <form method = "post">
            <label for = "make"> Make: </label>
            <input type = "text" name = "make">
            <br>
            <label for = "model"> Model: </label>
            <input type = "text" name = "model">
            <br> 
            <label for = "year"> Year: </label>
            <input type = "text" name = "year">
            <br>
            <label for = "mileage"> Mileage: </label>
            <input type = "text" name = "mileage">
            <br>
            <input type = "submit" value = "Add">
            <input type = "submit" name = "cancel" value = "Cancel">
        </form>
    </body>
</html>