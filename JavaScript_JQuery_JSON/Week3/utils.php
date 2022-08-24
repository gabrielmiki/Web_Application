<?php
    function dataCheck() {
        if (strlen($_POST["first_name"]) == 0 || strlen($_POST["last_name"]) == 0 ||
            strlen($_POST["email"]) == 0 || strlen($_POST["headline"]) == 0 || strlen($_POST["summary"]) == 0) {
            return 'All fields are required';
        }
        
        if (strripos($_POST['email'], "@") === false) {
            return 'Email adress must contain @';
        }
        
        return true;
    }

    function statusCheck() {
        if (isset($_SESSION['error'])) {
            echo('<p style="color: red;">' . htmlentities($_SESSION['error']) . "</p>\n");

            unset($_SESSION['error']);
        }

        if (isset($_SESSION['success'])) {
            echo('<p style="color: green;">' . htmlentities($_SESSION['success']) . "</p>\n");

            unset($_SESSION['success']);
        }
    }

    function acessPermission() {
        if (!isset($_SESSION['name'])) {
            die('ACESS DENIED');
        }
    }

    function validadePos() {
        for ($i = 1; $i <= 9; $i++){
            if (!isset($_POST['year' . $i])) continue;

            if (!isset($_POST['desc' . $i])) continue;

            $year = $_POST['year' . $i];
            $desc = $_POST['desc' . $i];
            
            if (strlen($year) == 0 || strlen($desc) == 0) {
                return "All fields are required";
            }

            return true;
        }        
    }

    function loadPos($pdo, $profile_id) {
        $stmt = $pdo->prepare('SELECT * FROM Position 
                               WHERE profile_id = :prof ORDER BY rank');

        $stmt->execute(array(':prof' => $profile_id));

        $positions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $positions;
    }