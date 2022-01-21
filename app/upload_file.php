<?php 
    // Get the database connection credentials
    require_once '../config/database.php';
    
    // Get and decode the json file ../config/settings.json
    $settings = json_decode(file_get_contents('../config/settings.json'), true);

    // Get the "max_file_size" setting and convert it to a number (it's in gigabytes)
    $max_file_size = $settings['max_file_size'];
    $max_file_size = (int) $max_file_size;

    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_pass);

    // Check if the connection was successful
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get the file name and type
    $file_name = $_FILES['file']['name'];
    $file_type = $_FILES['file']['type'];

    // Get the file size in gigabytes
    $file_size = $_FILES['file']['size'];
    $file_size = $file_size / (1024 * 1024 * 1024);

    // Get password from the form
    // If the password is empty, set it to NULL
    if (empty($_POST['password'])) {
        $password = NULL;
    } else {
        $password = $_POST['password'];
    }

    // Check if the setting "allow_all_file_types" is set to true
    if ($settings['allow_all_file_types'] == true) {
        // If true, then allow all file types
        // So don't do anything here
    } else {
        
        // Check if the file type is allowed
        if (in_array($file_type, $settings['allowed_file_types'])) {

            // Check if the file size is allowed
            if ($file_size <= $max_file_size) {

                // Check if file name length is allowed
                if(strlen($file_name) <= $settings['max_file_name_length']) {

                    // Create a new entry in the database
                    // Prepare the query
                    $query = "INSERT INTO files (file_name, file_size, password) VALUES (?, ?, ?)";
    
                    // Prepare the statement
                    $stmt = $conn->prepare($query);
    
                    // Bind the parameters
                    $stmt->bind_param("sds", $file_name, $file_size, $password);
    
                    // Execute the statement
                    $stmt->execute();
    
                    // Get the file id
                    $file_id = $stmt->insert_id;
    
                    // Close the statement
                    $stmt->close();
    
                    // Move the file from cache to the uploads folder
                    // Upload the file to ../files/ directory with the file id as the name and the file type as the extension
                    move_uploaded_file($_FILES['file']['tmp_name'], "../files/".$file_id.".".$file_type);
    
                    // Finish the database entry
                    // Prepare the query to update the "upload_finished" column in the "files" table
                    $query = "UPDATE files SET upload_finished = 1 WHERE id = ?";
    
                    // Prepare the statement
                    $stmt = $conn->prepare($query);
    
                    // Bind the parameters
                    $stmt->bind_param("i", $file_id);
    
                    // Execute the statement
                    $stmt->execute();
    
                    // Close the statement
                    $stmt->close();
    
                    // Close the connection
                    $conn->close();
    
                    // Exit the script
                    // Return the file id
                    exit($file_id);
                } else {
                    // Alert the user that the file name is too long
                    exit("FNTL"); // FNTL = File Name Too Long
                }	

            } else {

                // Alert the user that the file type is not allowed
                exit("FSTB"); // FSTB = File Size Too Big
            }


        } else {

            // Alert the user that the file type is not allowed
            exit("FTNA"); //FTNA = File Type Not Allowed
        }

    }



?>