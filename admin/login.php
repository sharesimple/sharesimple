<?php

    // Start the PHP_session
    session_start();

    // Variables
    $db_config = require('../config/database.php');

    // Get input
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Conect to database
    $con = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
    if (mysqli_connect_errno()) {
        exit("Database error");
    } else {

        // Check if account exists
        $stmt = $con->prepare("SELECT id FROM accounts WHERE username = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows == 0) {
            exit("Username or password invalid");
        } else {

        // Get id, salt and password hash from database
        if ($stmt = $con->prepare("SELECT id, password, is_admin FROM accounts WHERE username = ?")) {
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id, $password_hash, $is_admin);
            $stmt->fetch();

            // Check if password is right
            if (!password_verify($password, $password_hash)) {
                exit("Username or password invalid");
            } else {

                // Set session variables
                $_SESSION['id'] = $id;
                $_SESSION['username'] = $username;
                $_SESSION['is_admin'] = $is_admin;
                $_SESSION['loggedin'] = true;

                // Redirect to app
                header("Location: ./admin.php");
            }
            }
        }
    }
?>