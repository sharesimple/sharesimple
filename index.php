
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sharesimple</title>
    <link rel="icon" type="image/x-icon" href="/res/img/favicon.ico" />
    <link rel="apple-touch-icon" href="/res/img/favicon.ico" />
    <link rel="stylesheet" href="/res/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="/res/fontawesome/css/solid.min.css">
    <link rel="stylesheet" href="/res/css/fonts.css">
    <link rel="stylesheet" href="/res/css/main.css">
    <link rel="stylesheet" href="/res/css/parallax.css">
    <link rel="stylesheet" href="/res/css/grid.css">
</head>
<body>
    <div class="parallax-wrap"></div>
    <main>
        <div class="panel main_container-upload action_button">
            <div class="action_button-title">
                <h1>Hochladen</h1>
            </div>
            <div class="action_button-button" onclick="document.querySelector('#upload-input').click();">
                <i class="fa-solid fa-cloud-arrow-up"></i>
            </div>
            <input type="file" id="upload-input">
        </div>
        <div class="panel main_container-download action_button">
            <div class="action_button-title">
                <h1>Herunterladen</h1>
            </div>
            <div class="action_button-button">
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
            <div class="panel main_container-settings"></div>
        </div>
    </main>
    <script src="/res/js/jquery/jquery-3.6.1.min.js"></script>
    <script src="/res/js/parallax.js"></script>
</body>

</html>