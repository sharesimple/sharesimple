<?php 
    // Config
    require("../../config.php");

    // Connect to database
    $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    // Check if file exists
    $file_id = $_POST['file_id'];
    $file_passcode = $_POST['file_passcode'];
    if($stmt = $con->prepare("SELECT passcode FROM files WHERE file_id = ?")) {
        $stmt->bind_param("s", $file_id);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows != 1) die("File does not exist");
        $stmt->bind_result($passcode);
        $stmt->fetch();
        $stmt->close();
        $con->close();
        if(!isset($passcode)) exit("NOPASS");
        if($file_passcode != $passcode) exit("FALSEPASS");
        exit("TRUEPASS");
    } else {
        echo "Error: " . $con->error;
        exit();
    }
?>