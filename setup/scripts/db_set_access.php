<?php 
    // Get all form values
    $db_host = $_POST["db_host"];
    $db_user = $_POST["db_user"];
    $db_pass = $_POST["db_pass"];
    $db_name = $_POST["db_name"];

    // Try to connect to DB
    $conn = mysqli_connect($db_host,$db_user,$db_pass);
    // Check connection
    if ($conn->connect_error) {
        exit("Fehlerhafte Zugangsdaten");
    }

    // Create string
    $db_file = "<?php\n";
    $db_file .= "   \$db_host = \"$db_host\";\n";
    $db_file .= "   \$db_user = \"$db_user\";\n";
    $db_file .= "   \$db_pass = \"$db_pass\";\n";
    $db_file .= "   \$db_name = \"$db_name\";\n";
    $db_file .= "?>";

    // Write file
    file_put_contents("../../config/database.php", $db_file);

    // Redirect
    header("Location: ../step2.html")
?>