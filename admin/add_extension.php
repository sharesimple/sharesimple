<?php 

    // Get the sent extension
    $file_extension = ".".$_POST['extension'];

    // Get and decode the json file ../config/settings.json
    $settings = json_decode(file_get_contents('../config/settings.json'), true);

    // Push to array
    array_push($settings["allowed_file_types"], $file_extension);

    // Encode json
    $encoded_json = json_encode($settings);

    // Save the file
    file_put_contents('../config/settings.json', $encoded_json);

?>