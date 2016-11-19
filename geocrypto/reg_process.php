<?php

	include('connection.php');

	// Get values passed from form in login.php file
	$username = $_POST['user'];
	$password = $_POST['pass'];
	//$pass_confirm = $_POST['pass_confirm'];

	// To prevent mysql injection
	$username = stripcslashes($username);
	$password = stripcslashes($password);
	//$pass_confirm = stripcslashes($pass_confirm);
	$username = mysqli_real_escape_string($connection, $username);
	$password = mysqli_real_escape_string($connection, $password);
	//$pass_confirm = mysqli_real_escape_string($connection, $pass_confirm);

	$hashed_password = password_hash($password, PASSWORD_DEFAULT);

	$check_username = mysqli_query($connection, "SELECT * FROM users WHERE username='$username'");
	$count = $check_username->num_rows;

	if ($count == 0) {
		// Query the database for user
		$result = mysqli_query($connection, "INSERT INTO users(username,password) VALUES('$username','$hashed_password')")
			or die("Failed  to query database " .mysql_error());
		
		echo("Successfully registered");
	} else {
		echo("Username is already taken. Please register again.");
	}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="GeoCrypto Encryption Software">
    <meta name="author" content="CS321 Team 3">

    <title>GeoCrypto Registration</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

	<link rel="stylesheet" type="text/css" href="css/stylesheet.css">

    <!-- Custom CSS -->
    <style>
    body {
        padding-top: 70px;
    }
    </style>

</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">GeoCrypto</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <!-- <ul class="nav navbar-nav">
                    <li>
                        <a href="#">About</a>
                    </li>
                    <li>
                        <a href="#">Services</a>
                    </li>
                    <li>
                        <a href="#">Contact</a>
                    </li>
                </ul> -->
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <!-- Page Content -->
    <div class="container">

        <div class="row">
            <div class="col-lg-12 text-center">
                <h1>GeoCrypto</h1>
                <p class="lead">Registration</p>
            </div>
        </div>
        <!-- /.row -->
        <div class="row">
        	<p>If you have successfully registered, please login</p>
        	<a href="index.html" class="btn btn-default">Login</a>
        </div>

        <div class="row">
        	<p>If you did not successfully register, please attempt to register again.</p>
        	<a href="registration.php" class="btn btn-default">Register</a>
        </div>
    </div>
    <!-- /.container -->

    <!-- jQuery Version 1.11.1 -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>

</html>

