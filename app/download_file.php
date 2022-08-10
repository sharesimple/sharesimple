<?php 

    // Get the database connection credentials
    require_once '../config/database.php';

    // Connect to the database
    $con = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
    if (mysqli_connect_errno()) {
        exit("An error occured, trying to connect to the database");
    } 

    // Get password and name from db
    if($stmt = $con->prepare("SELECT password, name from `files` WHERE id = ?")){
        $stmt->bind_param('s', $_POST['file_id']);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows == 0) {
            exit("That file couldn't be found!");
        }
        $stmt->bind_result($password, $name);
        $stmt->fetch();
        
        // Close the connection
        $stmt->close();
        $con->close();

        // Check if the password matches
        if($password != $_POST['file_key']){
            exit("WRONG PASSWORD");
        }
        
        // Create a filepath string
        $filepath = "../files/".$_POST['file_id'] . "/" .$name;

        // Download the file
        if (file_exists($filepath)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename='.basename($filepath));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filepath));
            ob_clean();
            flush();
            readfile($filepath);
            exit;
        }
    }
?>