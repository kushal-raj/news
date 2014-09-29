<?php
session_start();
$mysqli = new mysqli('localhost', 'news', 'news', 'newssite');
 
if($mysqli->connect_errno) {
	printf("Connection Failed: %s\n", $mysqli->connect_error);
	exit;
}

if(empty($_SESSION['id'])){
header ("Location: index.php");
}

?>