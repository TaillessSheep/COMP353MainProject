<?php
//session_start() or die("Cannot start session.");


define('DB_SERVER',   'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'Password');
define('DB_DATABASE', 'COMP353');
//define('DB_PORT', 3306);
//ping 'oyc353.encs.concordia.ca';
//echo "console.log('huh')";


$db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
//$db =

// Check connection
if ($db->connect_error) {
    echo "connection failed.";
    die("connection failed: " . $db->connect_error);
}




