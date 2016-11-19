<?php

	include('connection.php');

	// Get values passed from form in login.php file
	$username = $_POST['user'];
	$password = $_POST['pass'];

	// To prevent mysql injection
	$username = stripcslashes($username);
	$password = stripcslashes($password);
	$username = mysqli_real_escape_string($connection, $username);
	$password = mysqli_real_escape_string($connection, $password);

	// Query the database for user
	$result = mysqli_query($connection, "select * from users where username = '$username' and password = '$password'")
		or die("Failed  to query database " .mysql_error());
	$row = mysqli_fetch_array($result);
	if ($row['username'] == $username && $row['password'] == $password) {
		echo "Login Successful :) Welcome ".$row['username'];
	} else { 
		echo "Failed to login. Register now!";
	}
?>