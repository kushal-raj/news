<!DOCTYPE html>

<html>
<meta charset="utf-8">
<title>Index</title>
<body>
<?php
session_start();
$mysqli = new mysqli('localhost', 'news', 'news', 'newssite');
 
if($mysqli->connect_errno) {
	printf("Connection Failed: %s\n", $mysqli->connect_error);
	exit;
}
$stmt = $mysqli->prepare("select title,score,link,description,username,story_id,user_id 
from stories join Users on(stories.user_id=Users.id)
where story_id=?
");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
$stmt->bind_param('i', $_GET['id']);
$stmt->execute();
$stmt->bind_result($title,$score,$link,$description,$username,$story_id,$user_id);
while($stmt->fetch()){
echo "<a href='home.php'>Home</a><br>";
echo "<a href='".$link."'  >".$title."</a><br>" ;
echo "--".$description."--";
if ($user_id==$_SESSION['id']){
echo "<form action='edit_post.php' method='POST' style='display: inline;'>
	<input type='hidden' name='description' value='".$description."' />
	<input type='hidden' name='story_id' value='".$story_id."' />
	<input type='hidden' name='token' value='".$_SESSION['token']."' />
	<input type='submit' value='Edit post' /> </form> <br>";
}
echo "<br>---------------------------------<br>";

}
//option to add comment
if (isset($_SESSION['id'])){
echo '<textarea rows=8 name="text" form="comment"></textarea>

<form action="comments.php?id='.$_GET['id'].'" method="POST" id="comment"> 
<input type="hidden" name="token" value="'.$_SESSION['token'].'" />
<input type="hidden" name="story_id" value="'.$_GET['id'].'"/>
<input type="submit" name="submit" value="Submit Comment"/>
</form>';
}


if (isset($_POST['text'])){//if they posted a comment
if($_SESSION['token'] !== $_POST['token']){
	die("Request forgery detected");
}
$stmt = $mysqli->prepare("INSERT INTO comments (story_id,user_id,comment) values (?,?,?)");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

$stmt->bind_param('iis', $_GET['id'],$_SESSION['id'],$_POST['text']);
$stmt->execute();
//echo "INSERT INTO comments (story_id,user_id,comment) values (".$_GET['id'].",".$_SESSION['id'].",".$_POST['text'].")";
}



echo "<br>---------------------------------<br>";
//query for all comments
$stmt = $mysqli->prepare("SELECT `comment`,`time`,`username`,`id`,`comment_id`,`story_id` from comments join Users on (Users.id=comments.user_id) where story_id=?
order by time ASC");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
$stmt->bind_param('i', $_GET['id']);
$stmt->execute();
$stmt->bind_result($comment,$time,$username,$user_id,$comment_id,$story_id);
while($stmt->fetch()){
$time = strtotime($time);
echo $username." at ".date("m/d/Y H:i:s", $time).": ".$comment;
if($user_id==$_SESSION['id']){
	echo "<form action='delete.php' method='POST' style='display: inline;'>
	<input type='hidden' name='comment_id' value='".$comment_id."' />
	<input type='hidden' name='story_id' value='".$story_id."' />
	<input type='submit' value='Delete comment' /> </form> ";
	echo "<form action='edit_comment.php' method='POST' style='display: inline;'>
	<input type='hidden' name='token' value='".$_SESSION['token']."' />
	<input type='hidden' name='comment_id' value='".$comment_id."' />
	<input type='hidden' name='comment' value='".$comment."' />
	<input type='hidden' name='story_id' value='".$story_id."' />
	<input type='submit' value='Edit comment' /> </form> <br>";
	}
else{
echo "<br>";
}
}



?>
</body>
</html>