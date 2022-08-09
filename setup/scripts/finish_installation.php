<?php 
    // Rewrite index file
    $index = "<?php\n";
    $index .= "   // Redirect to app\n";
    $index .= "   header('Location: ./app/');\n";
    $index .= "?>";
    file_put_contents("../../index.php",$index);

    // Redirect to admin panel login
    header("Location: ../../admin/");
?>