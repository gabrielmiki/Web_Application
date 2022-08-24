<?php
    session_start();
    require_once 'pdo.php';
    require_once "utils.php";

    acessPermission();

    if (isset($_POST['cancel'])) {
        header('Location: index.php');

        return;
    }

    if (!$_REQUEST['profile_id']) {
        $_SESSION['error'] = "Missing profile_id";

        header('Location: index.php');

        return;
    }

    if (isset($_POST['first_name']) && isset($_POST['last_name']) && 
        isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary'])) {
        $msg = dataCheck();

        if (is_string($msg)) {
            $_SESSION['error'] = $msg;

            header('Location: edit.php?profile_id=' . $_REQUEST['profile_id']);

            return;
        }

        $msg = validadePos();

        if (is_string($msg)) {
            $_SESSION['error'] = $msg;

            header('Location: edit.php?profile_id=' . $_REQUEST['profile_id']);

            return;
        }

        $sql = "UPDATE Profile SET first_name = :fn, last_name = :ln,
                email = :em, headline = :he, summary = :su
                WHERE profile_id = :prof AND user_id = :uid";

        $stmt = $pdo->prepare($sql);

        $stmt->execute(array(
            ':fn' => $_POST['first_name'],  
            ':ln' => $_POST['last_name'],
            ':em' => $_POST['email'],
            ':he' => $_POST['headline'],
            ':su' => $_POST['summary'],
            ':prof' => $_REQUEST['profile_id'],
            ':uid' => $_SESSION['user_id']));

        $sql = "DELETE FROM Position
                WHERE profile_id = :prof";
        
        $stmt = $pdo->prepare($sql);

        $stmt->execute(array(":prof" => $_REQUEST['profile_id']));

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
                ':pid' => $_REQUEST['profile_id'],
                ':rank' => $rank,
                ':year' => $year,
                ':desc' => $desc));

            $rank ++;
        }

        $_SESSION['success'] = "Record edited";

        header("Location: index.php");

        return;
    }

    $stmt = $pdo->prepare("SELECT * FROM Profile
                           WHERE profile_id = :prof AND user_id = :uid");

    $stmt->execute(array(
        ":prof" => $_REQUEST['profile_id'],
        ":uid" => $_SESSION['user_id']));

    $profile = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($profile === false) {
        $_SESSION['error'] = "Bad value for profile id";

        header('Locatio: index.php');

        return;
    }

    $positions = loadPos($pdo, $_REQUEST['profile_id']);
    var_dump($_REQUEST['profile_id']);
?>

<!DOCTYPE html>
<html>
    <head>
        <title> Gabriel de Almeida Miki's Edit Page </title>
        <?php require_once "head.php"; ?>
    </head>

    <body>
        <h1> Editing Profile for <?= $_SESSION['name'] ?> </h1>

        <form method = "post" action = "edit.php">
            <label for = "first_name"> First Name: </label>
            <input type = "text" name = "first_name" value = "<?= htmlentities($profile['first_name']) ?>">

            <br>

            <label for = "last_name"> Last Name: </lable>
            <input type = "text" name = "last_name" value = "<?= htmlentities($profile['last_name']) ?>">

            <br>

            <label for = "email"> Email: </lable>
            <input type = "text" name = "email" value = "<?= htmlentities($profile['email']) ?>">

            <br>

            <label for = "headline"> Headline </lable>
            <input type = "text" name = "headline" value = "<?= htmlentities($profile['headline']) ?>">

            <br>

            <label for = "summary"> Summary </label>
            <input type = "text" name = "summary" value = "<?= htmlentities($profile['summary']) ?>">

            <br>

            <?php
                $pos = 0;
                
                echo ('<p> Position: <input type = "submit" id = "addPos" value = "+">' . "\n");
                echo ('<div id = "positionContent">' . "\n");
                
                foreach ($positions as $position) {
                    $pos ++;

                    echo ('<div id = "positionContent' . $pos . '">' . "\n");
                    echo ('<p> Year: <input type = "text" name = "year' . $pos . '" value = "' . $position['year'] . '">' . "\n");
                    echo ('<input type = "button" value = "-" onclick = "$(\'#position' . $pos . '\').remove(); return false;">' . "\n");
                    echo ("</p>\n");
                    echo ('<textarea name = "desc' . $pos . '" rows = "8" cols = "80">' . "\n");
                    echo (htmlentities($position['description']) . "\n");
                    echo ("\n</textarea> </div> \n");
                }

                echo ('</div> </p>');
            ?>

            <input type = "submit" value = "Save">
            <input type = "submit" name = "cancel" value = "Cancel">
            <input type = "hidden" name = "profile_id" value = "<?= $_GET['profile_id'] ?>">
        </form>

        <script>
            countPos = <?= $pos ?>;

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
                        <textarea name = "desc' + countPos + '" rows = "8" cols = "80"> </textarea> \
                        </div>' 
                    );
                });
            });
        </script>
    </body>
</html>