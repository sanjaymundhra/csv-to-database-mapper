<?php 
include_once('error.php');

$host="localhost"; // Host name.
$db_user="root"; //mysql user
$db_password="root"; //mysql pass
$database='mapper'; // Database name.

//$conn=mysql_connect($host,$db_user,$db_password) or die (mysql_error());
//mysql_select_db($db) or die (mysql_error());
$db=new mysqli($host,$db_user,$db_password,$database);

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
} 
?>