<!DOCTYPE html>

<html>
<meta charset="utf-8">
<title>Index</title>
<body>
<script>
function up(id){
var xmlhttp;
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
  else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.open("GET","vote.php?id="+id+"&direction=up",false);
xmlhttp.send();
document.getElementById(id).innerHTML="("+xmlhttp.responseText+" points)";
}
function down(id){
var xmlhttp;
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
  else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.open("GET","vote.php?id="+id+"&direction=down",false);
xmlhttp.send();
document.getElementById(id).innerHTML="("+xmlhttp.responseText+" points)";
}
</script>
<a href="index.php?logout=true">Log out</a>
<br>---------------------------------<br>
<?php
if (isset($_SESSION['id'])){
echo '<textarea rows=2 name="title" form="post" placeholder="Title"></textarea>
<textarea rows=2 name="link" form="post" placeholder="Link"></textarea>
<textarea rows=2 name="commentary" form="post" placeholder="Commentary"></textarea>

<form action="home.php" method="POST" id="post"> 
<!--<input type="textarea" style="width:200px;height:200px; "name="comment" placeholder="type comment here"/>-->
<input type="submit" name="submit" value="Submit Post"/>
</form>';
}
?>
<br>---------------------------------<br>
<?php
session_start();
$mysqli = new mysqli('localhost', 'news', 'news', 'newssite');
 
if($mysqli->connect_errno) {
	printf("Connection Failed: %s\n", $mysqli->connect_error);
	exit;
}

if (isset($_POST['title'])){//if they posted

$stmt = $mysqli->prepare("INSERT INTO stories (user_id,description,link,title,score) values (?,?,?,?,0)");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

$stmt->bind_param('isss',$_SESSION['id'],$_POST['commentary'],$_POST['link'],$_POST['title']);
$stmt->execute();
}




$stmt = $mysqli->prepare("select title,score,link,description,username,story_id, user_id
from stories join Users on(stories.user_id=Users.id)
 order by score desc");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
$stmt->execute();
$stmt->bind_result($title,$score,$link,$description,$username,$story_id,$user_id);
while($stmt->fetch()){
echo "<h2 style='font-size:small; display:inline' id=".$story_id."> (".$score." points) </h2>\n";
echo "<a href='".$link."'  >".$title."</a>\n" ;
if (isset($_SESSION['id'])){
echo "<button onclick=up(".$story_id.") >Upvote</button>\n";
echo "<button onclick=down(".$story_id.") >Downvote</button>\n";
}
echo "<a href='comments.php?id=".$story_id."'   >Comments</a>\n" ;
if ($user_id==$_SESSION['id']){
 echo "<form action='delete_post.php' method='POST' style='display: inline;'>
	<input type='hidden' name='story_id' value='".$story_id."' />
	<input type='submit' value='Delete post' /> </form><br>";
}
else {
echo "<br>";
}

}
?>
</body>
</html>

