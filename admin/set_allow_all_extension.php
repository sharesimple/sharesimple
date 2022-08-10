<?php 

    // Get the sent bool
    $bool = $_POST['bool'];
    if($bool == 1){
        $bool = true;
    }else{
        $bool = false;
    }

    // Get and decode the json file ../config/settings.json
    $settings = json_decode(file_get_contents('../config/settings.json'), true);

    // Set setting
    $settings["allow_all_file_types"] = $bool;

    // Encode json
    $encoded_json = json_encode($settings, JSON_PRETTY_PRINT);

    // Save the file
    file_put_contents('../config/settings.json', $encoded_json);
    exit($encoded_json);

?>