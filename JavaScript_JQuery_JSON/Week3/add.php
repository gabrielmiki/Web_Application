<?php
    session_start();
    require_once "pdo.php";
    require_once "utils.php";

    acessPermission();

    if (isset($_POST['cancel'])) {
        header('Location: index.php');

        return;
    }

    if (isset($_POST['first_name']) && isset($_POST['last_name']) && 
        isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary'])) {
        $msg = dataCheck();

        if (is_string($msg)) {
            $_SESSION['error'] = $msg;

            header('Location: add.php');

            return;
        }

        $msg = validadePos();

        if (is_string($msg)) {
            $_SESSION['error'] = $msg;

            header('Location: add.php');

            return;
        }

        $stmt = $pdo->prepare('INSERT INTO Profile
                                (user_id, first_name, last_name, email, headline, summary)
                                VALUES (:uid, :fn, :ln, :em, :he, :su)');

        $stmt->execute(array(
            ':uid' => $_SESSION['user_id'],
            ':fn' => $_POST['first_name'],
            ':ln' => $_POST['last_name'],
            ':em' => $_POST['email'],
            ':he' => $_POST['headline'],
            ':su' => $_POST['summary']));

        $profile_id = $pdo->lastInsertId();

        $rank = 1;

        for ($i = 1; $i <= 9; $i++) {
            if (!isset($_POST['year' . $i])) continue;
            if (!isset($_POST['desc' . $i])) continue;

            $year = $_POST['year' . $i];
            $desc = $_POST['desc' . $i];

            $stmt = $pdo->prepare('INSERT INTO Position
                                   (profile_id, rank, year, description)
                                   VALUES (:pid, :rank, :year, :desc)');

            $stmt->execute(array(
                ':pid' => $profile_id,
                ':rank' => $rank,
                ':year' => $year,
                ':desc' => $desc));

            $rank ++;
        }

        $_SESSION['success'] = 'Record added';

        header('Location: index.php');

        return;
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title> Gabriel de Almeida Miki's Add Page </title>
        <?php require_once "head.php"; ?>
    </head>

    <body>
        <h1> Adding Profile for UMSI </h1>

        <?php
            statusCheck();
        ?>

        <form method = "post"> 
            <label for = "first_name"> First Name </label>
            <input type = "text" name = "first_name" id = "first_name">

            <br>

            <label for = "last_name"> Last Name</label>
            <input type = "text" name = "last_name" id = "last_name">

            <br>

            <label for = "email"> Email </label>
            <input type = "text" name = "email" id = "email">

            <br>

            <label for = "headline"> Headline </label>
            <br>
            <input type = "text" name = "headline" id = "headline">

            <br>

            <label for = "summary"> Summary </label>
            <br>
            <textarea name = "summary" id = "summary" rows = "8" cols = "80"> </textarea>

            <br>

            <label for = "addPos"> Position: </label>
            <input type = "submit" id = "addPos" value = "+">

            <br>

            <div id = "positionContent"> </div>

            <br>

            <input type = "submit" value = "Add">
            <input type = "submit" name = "cancel" value = "Cancel">
        </form>

        <script>
            countPos = 0;

            $(document).ready(function() {
                window.console && console.log('Document ready called');

                $('#addPos').click(function(event) {
                    event.preventDefault();

                    if (countPos >= 9) {
                        alert('Maximum of nine position entries exceeded');

                        return;
                    }

                    countPos++;

                    window.console && console.log('Adding position ' + countPos);

                    $('#positionContent').append(
                        '<div id = "positionContent' + countPos + '"> \
                        <p> Year: <input type = "text" name = "year' + countPos + '" value = ""> \
                        <input type = "button" value = "-" \
                            onclick = "$(\'#positionContent' + countPos + '\').remove(); return false;"> </p> \
                        <textarea name = "desc' + countPos + '" rows = "8" cols = "80"> </textarea>\
                        </div>' 
                    );
                });
            });
        </script>
    </body>
</html>