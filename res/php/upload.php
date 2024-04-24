<?php
// Get Config
require_once $_SERVER["DOCUMENT_ROOT"] . '/config.php';

// Connect to database
$con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// Choose where to save the uploaded file
$files_dir = "../../" . FILES_DIR;

$passcode = null;
if ($_POST['use_passcode']) $passcode = strval(rand(1000, 9999));

$autodelete = null;
if ($_POST['autodelete']) $autodelete = $_POST['autodelete'];

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
    $stmt->bind_param("ssss", $file_id, $passcode, $_FILES['upload']['name'], $autodelete);
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
