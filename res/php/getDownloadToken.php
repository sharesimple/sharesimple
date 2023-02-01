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
        if($stmt->num_rows != 1) die();
        $stmt->bind_result($passcode);
        $stmt->fetch();
        $stmt->close();
        if($file_passcode != $passcode) die();
        $generating_dl_token = true;
        while ($generating_dl_token) {
            if($stmt = $con->prepare("SELECT token FROM downloadTokens WHERE token = ?")) {
                $download_token = bin2hex(random_bytes(4));
                $stmt->bind_param("s", $download_token);
                $stmt->execute();
                $stmt->store_result();
                if($stmt->num_rows == 0) {
                    $stmt->close();
                    if($stmt = $con->prepare("INSERT INTO downloadTokens (token, file_id, expire_time) VALUES (?, ?, ?)")) {
                        // The time needs the mysql DATETIME format
                        $expiry_time = date("Y-m-d H:i:s", strtotime("+1 minute"));
                        $stmt->bind_param("sss", $download_token, $file_id, $expiry_time);
                        $stmt->execute();
                        $stmt->close();
                        exit ("?DT=".$download_token);
                        $generating_dl_token = false;
                    } else {
                        echo "Error: " . $con->error;
                        exit();
                    }
                }
            } else {
                echo "Error: " . $con->error;
                exit();
            }
        }
    } else {
        echo "Error: " . $con->error;
        exit();
    }
?>