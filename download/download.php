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
    header("Location: /?error=missing-fields");
    exit;
}
$file_code = $_POST["number-code-0"] . $_POST["number-code-1"] . $_POST["number-code-2"] . $_POST["number-code-3"];
// Check if code is valid (4 digits)
if (!preg_match("/^[0-9]{4}$/", $file_code)) {
    header("Location: /?error=missing-fields");
    exit;
}

// Check if file_id is set and valid
if (!isset($_POST["file_id"])) {
    header("Location: /?error=missing-fields");
    exit;
}
$file_id = $_POST["file_id"];
// Check if id is valid (4 digits)
if (!preg_match("/^[0-9]{4}$/", $file_id)) {
    header("Location: /?error=missing-fields");
    exit;
}

//
// Redirect to download is done with meta refresh in HTML
// This is done to show a success message
//
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="1;url=/api/download.php?id=<?= $file_id ?>&code=<?= $file_code ?>">
    <title>Document</title>
</head>

<body>
    <h1>Success</h1>
</body>

</html>