<?php
require 'lib.php';
if (isset($_POST['story_id'])){//if they posted a comment

$stmt = $mysqli->prepare("DELETE FROM stories where story_id=?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

$stmt->bind_param('i',$_POST['story_id']);
$stmt->execute();
header ("Location: home.php");
}


?>