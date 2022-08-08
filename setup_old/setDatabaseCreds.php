<?php 

    // Get all elements from post
    $db_host = $_POST['db_host'];
    $db_user = $_POST['db_user'];
    $db_pass = $_POST['db_pass'];

    // Check if one of the fields is empty
    if(empty($db_host) || empty($db_user)) {
        echo "One of the fields is empty";
        exit();
    }

    // Create connection
    $conn = new mysqli($db_host, $db_user, $db_pass);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }else{

        // Save connection to file
        $file = fopen("../config/database.php", "w");
        $txt = "<?php\n\n";
        $txt .= "\$db_host = '$db_host';\n";
        $txt .= "\$db_user = '$db_user';\n";
        $txt .= "\$db_pass = '$db_pass';\n\n";
        $txt .= "?>";
        fwrite($file, $txt);
        fclose($file);

        // Redirect to step2 page
        header("Location: step2.html");
    }
    
?>