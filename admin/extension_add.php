<?php 

    // Get the sent extension
    $file_extension = ".".$_POST['extension'];

    // Get and decode the json file ../config/settings.json
    $ext_list = json_decode(file_get_contents('../config/file_extensions.json'), true);

    // Push to array
    array_push($ext_list, $file_extension);

    // Encode json
    $encoded_json = json_encode(array_values($ext_list), JSON_PRETTY_PRINT);

    // Save the file
    file_put_contents('../config/file_extensions.json', $encoded_json);

?>