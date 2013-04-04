<?php
$address= "127.0.0.1";
$user="root";
$password="";
$database="life_catalogue";

$dbLink = mysql_connect($address,$user,$password);
mysql_set_charset('utf8', $dbLink);
@mysql_select_db($database) or die( "Unable to select database $database");

