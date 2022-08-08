<?php 
    // Get POST values
    $username = $_POST["acc_name"]; 
    $password = password_hash($_POST["acc_pass"],PASSWORD_DEFAULT); 

    // Get db connection
    require("../../config/database.php");
    // Try to connect to DB
    $conn = mysqli_connect($db_host,$db_user,$db_pass);
    // Check connection
    if ($conn->connect_error) {
        exit("Fehlerhafte Zugangsdaten");
    }

    // Add account entry
    $sql = "INSERT INTO sharesimple.accounts (username, password, is_admin) VALUES ('$username', '$password', 1)";
    if (!$conn->query($sql)) {
        exit($conn->error);
    }    

    // Redirect
    header("Location: ../step5.html")
?>