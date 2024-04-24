<?php
// Config
require_once($_SERVER["DOCUMENT_ROOT"] . '/../config.php');

// Connect to database
$con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// Check if file_id is valid
$file_id = $_GET['id'];
$passcode = $_GET['pass'];

// Get the filename
if ($stmt = $con->prepare("SELECT filename, passcode, delete_time FROM files WHERE file_id = ?")) {
    $stmt->bind_param("s", $file_id);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows != 1) die("File does not exist");
    $stmt->bind_result($filename, $passcode_db, $delete_time);
    $stmt->fetch();
    $stmt->close();
    // Check if the file has expired
    if (strtotime($delete_time) < time()) {
        die("File has expired");
    }
    // Check if the passcode is correct
    if ($passcode != $passcode_db) {
        die("Incorrect passcode");
    }
    // Download the file
    $file = $_SERVER["DOCUMENT_ROOT"] . FILES_DIR . $file_id;
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
