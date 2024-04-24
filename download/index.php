<?php
// Config
require_once($_SERVER["DOCUMENT_ROOT"] . '/../config.php');

// Connect to database
$con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// Check if token is valid
$token = $_GET['DT'];
if ($stmt = $con->prepare("SELECT file_id, expire_time FROM downloadTokens WHERE token = ?")) {
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows == 0) die("Token not authorized");
    $stmt->bind_result($file_id, $expire_time);
    $stmt->fetch();
    if (strtotime($expire_time) < time()) die("Token expired");
    $stmt->close();
    // Get the filename
    if ($stmt = $con->prepare("SELECT filename FROM files WHERE file_id = ?")) {
        $stmt->bind_param("s", $file_id);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows != 1) die("File does not exist");
        $stmt->bind_result($filename);
        $stmt->fetch();
        $stmt->close();
        // Invalidate token (delete from database)
        if ($stmt = $con->prepare("DELETE FROM downloadTokens WHERE token = ?")) {
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $stmt->close();
            $con->close();
            // Download the file
            $file = "../" . FILES_DIR . $file_id;
            if (file_exists($file)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="' . $filename . '"');
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));
                ob_clean();
                flush();
                readfile($file);
                exit;
            } else {
                die("File does not exist");
            }
        } else {
            echo "Error: " . $con->error;
            exit();
        }
    } else {
        echo "Error: " . $con->error;
        exit();
    }
} else {
    echo "Error: " . $con->error;
    exit();
}
