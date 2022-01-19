<?php 
    // Get the database credentials from ../config/database.php 
    require_once '../config/database.php';

    // Establish a connection to the server
    $conn = new mysqli($db_host, $db_user, $db_pass);

    // Check connection
    if ($conn->connect_error) {
        die("error: Connection failed: " . $conn->connect_error);
    }

    // Delete the database "sharesimple"
    $sql = "DROP DATABASE IF EXISTS sharesimple";

    // Close the connection
    $conn->close();

    // Delete all files in the folder ../app and ../config
    $files = glob('../app/*');
    foreach($files as $file){
        if(is_file($file))
            unlink($file);
    }
    $files = glob('../config/*');
    foreach($files as $file){
        if(is_file($file))
            unlink($file);
    }

    // Delete the file ../.htaccess

    // Delete all files in the folder ../setup but not ../setup/uninstall.php
    $files = glob('../setup/*');
    foreach($files as $file){
        if(is_file($file) && $file != '../setup/uninstall.php'){
            unlink($file);
        }
    }

    // Delete all files and subfolders in ../
    $files = glob('../*', GLOB_MARK);
    foreach ($files as $file) {
        if (is_dir($file)) {
            rmdir($file);
        } else {
            unlink($file);
        }
    }
    rmdir('../');
?>