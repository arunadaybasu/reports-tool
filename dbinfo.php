<?php

$username="admin";
$password="myglamm123";
$database="myglamm_v2_franchise";

$con=mysqli_connect("myglamm-integration.c8gmtmvq9gbn.ap-southeast-1.rds.amazonaws.com",$username,$password,$database);

if (!$con) {
  die('Not connected : ' . mysql_error());
}

?>