<?php
//Call this script via http like this:
//http(s)://<web server address>/<this script name>.php?ip=<router ip>
$ip = $_GET['ip'];

//The command you would like to be ran on the router
$url = "https://$ip/level/15/exec/-/show/voice/call/status/CR";

//credentials on cisco device
$username = 'admin';
$password = 'password';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);

$out = curl_exec($ch) or die ("connection error");
curl_close($ch);

//Regular expression match the information we are looking for in the routers response.
preg_match_all("/([0-9]+|No)\sactive call/",$out,$array) or die("parse error");

//display output the monitoring system can understand
//Expected format:  [x]  Where x is the number of active calls
if(is_numeric($array[1][0]))
 echo "[" . $array[1][0] ."]";
else if($array[1][0] == "No")
 echo "[0]";
else
 echo "error";
?>
