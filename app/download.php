<?php

    $fileId = $_POST["fileToDownload"];

    // Database credentials
    $DATABASE_HOST = 'localhost';
    $DATABASE_USER = 'root';
    $DATABASE_PASS = '';
    $DATABASE_NAME = 'mini_projects';

    // Connect with the Credentials
    $con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

    // check if the connection was successfull
    if (mysqli_connect_errno()) {

        // Log the error
        error_log("Error(101)-".mysqli_connect_error(),0);
        exit("Error(101)-".mysqli_connect_error());

        // Display an error.
        header('Location: message.html?message=%22Fehler%20mit%20der%20Datenbank%22');
        exit();
    }
    // register the file in the database
    if ($stmt = $con->prepare('SELECT `filename`, `password` FROM `sharesimple` WHERE unique_id = ?')) {
    
        // Bind parameters
        $stmt->bind_param('s', $fileId);
        $stmt->execute();

        // Store the result so we can check if the account exists in the database.
        $stmt->store_result();

        // Check if Account exist
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($filename, $password);
            $stmt->fetch();

            if($_POST["passcode"] == $password) {

                header('Location: ./files/'.$filename);

            } else {
                header('Location: message.html?message=%22Falsches%20Passwort%22');
                exit();
            }


        } else { 
            header('Location: message.html?message=""&showReportButton=true&passcode="Diese%20Datei%20existiert%20nicht!"');
        }
        

    }
?>
