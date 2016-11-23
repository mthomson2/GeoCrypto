<?php
  // Get User IP
  $ip = $_SERVER['REMOTE_ADDR'];
  //Send the API request with user Ip
  $ipinfoAPI = "http://ipinfo.io/{$ip}/json";
  //get the APi requeted data
  $load = file_get_contents($ipinfoAPI);
  //Convert it to the readable format
  $return = json_decode($load);

  // print_r($return);
  // echo $return->loc;
  $keywords = preg_split("/[\s,]+/", $return->loc);
  // print_r($keywords[0]);
  // print_r($keywords[1]);

?>

<html>
<head>
  <title>Testing</title>
</head>
<body>
  <h1>Testing</h1>
  <p>hopefully this works</p>
  <div id="lat"></div>
  <div id="long"></div>

  <script type="text/javascript">
    var lat=<?php echo json_encode($keywords[0]); ?>;
    var long=<?php echo json_encode($keywords[1]); ?>;

    document.getElementById("lat").innerHTML= lat;
    document.getElementById("long").innerHTML= long;

  </script>

</body>
</html>
