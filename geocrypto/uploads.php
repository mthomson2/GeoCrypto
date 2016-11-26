<?php
  session_start();
  // Get User IP
  $ip = $_SERVER['REMOTE_ADDR'];
  //Send the API request with user Ip
  $ipinfoAPI = "http://ipinfo.io/{$ip}/json";
  //get the APi requeted data
  $load = file_get_contents($ipinfoAPI);
  //Convert it to the readable format
  $return = json_decode($load);

  $keywords = preg_split("/[\s,]+/", $return->loc);

  $key = $keywords[0].$keywords[1]."0";

?>

<html>
    <head>
       <title>Encrypting/Decrypting the File</title>
	</head>
	<body>
		<!-- <div id="crypt"></div> -->

		<?php
		include('connection.php');

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
				
				
				echo "<p>File Uploaded Successfully! :)</p>";
				
				$filesize = filesize(UPLOAD_DIR . $name);
				//$filetext is a string
				$filetext = fread( $file, $filesize );
					
				//Tells us how big the file is and what it says
				echo ( "File size : $filesize bytes" );
				echo ( "<pre>$filetext</pre>" );
				
				//Tells us the name the file was saved as in the uploads folder
				//echo "<p>Uploaded file saved as " . $name . ".</p>";
				
				$string = "";
				
				if ($_POST['encrypt'] == "encrypt")
				{
					echo "<p>User selected Encrypt</p>";
					
					$string = mcrypt_encrypt(MCRYPT_TWOFISH, $key, $filetext, MCRYPT_MODE_CBC,"some 16 byte iv.");
					$quser = $_SESSION["username"];

					$result = mysqli_query($connection, "SELECT id FROM users WHERE username = '$quser'")
						or die("Failed  to query database " .mysql_error());

					$row = $result->fetch_assoc();
					$userFK = $row["id"];

					$inserting = mysqli_query($connection, "INSERT INTO files(fileName,fileKey, userID) VALUES('$name','$key','$userFK')")
						or die("Failed  to query database " .mysql_error());
					
				}
				else
				{
					echo "<p>User selected Decrypt</p>";

					$decrypt = mcrypt_decrypt(MCRYPT_TWOFISH, $key, $string, MCRYPT_MODE_CBC,"some 16 byte iv.");
					// echo $decrypt;
					
				}
				
				fclose($file);
				
				// echo "<script>setTimeout(\"location.href = 'http://geocrypto.mollycodes.com/home.php';\",3500);</script>";
				
				//exit;

				// Delete the file after use
				array_map('unlink', glob(UPLOAD_DIR."/*.txt"));
			}
			
		}
		?>
		
	</body>
</html>
