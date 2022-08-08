<?php 

    // Start sessions
    session_start();

    // Functions to generate an pseudo-random ID
    function generateID($length){
        $id_charset = "ABCDEFGHIJKLMNOPQRSTUVWXYZ123465789";
        $generated = "";
        for ($i=0; $i < $length; $i++) { 
            $new_char = substr($id_charset, mt_rand(0, strlen($id_charset)-1),1);
            $generated .= $new_char;
        }
        return $generated;
    }

    // Get the database connection credentials
    require_once '../config/database.php';

    // Connect to the database
    $con = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
    if (mysqli_connect_errno()) {
        exit("An error occured, trying to connect to the database");
    } 
    
    // Get and decode the json file ../config/settings.json
    $settings = json_decode(file_get_contents('../config/settings.json'), true);
    $allowed_file_types = json_decode(file_get_contents('../config/file_extensions.json'), true);

    // Get all settings
    $max_file_size = $settings['max_file_size'];
    $max_file_name_length = $settings['max_file_name_length'];
    $allow_all_file_types = $settings['allow_all_file_types'];

    // Convert max file size to bytes
    $max_file_size = $max_file_size * 1000000;
    
    // Check if the server has received files
    if ($_SERVER ["REQUEST_METHOD"] === "POST") {

        // check if there are files
        if (isset($_FILES['upload_file'])) {

            // Get the file name
            $file_name = $_FILES['upload_file']['name'];

            // Get the temp file
            $file_tmp = $_FILES['upload_file']['tmp_name'];

            // Get the file type
            $file_type = $_FILES['upload_file']['type'];

            // Get the file size
            $file_size = $_FILES['upload_file']['size'];

            // Get the file extension
            $explode_var = explode('.', $file_name);
            $file_ext = strtolower(end($explode_var));

            // Check if file exceeds max file size
            if($file_size > $max_file_size){

                exit("The file is too big");
            }

            // Check if all file types are allowed
            if(!$allow_all_file_types){

                // Check if the file type is allowed
                if (!in_array(".".$file_ext, $allowed_file_types)) {
                    
                    exit("That file type is not supported");
                }
            }

            // Check if file name is to long
            if(strlen($file_name) > $max_file_name_length){

                exit("That file name is too long");
            }

            // Check if id is already taken
            $checking_id_double = true;
            while($checking_id_double){
                    
                $new_id = generateID(4);
                if ($stmt = $con->prepare("SELECT * FROM files WHERE id = ?")) {
                    $stmt->bind_param('s', $new_id);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        // Do nothing (this will repeat the process) 
                    } else {

                        // Close the statement
                        $stmt->close();

                        // Reset id checking var
                        $checking_id_double = false;

                        echo ($new_id);

                        // Generate a new password
                        $password = generateID(3);

                        // Insert file into db
                        if($stmt = $con->prepare("INSERT INTO `files` (id, name, extension, password) VALUES (?, ?, ?, ?) ")){
                            $stmt->bind_param('ssss', $new_id, $file_name, $file_ext, $password);
                            $stmt->execute();
                        }

                        // Make id directory
                        mkdir("../files/".$new_id);

                        // Move file
                        move_uploaded_file($file_tmp, "../files/".$new_id . "/" .$file_name);
            
                        // Debug message
                        echo $file_name . " with extension " . $file_ext . " is type " . $file_type . ", it is ". $file_size . " big and saved temporarily under ". $file_tmp;

                        // Save informations to session
                        $_SESSION['last_file_id'] = $new_id;
                        $_SESSION['last_file_password'] = $password;

                        // Redirect to next page
                        header("Location: ./uploaded.php");
                    }
                }
            }

        } else {

            exit("No files received");
        }
    } else {

        exit("No files can be recieved currently");
    }
?>