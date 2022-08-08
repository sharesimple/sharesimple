<?php 
    // Get db connection
    require("../../config/database.php");
    // Try to connect to DB
    $conn = mysqli_connect($db_host,$db_user,$db_pass);
    // Check connection
    if ($conn->connect_error) {
        exit("Fehlerhafte Zugangsdaten");
    }

    // Create db
    if (!$conn->query("CREATE DATABASE sharesimple CHARACTER SET utf8 COLLATE utf8_general_ci")) {
        exit($conn->error);
    }

    // Create table "accounts"
    $sql = "CREATE TABLE sharesimple.accounts (
        id int(11) NOT NULL AUTO_INCREMENT,
        username varchar(255) NOT NULL,
        password varchar(255) NOT NULL,
        is_admin tinyint(1) NOT NULL DEFAULT 0,
        PRIMARY KEY (id)
    )";
    if (!$conn->query($sql)) {
        exit($conn->error);
    }

    // Create table "files"
    $sql = "CREATE TABLE sharesimple.files (
        `id` text NOT NULL,
        `name` varchar(255) NOT NULL,
        `extension` varchar(32) NOT NULL,
        `created` date NOT NULL DEFAULT current_timestamp(),
        `password` varchar(255) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    if (!$conn->query($sql)) {
        exit($conn->error);
    }    

    // Redirect
    header("Location: ../step3.html")
?>