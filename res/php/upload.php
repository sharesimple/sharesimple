<?php
// Get Config
require_once($_SERVER["DOCUMENT_ROOT"] . '/../config.php');

// Connect to database
$con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// Choose where to save the uploaded file
$files_dir = $_SERVER["DOCUMENT_ROOT"] . FILES_DIR;

$passcode = null;
if ($_POST['use_passcode']) $passcode = strval(rand(1000, 9999));

$autodelete = null;
if ($_POST['autodelete']) $autodelete = $_POST['autodelete'];

// Switch autodelete to a timestamp
$deletion_time;
switch ($autodelete) {
    case 1:
        $deletion_time = date('Y-m-d H:i:s', strtotime('+1 minute'));
        break;
    case 2:
        $deletion_time = date('Y-m-d H:i:s', strtotime('+3 minutes'));
        break;
    case 3:
        $deletion_time = date('Y-m-d H:i:s', strtotime('+5 minutes'));
        break;
    case 4:
        $deletion_time = date('Y-m-d H:i:s', strtotime('+10 minutes'));
        break;
    case 5:
        $deletion_time = date('Y-m-d H:i:s', strtotime('+15 minutes'));
        break;
    case 6:
        $deletion_time = date('Y-m-d H:i:s', strtotime('+30 minutes'));
        break;
    case 7:
        $deletion_time = date('Y-m-d H:i:s', strtotime('+1 hour'));
        break;
    case 8:
        $deletion_time = date('Y-m-d H:i:s', strtotime('+4 hours'));
        break;
    case 9:
        $deletion_time = date('Y-m-d H:i:s', strtotime('+24 hours'));
        break;
    case 10:
        $deletion_time = date('Y-m-d H:i:s', strtotime('+7 days'));
        break;
    default:
        $deletion_time = date('Y-m-d H:i:s', strtotime('+15 minutes'));
        break;
}

// Generate a file id
$generating_file_id = true;
$file_id = strval(rand(1000, 9999));
while ($generating_file_id) {
    if ($stmt = $con->prepare("SELECT file_id FROM files WHERE file_id = ?")) {
        $stmt->bind_param("s", $file_id);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows == 0) {
            $generating_file_id = false;
            $stmt->close();
        } else {
            $file_id = strval(rand(1000, 9999));
        }
    }
}

// Insert file into database
if ($stmt = $con->prepare("INSERT INTO files (file_id, passcode, filename, delete_time) VALUES (?, ?, ?, ?)")) {
    $stmt->bind_param("ssss", $file_id, $passcode, $_FILES['upload']['name'], $deletion_time);
    $stmt->execute();
    $stmt->close();
    $con->close();

    // Save the uploaded file to the local filesystem
    $file_location = $files_dir . $file_id;
    if (move_uploaded_file($_FILES['upload']['tmp_name'], $file_location)) {
        exit(json_encode(array('success' => true, 'file_id' => $file_id, 'file_passcode' => $passcode)));
    } else {
        exit(json_encode(array('success' => false)));
    }
}
