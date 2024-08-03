<?php

// 
// Check inputs
//

// Check if file is uploaded
if (!isset($_FILES["file"])) {
    header("Location: /?error=missing-fields");
    exit;
}

// Check if file is empty
if ($_FILES["file"]["size"] == 0) {
    header("Location: /?error=missing-fields");
    exit;
}

// Passcode requirement
if (isset($_POST["passcode"])) $passcode = ($_POST["passcode"] == "on" ? 1 : 0);
else $passcode = 0;

// Delete after requirement
if (isset($_POST["delete_after"])) $delete_after = $_POST["delete_after"];
else $delete_after = 10080;

// 
// Connect to database
//

require_once $_SERVER["DOCUMENT_ROOT"] . "/config.php";
$con = mysqli_connect($config["db"]["host"], $config["db"]["username"], $config["db"]["password"], $config["db"]["dbname"]);
if (mysqli_connect_errno()) {
    header("Location: /?error=internal");
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
    if (!move_uploaded_file($_FILES['file']['tmp_name'], $file_location)) {
        header("Location: /?error=internal");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sharesimple</title>
    <link rel="stylesheet" href="upload.css" />
    <!-- Icons -->
    <link rel="apple-touch-icon" sizes="57x57" href="/res/img/apple-touch-icon-57x57.png" />
    <link rel="apple-touch-icon" sizes="60x60" href="/res/img/apple-touch-icon-60x60.png" />
    <link rel="apple-touch-icon" sizes="72x72" href="/res/img/apple-touch-icon-72x72.png" />
    <link rel="apple-touch-icon" sizes="76x76" href="/res/img/apple-touch-icon-76x76.png" />
    <link rel="apple-touch-icon" sizes="114x114" href="/res/img/apple-touch-icon-114x114.png" />
    <link rel="apple-touch-icon" sizes="120x120" href="/res/img/apple-touch-icon-120x120.png" />
    <link rel="apple-touch-icon" sizes="144x144" href="/res/img/apple-touch-icon-144x144.png" />
    <link rel="apple-touch-icon" sizes="152x152" href="/res/img/apple-touch-icon-152x152.png" />
    <link rel="apple-touch-icon" sizes="180x180" href="/res/img/apple-touch-icon-180x180.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="/res/img/favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="/res/img/favicon-16x16.png" />
    <link rel="manifest" href="/res/site.webmanifest" />
    <link rel="mask-icon" href="/res/img/safari-pinned-tab.svg" color="#5bbad5" />
    <link rel="shortcut icon" href="/res/img/favicon.ico" />
    <meta name="apple-mobile-web-app-title" content="ShareSimple" />
    <meta name="application-name" content="ShareSimple" />
    <meta name="msapplication-TileColor" content="#ffffff" />
    <meta name="msapplication-config" content="/res/browserconfig.xml" />
    <meta name="theme-color" content="#191D3F" />
</head>

<body>
    <div class="title"><a href="/">ShareSimple</a></div>
    <main>
        <div id="container">
            <fieldset>
                <legend>Success</legend>
                <div class="container">
                    <div class="qr" id="qrcode">
                    </div>
                    <div class="fileid">
                        <h2>ID</h2>
                        <h1><?= $file_id ?></h1>
                    </div>
                    <div class="filecode">
                        <h2>Passcode</h2>
                        <h1><?php echo $code == null ? "-" : $code ?></h1>
                    </div>
                </div>
            </fieldset>
        </div>
    </main>
    <footer>
        <div>
            Made with ❤️ in Germany
            <span>&CenterDot;</span>
            <a href="/legal/impressum">Impressum</a>
            <span>&CenterDot;</span>
            <a href="/legal/datenschutz">Datenschutzerkl&auml;rung</a>
            <span>&CenterDot;</span>
            <a href="/legal/agb">AGB</a>
        </div>
    </footer>
    <script src="/res/js/jquery/jquery-3.6.1.min.js"></script>
    <script src="/res/js/qrjs.js"></script>
    <script>
    var qrcode = new QRCode("qrcode", {
        colorDark: "#3f48cc",
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.L
    });

    const text = "<?= $config["shorturl"] ?>/<?= $file_id ?>/<?= $code ?>";
    qrcode.makeCode(text);
    </script>
</body>

</html>