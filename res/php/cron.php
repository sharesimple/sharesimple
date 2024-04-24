<?php

// Config
require_once($_SERVER["DOCUMENT_ROOT"] . '/../config.php');

// Connect to database
$con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// Remove expired download tokens
if ($stmt = $con->prepare("DELETE FROM downloadTokens WHERE expire_time < NOW()")) {
    $stmt->execute();
    $stmt->close();
} else {
    echo "Error: " . $con->error;
    exit();
}

// Get all files due to expire
if ($stmt = $con->prepare("SELECT file_id FROM files WHERE delete_time < NOW()")) {
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($file_id);
    while ($stmt->fetch()) {
        if ($stmt2 = $con->prepare("DELETE FROM files WHERE file_id = ?")) {
            $stmt2->bind_param("s", $file_id);
            $stmt2->execute();
            $stmt2->close();
        } else {
            echo "Error: " . $con->error;
            exit();
        }
        $files_dir = $_SERVER["DOCUMENT_ROOT"] . FILES_DIR;
        unlink($files_dir . $file_id);
    }
    $stmt->close();
} else {
    echo "Error: " . $con->error;
    exit();
}
