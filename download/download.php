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
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- REDIRECT TO DOWNLOAD -->
    <meta http-equiv="refresh" content="1;url=/api/download.php?id=<?= $file_id ?>&code=<?= $file_code ?>">
    <title>Sharesimple</title>
    <link rel="stylesheet" href="download.css" />
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
                <legend>Download</legend>
                <h1>Download starts automatically</h1>
                <p>If it doesn't you can start it <a href="/api/download.php?id=<?= $file_id ?>&code=<?= $file_code ?>">here</a>.</p>
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
</body>

</html>