<?php 
    // Rewrite index file
    $index = "<?php\n";
    $index .= "   // Redirect to app\n";
    $index .= "   header('Location: ./app/');\n";
    $index .= "?>";
    file_put_contents("../../index.php",$index);

    // Create deleter file
    $deleter_file = "<?php\n";
    $deleter_file .= "  // Remove setup directory\n";
    $deleter_file .= "  array_map('unlink', glob('setup/scripts/*.*'));\n";
    $deleter_file .= "  rmdir('./setup/scripts/');\n";
    $deleter_file .= "  array_map('unlink', glob('setup/*.*'));\n";
    $deleter_file .= "  rmdir('./setup/');\n";
    $deleter_file .= "  // Delete this file\n";
    $deleter_file .= "  unlink(__FILE__);\n";
    $deleter_file .= "  // Redirect to admin panel\n";
    $deleter_file .= "  header('Location: ../../admin/');\n";
    $deleter_file .= "?>";
    file_put_contents("../../del_setup.php", $deleter_file);

    // Redirect to tmp del file
    header("Location: ../../del_setup.php");

?>