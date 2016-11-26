<?php
  // Get User IP
  $ip = $_SERVER['REMOTE_ADDR'];
  //Send the API request with user Ip
  $ipinfoAPI = "http://ipinfo.io/{$ip}/json";
  //get the APi requeted data
  $load = file_get_contents($ipinfoAPI);
  //Convert it to the readable format
  $return = json_decode($load);

  $keywords = preg_split("/[\s,]+/", $return->loc);

?>

<html>
    <head>
       <title>Encrypting/Decrypting the File</title>
       <script type="text/javascript" src="http://cryptojs.altervista.org/api/functions_cryptography.js"></script>

       <script>
			var Crypt = new Crypt();  // constructor  
			function jscrypt() {
				var fi = <?php echo json_encode($str); ?>;
				alert(fi);
				var ciphertext = Crypt.AES.encrypt(fi); 
				var plaintext = Crypt.AES.decrypt(ciphertext);
				alert(ciphertext);
			}
		</script>

	</head>
	<body>
		<!-- <div id="crypt"></div> -->

		<?php
		define("UPLOAD_DIR", "/home/mollyc6/public_html/geocrypto/uploads/");

		function escapeJavaScriptText($string) 
		{ 
		    return str_replace("\n", '\n', str_replace('"', '\"', addcslashes(str_replace("\r", '', (string)$string), "\0..\37'\\"))); 
		} 

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
			else{
				
				// set proper permissions on the new file
				chmod(UPLOAD_DIR . $name, 0644);
				
				//UPLOAD_DIR . $name
				$file = fopen(UPLOAD_DIR . $name, "r");
				if (!$file) {
					echo "<p>Unable to open remote file.</p>";
					exit;
				}
				
				$string_file = file_get_contents(UPLOAD_DIR . $name);
				
				$str = escapeJavaScriptText($string_file);
				
				echo "<p>File Uploaded Successfully! :)</p>";
				
				$filesize = filesize(UPLOAD_DIR . $name);
				//$filetext is a string
				$filetext = fread( $file, $filesize );
					
				//Tells us how big the file is and what it says
				echo ( "File size : $filesize bytes" );
				echo ( "<pre>$filetext</pre>" );
				
				//Tells us the name the file was saved as in the uploads folder
				//echo "<p>Uploaded file saved as " . $name . ".</p>";
				
				
				if ($_POST['encrypt'] == "encrypt")
				{
					echo "<p>User selected Encrypt</p>";
					echo "<script> jscrypt(); </script>";
					
				}
				else
				{
					echo "<p>User selected Decrypt</p>";
					
				}
				
				fclose($file);
				
				// echo "<script>setTimeout(\"location.href = 'http://geocrypto.mollycodes.com/home.php';\",3500);</script>";
				
				//exit;
			}
			
		}
		?>
		
	</body>
</html>
