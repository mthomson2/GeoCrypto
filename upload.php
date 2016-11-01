<?php

print_r($_POST);

echo "FILES\n";

var_dump($FILES);

echo "file\n";

print_r($_FILES['file']);

$md5 = md5_file($_FILES['file']['tmp_name'])
echo "Unencrypted md5: ".$md5;

// INSERT INTO files (encrypted_md5, decrypted_md5, key_id, user_id) VALUES ("hi", "bye", 2, 3);

?>
