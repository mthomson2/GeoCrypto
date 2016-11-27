<?php
  session_start();
  ob_start();
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
       <link rel="stylesheet" type="text/css" href="css/stylesheet.css">
	</head>
	<body>

		<?php
		include('connection.php');

		define("UPLOAD_DIR", "/home/mollyc6/public_html/geocrypto/uploads/");

		if (!empty($_FILES["myFile"])) {
			$myFile = $_FILES["myFile"];

			if ($myFile["error"] !== UPLOAD_ERR_OK) {
				echo "<p>An error occurred.</p>";
				exit;
			}

			$_SESSION["file_name"] = $myFile["name"];

			$sess_file = $_SESSION["file_name"];

			// ensure a safe filename
			$name = preg_replace("/[^A-Z0-9._-]/i", "_", $myFile["name"]);

			// // don't overwrite an existing file
			// $i = 0;
			// $parts = pathinfo($name);
			// while (file_exists(UPLOAD_DIR . $name)) {
			// 	$i++;
			// 	$name = $parts["filename"] . "-" . $i . "." . $parts["extension"];
			// }

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
				
				$filesize = filesize(UPLOAD_DIR . $name);
				//$filetext is a string
				$filetext = fread( $file, $filesize );

				$string = "";

				$quser = $_SESSION["username"];

				$result = mysqli_query($connection, "SELECT id FROM users WHERE username = '$quser'")
					or die("Failed  to query database " .mysql_error());

				$row = $result->fetch_assoc();
				$userFK = $row["id"];
				
				if ($_POST['encrypt'] == "encrypt")
				{
					
					$string = mcrypt_encrypt(MCRYPT_TWOFISH, $key, $filetext, MCRYPT_MODE_CBC,"some 16 byte iv.");

					$newFile = fopen(UPLOAD_DIR . $name, "w")
						or die("Unable to open file!");

					fwrite($newFile, $string);
					fclose($newFile);

					$inserting = mysqli_query($connection, "INSERT INTO files(fileName,fileKey, userID) VALUES('$name','$key','$userFK')")
						or die("Failed  to query database " .mysql_error());

				}
				else
				{
					$db_key = "";
				
					$result_key  = mysqli_query($connection, "SELECT fileKey FROM files WHERE userID = '$userFK' and fileName = '$sess_file' LIMIT 1") or die("Failed to query database.");

					if ($result_key->num_rows > 0) {
					    // output data of each row
					    while($row = $result_key->fetch_assoc()) {
					     	$db_key = $row["fileKey"];
					    }

					    $decrypt = mcrypt_decrypt(MCRYPT_TWOFISH, $db_key, $filetext, MCRYPT_MODE_CBC,"some 16 byte iv.");

					    array_map('unlink', glob(UPLOAD_DIR."/*.*"));

					    $newFile = fopen(UPLOAD_DIR . $name, "w")
						or die("Unable to open file!");

						fwrite($newFile, $decrypt);
						fclose($newFile);

					} else {
					    echo "0 results";
					}
				}
				
				fclose($file);
				
			}
			
		}
		?>
		
		<div id = "dwnbtn">
			<a href="download.php" class="myButton">Click to Download File</a>
		</div>

		<br>
		<br>
		<br>
		<br>
		<br>
		<br>

		<div class="row">
			<div class = "col-lg-12 text-center">
				<p>After you have successfully downloaded your file, please click the below button:</p>
			</div>
		</div>

		<div>
			<a href="thankyou.html" class="btnn">Click to Continue</a>
		</div>

	</body>
</html>
