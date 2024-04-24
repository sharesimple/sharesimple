<?php
require($_SERVER['DOCUMENT_ROOT'] . "/../config.php");
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sharesimple</title>
    <link rel="stylesheet" href="/res/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="/res/fontawesome/css/solid.min.css">
    <link rel="stylesheet" href="/res/css/fonts.css">
    <link rel="stylesheet" href="/res/css/main.css">
    <link rel="stylesheet" href="/res/css/parallax.css">
    <link rel="stylesheet" href="/res/css/grid_desktop.css">
    <link rel="stylesheet" href="/res/css/grid_mobile.css">
    <link rel="stylesheet" href="/res/css/settings.css">
    <link rel="stylesheet" href="/res/css/overlay.css">
    <link rel="stylesheet" href="/res/css/warnings.css">
    <link rel="stylesheet" href="/res/css/status.css">
    <link rel="stylesheet" href="/res/css/infobutton.css">
    <link rel="stylesheet" href="/res/css/success.css">
    <!-- Icons -->
    <link rel="apple-touch-icon" sizes="57x57" href="/res/img/apple-touch-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/res/img/apple-touch-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/res/img/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/res/img/apple-touch-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/res/img/apple-touch-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/res/img/apple-touch-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/res/img/apple-touch-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/res/img/apple-touch-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/res/img/apple-touch-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/res/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/res/img/favicon-16x16.png">
    <link rel="manifest" href="/res/site.webmanifest">
    <link rel="mask-icon" href="/res/img/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="shortcut icon" href="/res/img/favicon.ico">
    <meta name="apple-mobile-web-app-title" content="ShareSimple">
    <meta name="application-name" content="ShareSimple">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-config" content="/res/browserconfig.xml">
    <meta name="theme-color" content="#191D3F">
</head>

<body>
    <!-- The statusbox -->
    <div class="status_container status"></div>

    <div class="pwa_warning-overlay warning-overlay">Du nutzt aktuell ShareSimple als eine App.<br>Es können fehler auftreten!</div>
    <div class="mobile_warning-overlay warning-overlay">Du nutzt aktuell die Mobile version von ShareSimple.<br>Es können fehler auftreten!</div>
    <div class="upload_overlay">
        <h1><i class="fa-solid fa-cloud-arrow-up fa-fade" style="--fa-animation-duration: 3s;"></i></h1>
        <div>
            <div class="loader circle paused">
                <svg viewBox="0 0 80 80">
                    <circle id="test" cx="40" cy="40" r="32"></circle>
                </svg>
            </div>

            <div class="loader triangle paused">
                <svg viewBox="0 0 86 80">
                    <polygon points="43 8 79 72 7 72"></polygon>
                </svg>
            </div>

            <div class="loader square paused">
                <svg viewBox="0 0 80 80">
                    <rect x="8" y="8" width="64" height="64"></rect>
                </svg>
            </div>
        </div>
    </div>
    <div class="parallax-wrap"></div>
    <main>
        <div class="panel main_container-upload action_button">
            <div class="action_button-title">
                <h1>Hochladen</h1>
            </div>
            <div class="action_button-button" onclick="location.assign('/?p=up')">
                <i class="fa-solid fa-cloud-arrow-up"></i>
            </div>
            <input type="file" id="upload-input">
        </div>
        <div class="panel main_container-download action_button">
            <div class="action_button-title">
                <h1>Herunterladen</h1>
            </div>
            <div class="action_button-button" onclick="location.assign('/?p=down')">
                <span id="download_button_container">
                    <i class="fa-solid fa-cloud-arrow-down"></i>
                </span>
            </div>
        </div>
        <div class="main_container_right">
            <div class="panel main_container-title">
                <h1>ShareSimple</h1>
                <p>Share files with your friends at ease</p>
            </div>
            <div class="panel main_container-settings">
                <h1>Upload vollständig</h1>
                <div id="filedata">
                    <h2><b>ID:</b> <?= $_GET["id"] ?></h2>
                    <?php if (isset($_GET["pw"])) { ?> <h2><b>Passwort:</b> <?= $_GET["pw"] ?></h2> <?php } ?>
                </div>
                <div id="qrcode"></div>
            </div>
        </div>
    </main>
    <aside>
        <?php if (null !== INFO_URL) { ?>
            <div id="infobutton" onclick="window.open('<?= INFO_URL ?>', '_blank');">
                <i class="fas fa-info-circle"></i>
            </div>
        <?php } ?>
    </aside>
    <script src="/res/js/jquery/jquery-3.6.1.min.js"></script>
    <script src="/res/js/enter.js"></script>
    <script src="/res/js/status.js"></script>
    <script src="/res/js/parallax.js"></script>
    <script src="/res/js/settings.js"></script>
    <script src="/res/js/mobile.js"></script>
    <script src="/res/js/qrjs.js"></script>
    <? if (null !== SHORT_URL) { ?><script>
            var qrcode = new QRCode("qrcode", {
                colorDark: "#7E84F7",
                colorLight: "#30334A",
                correctLevel: QRCode.CorrectLevel.L
            });

            function makeCode() {
                const text = "<?= SHORT_URL ?>/<?= $_GET["id"] ?>/<?= $_GET["pw"] ?>";
                qrcode.makeCode(text);
            }

            makeCode();
        </script> <? } ?>
</body>

</html>