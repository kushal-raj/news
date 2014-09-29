<form action="sign_up.php" method="POST">
<input type="text" name="username" placeholder="enter desired username"/>
<input type="password" name="password" placeholder="enter desired password"/>
<input type="submit" name="submit" value="Create account"/>
</form>
<?php
session_start();
$mysqli = new mysqli('localhost', 'news', 'news', 'newssite');
 
if($mysqli->connect_errno) {
	printf("Connection Failed: %s\n", $mysqli->connect_error);
	exit;
}
 if (preg_match('/^[\w_\-]+$/',$_POST['username'])){
 $post_username=$_POST['username'];
$post_password=$_POST['password'];
$crypt_pass=crypt($post_password,"salt n' pepper");

$stmt = $mysqli->prepare("INSERT INTO Users (username,password,points) VALUES (?,?,0)");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}


$stmt->bind_param('ss', $post_username, $crypt_pass);
$stmt->execute();
header ("Location: index.php");
 }

?>