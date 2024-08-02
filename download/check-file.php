<?php

// 
// Check inputs
// 

if (
    !isset($_POST["number-code-0"]) ||
    !isset($_POST["number-code-1"]) ||
    !isset($_POST["number-code-2"]) ||
    !isset($_POST["number-code-3"])
) {
    header("Location: /");
    exit;
}
$file_id = $_POST["number-code-0"] . $_POST["number-code-1"] . $_POST["number-code-2"] . $_POST["number-code-3"];
// Check if code is valid (4 digits)
if (!preg_match("/^[0-9]{4}$/", $file_id)) {
    header("Location: /");
    exit;
}

// 
// Connect to database
// 

require_once $_SERVER["DOCUMENT_ROOT"] . "/config.php";
$con = mysqli_connect($config["db"]["host"], $config["db"]["username"], $config["db"]["password"], $config["db"]["dbname"]);
if (mysqli_connect_errno()) {
    header("Location: /");
    exit;
}

//
// Main
//

// Get file metadata
$query = $con->prepare("SELECT code, deletion FROM files WHERE id = ?");
$query->bind_param("s", $file_id);
$query->execute();
$query->store_result();
$query->bind_result($code, $deletion);
$rows = $query->num_rows;
$query->fetch();
$query->close();
// Check if file exists
if ($rows == 0) {
    header("Location: /");
    exit;
}
// Check if file is already over deletion time
if ($deletion != null && $deletion < time()) {
    header("Location: /");
    exit;
}
// Check where to redirect
if ($code == null) {
    // Redirect to download
    header("Location: /api/download.php/" . $file_id);
    exit;
} else {
    // Redirect to code input with file_id
    header("Location: code.php?file_id=" . $file_id);
    exit;
}
