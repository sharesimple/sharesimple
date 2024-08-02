<?php

// 
// Check inputs
// 

// Get id and code GET parameters
if (!isset($_GET["id"])) {
    header("Location: /");
    exit;
}
$id = $_GET["id"];
if (!preg_match("/^[0-9]{4}$/", $id)) {
    header("Location: /");
    exit;
}

if (isset($_GET["code"])) {
    $code = $_GET["code"];
    if (!preg_match("/^[0-9]{4}$/", $code)) {
        header("Location: /");
        exit;
    }
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
$query = $con->prepare("SELECT code, deletion, name FROM files WHERE id = ?");
$query->bind_param("s", $id);
$query->execute();
$query->store_result();
$query->bind_result($db_code, $deletion, $name);
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

// Check if code is required
if ($db_code != null) {
    if (!isset($code)) {
        header("Location: /");
        exit;
    }
    if ($code != $db_code) {
        header("Location: /");
        exit;
    }
}

// Download file
$file = $_SERVER["DOCUMENT_ROOT"] . $config["files"]["dir"] . $id;
if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $name . '"');
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
