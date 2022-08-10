<?php 
    // Rewrite index file
    $index = "<?php\n";
    $index .= "   // Redirect to app\n";
    $index .= "   header('Location: ./app/');\n";
    $index .= "?>";
    file_put_contents("../../index.php",$index);

    // Create deleter file
    $deleter_file = "<?php\n";
    $deleter_file .= "  // Remove setup directory";
    $deleter_file .= "  rmdir('./setup/');";
    $deleter_file .= "  // Delete this file";
    $deleter_file .= "  unlink(__FILE__);";
    $deleter_file .= "  // Redirect to admin panel";
    $deleter_file .= "  header('Location: ../../admin/')";
    $deleter_file .= "?>";
    file_put_contents("../../del_setup.php", $deleter_file);

    // Redirect to tmp del file
    header("Location: ../../del_setup.php");

?>