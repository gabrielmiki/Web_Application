<?php
    session_start();
    require_once "pdo.php";

    if (!isset($_SESSION['email'])) {
        header('Location: start.php');
    }

    $st = $pdo->query("SELECT * FROM autos");
    $rows = $st->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
    <header>
        <title> Gabriel de Almeida Miki's Index Page</title>
    </header>

    <body>
        <h1> Welcome to Automobiles Database </h1>

        <?php 
            if (isset($_SESSION['success'])) {
                echo('<p style = "color: green;"> ' . htmlentities($_SESSION['success']) . " </p>\n");

                unset($_SESSION['success']);
            }

            if (isset($_SESSION['error'])) {
                echo('<p style="color: red;">' . htmlentities($_SESSION['error']) . "</p>\n");

                unset($_SESSION['error']);
            }
        ?>

        <table border = '1'>  
            <?php
                if (sizeof($rows) > 0) {
                    echo "<tr> <th> Make </th>";
                    echo "<th> Model </th>";
                    echo "<th> Year </th>";
                    echo "<th> Mileage </th>";
                    echo "<th> Action </th> </tr>";

                    foreach ( $rows as $row ) {
                        echo ("<tr> <td> " . htmlentities($row["make"]) . " </td>"); 
                        echo ("<td> " . htmlentities($row["model"]) . " </td>");
                        echo ("<td> " . htmlentities($row["year"]) . " </td>");
                        echo ("<td> " . htmlentities($row["mileage"]) . " </td>");
                        echo ('<td> <a href = "edit.php?autos_id=' . ($row["autos_id"]) . '"> Edit </a> / ');
                        echo ('<a href = "delete.php?autos_id=' . ($row["autos_id"]) . '"> Delete </a> </td> </tr>');
                    }
                }
                else {
                    echo ("<p> No rows found </p>");
                    var_dump($_SESSION);
                }
            ?>
        </table>

        <p> <a href = "add.php"> Add New Entry </a> </p>

        <p> <a href = "logout.php"> Logout </a> </p> 
    </body>
</html>