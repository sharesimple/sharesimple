<?php 

    // Create the config file
    $config = '{"max_file_size": 100,"max_file_name_length": 250,"allow_all_file_types": true,"automated_virusscan": false,"always_need_password": false}';
    file_put_contents("../../config/settings.json", json_encode(json_decode($config), JSON_PRETTY_PRINT));

    // Redirect
    header("Location: ../step4.html")
?>