<?php 
    // Get the database credentials from ../config/database.php 
    require_once '../config/database.php';

    // Establish a connection to the server
    $conn = new mysqli($db_host, $db_user, $db_pass);

    // Check connection
    if ($conn->connect_error) {
        die("error: Connection failed: " . $conn->connect_error);
    }

    // Get the post data
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password2 = $_POST['password_2'];

    // Check if the passwords match
    if ($password != $password2) {
        echo "error: Passwords do not match";
        exit();
    } else {
        // Hash the password
        $password = password_hash($password, PASSWORD_DEFAULT);

        // Add the user to the database
        $sql = "INSERT INTO sharesimple.accounts (username, password, is_admin) VALUES ('$username', '$password', 1)";

        // Run the query
        if ($conn->query($sql) === TRUE) {
            
            // Redirect to step4.html
            header("Location: step4.html");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    // Close the connection
    $conn->close();

?>