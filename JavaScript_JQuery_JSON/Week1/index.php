<?php
    session_start();
    require_once "pdo.php";

    $stmt = $pdo->query("SELECT * FROM Profile");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
    <head>
        <title> Gabriel de Almeida Miki's Resume Registry Page </title>
    </head>

    <body>
        <h1> Resume Registry </h1>

        <?php
            if (isset($_SESSION['error'])) {
                echo('<p style="color: red;">' . htmlentities($_SESSION['error']) . "</p>\n");

                unset($_SESSION['error']);
            }
            if (isset($_SESSION['success'])) {
                echo('<p style="color: green;">' . htmlentities($_SESSION['success']) . "</p>\n");

                unset($_SESSION['success']);
            }
        ?>

        <?php
            if (isset($_SESSION['name'])) {
                echo ('<p> <a href = "logout.php"> Logout </a> </p>');

                if (sizeof($rows) > 0) {
                    echo ('<table border = "1">');
                    echo ('<tr> <th> Name </th> <th> Headline </th> <th> Action </th> </tr>');

                    foreach ($rows as $row) {
                        echo ('<tr> <td> <a href = "view.php?profile_id=' . htmlentities($row['profile_id']) . '" > ');
                        echo (htmlentities($row['first_name'] . ' ' . $row['last_name']) . '</a> </td>');
                        echo ('<td> ' . htmlentities($row['headline']) . ' </td>');
                        echo ('<td> <a href = "edit.php?profile_id=' . htmlentities($row['profile_id']) . '" > Edit </a>');
                        echo ('<a href = "delete.php?profile_id=' . htmlentities($row['profile_id']) . '" > Delete </a> </td> </tr>');
                    }

                    echo ('</table>');
                }

                echo ('<p> <a href = "add.php"> Add New Entry </a> </p>');
            }
            else {
                echo ('<p> <a href = "login.php"> Please log in </a> </p>');

                if (sizeof($rows) > 0) {
                    echo ('<table border = "1">');
                    echo ('<tr> <th> Nme </th> <th> Headline </th> </tr>');

                    foreach ($rows as $row) {
                        echo ('<tr> <td> <a href = "view.php?profile_id=' . htmlentities($row['profile_id']) . '" > ');
                        echo (htmlentities($row['first_name'] . ' ' . $row['last_name']) . '</a> </td>');
                        echo ('<td> ' . htmlentities($row['headline']) . ' </td> </tr>');
                    }

                    echo ('</table>');
                }
            }
        ?>
    </body>
</html>