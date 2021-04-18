<?php
define('DB_SERVER','hms.cmhsinxtjx3j.us-east-1.rds.amazonaws.com');
define('DB_USER','admin');
define('DB_PASS' ,'Shweta1234');
define('DB_NAME', 'hms');
$con = mysqli_connect(DB_SERVER,DB_USER,DB_PASS,DB_NAME);
// Check connection
if (mysqli_connect_errno())
{
 echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
?>
