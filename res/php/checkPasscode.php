<?php
// Config
require_once($_SERVER["DOCUMENT_ROOT"] . '/../config.php');

// Connect to database
$con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// Check if file exists
$file_id = $_POST['file_id'];
$file_passcode = $_POST['file_passcode'];
if ($stmt = $con->prepare("SELECT passcode, delete_time FROM files WHERE file_id = ?")) {
    $stmt->bind_param("s", $file_id);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows != 1) die("File does not exist");
    $stmt->bind_result($passcode, $delete_time);
    $stmt->fetch();
    $stmt->close();
    $con->close();
    if (strtotime($delete_time) < time()) die("File has expired");
    if (!isset($passcode)) exit("NOPASS");
    if ($file_passcode != $passcode) exit("FALSEPASS");
    exit("TRUEPASS");
} else {
    echo "Error: " . $con->error;
    exit();
}
