<?php

// 
// Check inputs
//

// Check if file is uploaded
if (!isset($_FILES["file"])) {
    echo json_encode(array("error" => "missing-fields", "error_human" => "One or more required fields are missing", "error_detail" => "file is missing"));
    exit;
}

// Passcode requirement
if (isset($_POST["passcode"])) $passcode = $_POST["passcode"];
else $passcode = 1;

// Delete after requirement
if (isset($_POST["delete_after"])) $delete_after = $_POST["delete_after"];
else $delete_after = 10080;

// 
// Connect to database
//

require_once $_SERVER["DOCUMENT_ROOT"] . "/config.php";
$con = mysqli_connect($config["db"]["host"], $config["db"]["username"], $config["db"]["password"], $config["db"]["dbname"]);
if (mysqli_connect_errno()) {
    echo json_encode(array("error" => "internal", "error_human" => "Internal error, try again later!"));
    exit;
}

//
// Main
//

// Calculate passcode 
if ($passcode == 1) {
    $code = rand(1000, 9999);
} else {
    $code = null;
}

// Calculate deletion time (limit to 1 week)
if ($delete_after > 10080) $delete_after = 10080;
$deletion = date("Y-m-d H:i:s", strtotime("+$delete_after minutes"));

// Generate a file id
$generating_file_id = true;
$file_id = strval(rand(1000, 9999));
while ($generating_file_id) {
    if ($stmt = $con->prepare("SELECT id FROM files WHERE id = ?")) {
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
if ($stmt = $con->prepare("INSERT INTO files (id, code, name, deletion) VALUES (?, ?, ?, ?)")) {
    $stmt->bind_param("ssss", $file_id, $code, $_FILES['file']['name'], $deletion);
    $stmt->execute();
    $stmt->close();
    $con->close();

    // Save the uploaded file to the local filesystem
    $file_location = $_SERVER["DOCUMENT_ROOT"] . $config["files"]["dir"] . $file_id;
    if (move_uploaded_file($_FILES['file']['tmp_name'], $file_location)) {
        exit(json_encode(array('success' => true, 'id' => $file_id, 'passcode' => $code, 'deletion' => $deletion)));
    } else {
        exit(json_encode(array('success' => false)));
    }
}
