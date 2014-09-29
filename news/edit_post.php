<?php
require 'lib.php';
if($_SESSION['token'] !== $_POST['token']){
	die("Request forgery detected");
}
if (isset($_POST['story_id'])){

echo '<textarea rows=8 name="text" form="post">'.$_POST['description'].'</textarea>

<form action="edit_post.php" method="POST" id="post"> 
<input type="submit" name="submit" value="Change Post"/>
<input type="hidden" name="token" value="'.$_SESSION['token'].'" />
<input type="hidden" name="story_id" value="'.$_POST['story_id'].'"/>
</form>';


}
if (isset($_POST['text'])){

$stmt = $mysqli->prepare(" Update `stories` SET description=? where story_id=?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}


$stmt->bind_param('si',$_POST['text'],$_POST['story_id']);
$stmt->execute();
header ("Location: comments.php?id=".$_POST['story_id']);


}

?>