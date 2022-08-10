<?php 

    // Create the settings file
    $settings = '{"max_file_size": 100,"max_file_name_length": 250,"allow_all_file_types": true,"automated_virusscan": false,"always_need_password": false}';
    file_put_contents("../../config/settings.json", json_encode(json_decode($settings), JSON_PRETTY_PRINT));

    // Create the allowed-file-extension file
    $allowed_extensions = '[".jpg",".jpeg",".png",".gif",".bmp",".tiff",".webp",".mp4",".webm",".mkv",".avi",".mov",".wmv",".flv",".mp3",".ogg",".wav"]';
    file_put_contents("../../config/file_extensions.json", json_encode(json_decode($allowed_extensions), JSON_PRETTY_PRINT));


    // Redirect
    header("Location: ../step4.html")
?>