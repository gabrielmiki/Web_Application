<?php
    session_start();
    require_once "pdo.php";

    if (!isset($_SESSION['email'])) {
        die("Not logged in.");
    }

    if (isset($_POST['cancel']) ) {
        header('Location: view.php');

        return;
    }

    if ((isset($_POST['make'])) && (isset($_POST['year'])) && (isset($_POST['mileage']))) {
        if (is_numeric($_POST["year"]) && is_numeric($_POST["mileage"])) {
            if (strlen($_POST["make"]) > 0) {

                $sql = "INSERT INTO autos (make, year, mileage) 
                        VALUES ( :mk, :yr, :mi)";

                $stmt = $pdo->prepare($sql);

                $stmt->execute(array(
                    ':mk' => $_POST['make'],
                    ':yr' => $_POST['year'],
                    ':mi' => $_POST['mileage']));

                $_SESSION['success'] = "Record inserted";

                header("Location: view.php");

                return;
            }
            else {
                $_SESSION['failure'] = "Make is required";

                header("Location: add.php");

                return;
            }
        }
        else {
            $_SESSION['failure'] = "Mileage and year must be numeric";

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
            if (isset($_SESSION['failure'])) {
                echo('<p style = "color: red"> ' . htmlentities($_SESSION['failure']) . ' </p>');

                unset($_SESSION['failure']);
            }
        ?>

        <form method = "post">
            <label for = "make"> Make: </label>
            <input type = "text" name = "make">
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