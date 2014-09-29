<?php
require 'lib.php';
if (isset($_POST['comment_id'])){//if they posted a comment

$stmt = $mysqli->prepare("DELETE FROM comments where comment_id=?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

$stmt->bind_param('i',$_POST['comment_id']);
$stmt->execute();
header ("Location: comments.php?id=".$_POST['story_id']);
}


?>