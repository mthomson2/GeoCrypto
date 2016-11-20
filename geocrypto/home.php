<?php
if($_POST['formSubmit'] == "Submit")
{
	$errorMessage = "";
	
	if(empty($_POST['formupload']))
	{
		$errorMessage .= "<li>You forgot to enter a file name!</li>";
		
	}
	
	if(empty($errorMessage)) 
	{
		
		header("Location: thankyou.html");
		exit;
	}
	
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

    <title>GeoCrypto Home</title>

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
                <p class="lead">Home</p>
            </div>
        </div>
        <!-- /.row -->

        <div id="frm">
			
			<script language="JavaScript" type="text/javascript">
			function HandleBrowseClick()
			{
				var fileinput = document.getElementById("browse");
				fileinput.click();
			}

			function Handlechange()
			{
				var fileinput = document.getElementById("browse");
				var textinput = document.getElementById("filename");
				textinput.value = fileinput.value;
			}
			</script>
			
			
			<form action="home.php" method="POST" enctype="multipart/form-data">
				<p>
					Please upload a file: <br>
					<input type="file" id="browse" name="fileupload" style="display: none" onChange="Handlechange();" />
				</p>
		
				<input type="text" id="filename" readonly="true"/>
				<input type="button" value="Click to select file" id="fakeBrowse" onclick="HandleBrowseClick();"/>
				<input type="submit" name="formSubmit" value="Submit" />
			</form>
		</div>

        <!-- Getting user location -->
        <p id="location"></p>

        <script>
        window.onload = function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                // Get current cordinates.
                positionCords = {"lat": position.coords.latitude, "lng": position.coords.longitude};
            },
            function(error) {
                alert("Error");
            },
            {timeout: 30000, enableHighAccuracy: true, maximumAge: 75000}
                );
            }  
        };
        </script>



    </div>
    <!-- /.container -->

    <!-- jQuery Version 1.11.1 -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>

</html>
