<?php 

    // Get the sent extension
    $button = $_POST['button'];

    // Get and decode the json file ../config/settings.json
    $ext_list = json_decode(file_get_contents('../config/file_extensions.json'), true);

    // Remove the button from the settings array
    $ext_list = array_diff($ext_list, array($button));

    // Encode json
    $encoded_json = json_encode(array_values($ext_list));

    // Save the file
    file_put_contents('../config/file_extensions.json', $encoded_json);
    
    
    exit(json_encode($ext_list));

?>