<?php 

    // Get the database credentials from ../config/database.php
    require_once '../config/database.php';

    // Establish a connection to the server
    $conn = new mysqli($db_host, $db_user, $db_pass);

    // Check connection
    if ($conn->connect_error) {
        die("error: Connection failed: " . $conn->connect_error);
    }

    // Delete all entries in all tables of the database sharesimple´
    $sql = "DROP DATABASE IF EXISTS sharesimple";

    // Execute the query
    $result = $conn->multi_query($sql);

    // Remove all files in the folder ../files
    $files = glob('../files/*');
    foreach($files as $file){
        if(is_file($file)){
            unlink($file);
        }
    }

    // Remove all files in the folder ../config
    $files = glob('../config/*');
    foreach($files as $file){
        if(is_file($file)){
            unlink($file);
        }
    }

    // Redirect to index.html
    header("Location: ./");
?>