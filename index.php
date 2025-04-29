<?php

// Bad practice goto used for good practice guard clause - Therefore ok :)
if (!isset($_GET["error"])) goto html_start;

$error = $_GET["error"];
switch ($error) {
    case "missing-fields":
        $error_message = "Please fill out all fields.";
        break;
    case "internal":
        $error_message = "An internal error occurred.";
        break;
    case "file":
        $error_message = "The ID you entered is invalid OR the passcode is wrong.";
        break;
    default:
        $error_message = "An unknown error occurred.";
        break;
}

html_start:
?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sharesimple</title>
    <link rel="stylesheet" href="/res/css/main.css" />
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
    <?php if (isset($error_message)) : ?>
        <div id="error"><?= $error_message ?></div>
    <?php endif; ?>
    <div class="title"><a href="/">ShareSimple</a></div>
    <div class="actions">
        <a href="/download/" class="download">
            <!-- <div > -->
            <h2>Download</h2>
            <!-- Icon by heroicons.com -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
            </svg>
            <!-- </div> -->
        </a>
        <a href="/upload/" class="upload">
            <h2>Upload</h2>
            <!-- Icon by heroicons.com -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
            </svg>
        </a>
        <a href="" class="tunnels">
            <h2>Tunnels</h2>
            <!-- Icon by heroicons.com -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
            </svg>
        </a>
    </div>
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