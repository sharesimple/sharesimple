<?php 
    // Declatarion: 
    // DSC - Database successfully created
    // T*SC - Table * successfully created


    // Get the database credentials from ../config/config.php
    require_once '../config/database.php';

    // Establish a connection to the server
    $conn = new mysqli($db_host, $db_user, $db_pass);

    // Check connection
    if ($conn->connect_error) {
        die("error: Connection failed: " . $conn->connect_error);
    }

    // Create the database "sharesimple" with UTF-8 encoding
    $sql = "CREATE DATABASE sharesimple CHARACTER SET utf8 COLLATE utf8_general_ci";

    if ($conn->query($sql) === TRUE) {
        echo "DCS";

        // Create the table "accounts" with UTF-8 encoding and the following columns:
        // id: int(11) NOT NULL AUTO_INCREMENT,
        // username: varchar(255) NOT NULL,
        // password: varchar(255) NOT NULL,
        // PRIMARY KEY (id)
        $sql = "CREATE TABLE sharesimple.accounts (
            id int(11) NOT NULL AUTO_INCREMENT,
            username varchar(255) NOT NULL,
            password varchar(255) NOT NULL,
            PRIMARY KEY (id)
        )";

        // Run the query
        if ($conn->query($sql) === TRUE) {
            echo "T1SC";

            // Create the table "files" with UTF-8 encoding and the following columns:
            // id: int(11) NOT NULL AUTO_INCREMENT,
            // name: varchar(255) NOT NULL,
            // created: DATE NOT NULL DEFAULT CURRENT_TIMESTAMP,
            // password: varchar(255) NOT NULL,
            // PRIMARY KEY (id)

            $sql = "CREATE TABLE sharesimple.files (
                id int(11) NOT NULL AUTO_INCREMENT,
                name varchar(255) NOT NULL,
                created DATE NOT NULL DEFAULT CURRENT_TIMESTAMP,
                password varchar(255) NOT NULL,
                PRIMARY KEY (id)
            )";

            // Run the query

            if ($conn->query($sql) === TRUE) {
                echo "T2SC";

                // Create the table "settings" with UTF-8 encoding and the following columns:
                // file_extensions: varchar(255) NOT NULL,
                // file_size: int(11) NOT NULL,
                // password_length: int(11) NOT NULL,
                // username_length: int(11) NOT NULL,
                // PRIMARY KEY (id)

                $sql = "CREATE TABLE sharesimple.settings (
                    file_extensions varchar(255) NOT NULL,
                    file_size int(11) NOT NULL,
                    password_length int(11) NOT NULL,
                    username_length int(11) NOT NULL
                )";

                // Run the query

                if ($conn->query($sql) === TRUE) {
                    echo "T3SC";
                } else {
                    echo "error: " . $sql . "<br>" . $conn->error;
                }

            } else {
                echo "error: " . $sql . "<br>" . $conn->error;
            }	

        } else {
            echo "error: " . $sql . "<br>" . $conn->error;
        }

    } else {
        echo "Error creating database: " . $conn->error;
    }


?>