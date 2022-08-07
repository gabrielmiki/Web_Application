<?php
require_once "pdo.php";

$failure = false;
$sucsses = false;

if (!isset($_GET['email']) || strlen($_GET['email']) < 1 ) {
    die('Name parameter missing');
}

if (isset($_POST['logout']) ) {
    header('Location: index.php');

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

            $sucsses = "Record inserted";
        }
        else {
            $failure = "Make is required";
        }
    }
    else {
        $failure = "Mileage and year must be numeric";
    }
}

$st = $pdo->query("SELECT make, year, mileage FROM autos");
$rows = $st->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
    <head>
        <title> Gabriel de Almeida Miki's Auto Database </title>
    </head>

    <body>
        <?php
            if (isset($_REQUEST['email'])) {
                echo "<h1> Tracking Autos for: ";
                echo htmlentities($_REQUEST['email']);
                echo "</h1>\n";
            }
        ?> 

        <?php
            if ($failure !== false) {
                echo('<p style = "color: red"> ' . htmlentities($failure) . ' </p>');
            }
            if ($sucsses !== false) {
                echo('<p style = "color: green"> ' . htmlentities($sucsses) . ' </p>');
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
            <input type = "submit" name = "logout" value = "Logout">
        </form>

        <h2> Automobiles </h2>

        <ul> 
        <?php
            foreach ( $rows as $row ) {
                echo "<li> " . htmlentities($row["make"]) . " " . htmlentities($row["year"]) . " \ " . htmlentities($row["mileage"]) . "</li>";
            }
        ?>
        </ul>
    </body>
</html>