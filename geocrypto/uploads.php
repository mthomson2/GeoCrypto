<?php
define("UPLOAD_DIR", "/home/mollyc6/public_html/geocrypto/uploads/");

if (!empty($_FILES["myFile"])) {
    $myFile = $_FILES["myFile"];

    if ($myFile["error"] !== UPLOAD_ERR_OK) {
        echo "<p>An error occurred.</p>";
        exit;
    }

    // ensure a safe filename
    $name = preg_replace("/[^A-Z0-9._-]/i", "_", $myFile["name"]);

    // don't overwrite an existing file
    $i = 0;
    $parts = pathinfo($name);
    while (file_exists(UPLOAD_DIR . $name)) {
        $i++;
        $name = $parts["filename"] . "-" . $i . "." . $parts["extension"];
    }

    // preserve file from temporary directory
    $success = move_uploaded_file($myFile["tmp_name"],
        UPLOAD_DIR . $name);
    if (!$success) { 
        echo "<p>Unable to save file.</p>";
        exit;
    }

    // set proper permissions on the new file
    chmod(UPLOAD_DIR . $name, 0644);
    if ($success) { 
        echo "<p>File Uploaded Successfully! :)</p>";
        
        $file = fopen (UPLOAD_DIR . $name, "r");
		if (!$file) {
			echo "<p>Unable to open remote file.\n";
			exit;
		}
		
		
		if ($_POST['encrypt'] == "encrypt")
		{
			echo "<p>User selected Encrypt\n";
		}
		else
		{
			echo "<p>User selected Decrypt\n";
		}

        exit;
    }
    
}
?>

