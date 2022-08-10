<?php 
    // Get db connection
    require("../../config/database.php");
    
    // Check if db sharesimple exists
    $con = mysqli_connect($db_host,$db_user,$db_pass, "sharesimple");
    if (!isset($con)) {
        exit("ERROR (101)");
    } 

    // Check if table "accounts" exists
    if (!$con->query("DESCRIBE `accounts`")) {
        exit("ERROR (111)");
    }

    // Check if table "files" exists
    if (!$con->query("DESCRIBE `files`")) {
        exit("ERROR (112)");
    }

    // Check if settings file exists
    if(!file_exists("../../config/settings.json")){
        exit("ERROR (201)");
    }

    // Check if file_extensions file exists
    if(!file_exists("../../config/settings.json")){
        exit("ERROR (202)");
    }

    // Redirect
    header("Location: ../step6.html");
?>