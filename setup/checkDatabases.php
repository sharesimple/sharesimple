<?php 

    // This script will check if the database exists before installing.

    // Get the database credentials from ../config/database.php
    require_once '../config/database.php';

    // Establish a connection to the server
    $conn = new mysqli($db_host, $db_user, $db_pass);

    // Check connection
    if ($conn->connect_error) {


        exit("conn_fail");
    }else{
        
        // Check if the database "sharesimple" exists
        $sql = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'sharesimple'";

        $result = $conn->query($sql);

        // If the database "sharesimple" exists, then exit with (DB_EXISTS)
        if ($result->num_rows > 0) {

            exit("db_exists");
        }else{

            exit("all_good");
        }
    }



?>