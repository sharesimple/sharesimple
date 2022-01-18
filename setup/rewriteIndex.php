<?php 
    // Just rewrite the ../index.php file to redirect to ./app/
    $file = fopen("../index.php", "w");
    $txt = "<?php\n\n";
    $txt .= "header('Location: ./app/');\n";
    $txt .= "?>";
    fwrite($file, $txt);
    fclose($file);
?>