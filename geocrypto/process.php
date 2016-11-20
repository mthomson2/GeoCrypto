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

	$hashed_password = password_hash($password, PASSWORD_DEFAULT);

	// Query the database for user
	$result = mysqli_query($connection, "select * from users where username = '$username'")
		or die("Failed  to query database " .mysql_error());
	$row = mysqli_fetch_array($result);

	if (password_verify($password, $row['password']))
	{
		$_SESSION['userSession'] = $row['username'];
		header("Location: home.php");
	} else {
		header("Location: invalid.php");
	}

	$connection->close();
?>