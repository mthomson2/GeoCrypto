<html>
    <head>
       <title></title>
	</head>
	<body>
		
		<script>
		function hash(lng,lat)
		{
			var key = lng * lat; 
			if (key < 0)
			{
				key = key * -1;
			}
			key = key /1234;
			key = Math.floor(key);
			return key;
		}
		
		function encrypt(key, file)
		{
			//an array of words 
			var ret = " ";
			var words = file.split(" ");
			for (var i =0; i <words.length; i++)
			{
				//one word at a time
				var word = words[i];
				for (var j =0; j <word.length; j++)
				{
					//one char at a time
					var char = word.charCodeAt(j);
					char = char + key;
					ret = ret + " " + char;
				}
				ret = ret + "*";
			}
			return ret;
		}
		
		function decrypt(key, encryptedfile)
		{
			var ret = " ";
			encryptedfile = encryptedfile.trim();
			var words = encryptedfile.split("*");
			for(var i=0; i <words.length; i++)
			{
				if (i in words)//(words[i] != " ")
				{
					//get each word
					var word = words[i].trim();
					//array of char
					var char = word.split(" ");
					for(var j=0; j <char.length; j++)
					{
						if (j in char)
						{
							var dec = char[j].trim();
							dec = parseFloat(dec) -key;
							var dec = String.fromCharCode(dec);
							ret = ret + dec;
						}
					}
				}
				ret = ret + " ";
			}
			return ret;
		}
		</script>
		
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
			else{
				
				// set proper permissions on the new file
				chmod(UPLOAD_DIR . $name, 0644);
				
				//UPLOAD_DIR . $name
				$file = fopen(UPLOAD_DIR . $name, "r");
				if (!$file) {
					echo "<p>Unable to open remote file.</p>";
					exit;
				}
				
				
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
					
					//the following lines are calls to javascript and need to be edited
					
					//$lng= get longitube
					//$lat= get latitude
					//$key=hash($lng,$lat)
					//encrypt($key, $filetext)
					
				}
				else
				{
					echo "<p>User selected Decrypt</p>";
					
					//the following lines are calls to javascript and need to be edited
					
					//$key= get key
					//decrypt($key, $filetext)
					
				}
				
				fclose($file);
				
				echo "<script>setTimeout(\"location.href = 'http://geocrypto.mollycodes.com/home.php';\",3500);</script>";
				
				exit;
			}
			
		}
		?>
		
	</body>
</html>
