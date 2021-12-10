<?php

    // Maximum upload file size
    $max_size = 1610612736; // 1,5 GB

    // Create a password
    $password = substr(str_shuffle("0123456789"), 0, 4);

    // Directory the files are stored in
    $target_dir = "files/";

    // File name
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

    // Get file type
    $filetype = pathinfo($target_file, PATHINFO_EXTENSION);

    // An variable to store if error happened
    $uploadOk = 1;

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
        header('Location: message.html?error=%22Fehler%20mit%20der%20Datenbank%22');
        exit();
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        header('Location: message.html?message=""&showReportButton=true&passcode="Eine%20Datei%20mit%20dem%20selben%20Namen%20existiert%20bereits."&file=""');
        $uploadOk = 0;
    }

    // Check if file is bigger than 1.5GB
    if ($_FILES["fileToUpload"]["size"] > $max_size) {
        header('Location: message.html?message=""&showReportButton=true&passcode="Deine%20Datei%20ist%20zu%20gross."&file=""');
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";

    // if everything is ok, try to upload file
    } else {

        // Try to move the uploaded file to the target directory
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            
            // Create a delete timestamp 
            $delete_time = time() + (7 * 24 * 60 * 60);

            // register the file in the database
            if ($stmt = $con->prepare('INSERT INTO sharesimple (filename, password, delete_on) VALUES (?, ?, ?)')) {
        
                // Bind parameters
                $stmt->bind_param('sss', basename($_FILES["fileToUpload"]["name"]), $password, $delete_time);
                $stmt->execute();
                $stmt->store_result();
                
                // Get the id from the database
                $fileid = $con->insert_id;

                // Wurde erfolgreich Hochgeladen.
                header('Location: message.html?message="Datei%20Hochgeladen"&showReportButton=false&passcode='.$password.'&file='.$fileid);
            }
        } else {

            echo "Sorry, there was an error uploading your file.";
        }
    }
?>
