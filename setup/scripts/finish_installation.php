<?php 
    // Rewrite index file
    $index = "<?php\n\n";
    $index .= "   header('Location: ./app/');\n";
    $index .= "?>";
    file_put_contents("../../index.php",$index);

    // Redirect to admin panel login
    header("Location: ../../admin/");
?>