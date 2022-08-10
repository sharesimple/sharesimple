<?php 

    // Start session
    session_start();
?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1>Datei erfolgreich hochgeladen</h1>
    <div id="main">
        <h2>Die Datei kann folgendermaßen gefunden werden:</h2>
        <p></p>

            <br> 
            <span onclick='navigator.clipboard.writeText("<?=$_SESSION["last_file_id"]?>")'>Datei: <?=$_SESSION['last_file_id']?></span>
            <br>
            <span onclick='navigator.clipboard.writeText("<?=$_SESSION["last_file_password"]?>")'> Zugriffscode: <?=$_SESSION['last_file_password']?></span>
    </div>
    <button id="backlink_home" onclick="location.assign('./index.html')">Zurück zur startseite</button>
    <script src="script.js"></script>
</body>

</html>