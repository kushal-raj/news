<!DOCTYPE html>

<html>
<meta charset="utf-8">
<title>Index</title>
<body>
<?php
require 'lib.php';
if (isset($_POST['comment_id'])){
if($_SESSION['token'] !== $_POST['token']){
	die("Request forgery detected");
}
echo '<textarea rows=8 name="text" form="comment">'.$_POST['comment'].'</textarea>

<form action="edit_comment.php" method="POST" id="comment"> 
<input type="submit" name="submit" value="Change Comment"/>
<input type="hidden" name="change_id" value="'.$_POST['comment_id'].'"/>
<input type="hidden" name="story_id" value="'.$_POST['story_id'].'"/>
</form>';


}
if (isset($_POST['change_id'])){

echo "test";
$stmt = $mysqli->prepare(" Update `comments` SET comment=? where comment_id=?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}


$stmt->bind_param('si',$_POST['text'],$_POST['change_id']);
$stmt->execute();
header ("Location: comments.php?id=".$_POST['story_id']);


}

?>
</body>
</html>