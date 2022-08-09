<?php
    session_start();
    require_once "pdo.php";

    if (!isset($_SESSION['email'])) {
        die("Not logged in.");
    }

    $st = $pdo->query("SELECT make, year, mileage FROM autos");
    $rows = $st->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<hmtl>
    <header>
        <title> Gabriel de Almeida Miki's View Page </title>
    </header>

    <body>
        <h1> Tracking Autos for <?php echo $_SESSION['email'] ?> </h1>

        <?php 
            if (isset($_SESSION['success'])) {
                echo('<p style = "color: green;"> ' . htmlentities($_SESSION['success']) . " </p>\n");

                unset($_SESSION['success']);
            }
        ?>

        <h2> Automobiles </h2>

        <ul> 
        <?php
            foreach ( $rows as $row ) {
                echo "<li> " . htmlentities($row["make"]) . " " . htmlentities($row["year"]) . " \ " . htmlentities($row["mileage"]) . "</li>";
            }
        ?>
        </ul>

        <p> <a href = "add.php"> Add New </a> | <a href = "logout.php"> Logout </a> </p>  
    </body>
</html>
