<?php
    session_start();

    define("UPLOAD_DIR", "/home/mollyc6/public_html/geocrypto/uploads/");
    
    $file = UPLOAD_DIR . $_SESSION["file_name"];
    $filen =  $_SESSION["file_name"];

    if(!file_exists($file)) die("I'm sorry, the file doesn't seem to exist.");

    $type = filetype($file);

    // Send file headers
    header("Content-type: $type");
    header("Content-Disposition: attachment;filename=$filen");
    header("Content-Transfer-Encoding: binary"); 
    header('Pragma: no-cache'); 
    header('Expires: 0');
    // Send the file contents.
    set_time_limit(0); 
    readfile($file);

    array_map('unlink', glob(UPLOAD_DIR."/*.txt"));
?>