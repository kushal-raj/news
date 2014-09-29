<!DOCTYPE html>

<html>
<meta charset="utf-8">
<title>Index</title>
<body>
<form action="index.php" method="POST">
<input type="text" name="username" placeholder="username"/>
<input type="password" name="password" placeholder="password"/>
<input type="submit" name="submit" value="Submit"/>
</form>
<a href="home.php">Continue without logging in</a><br>
<a href="sign_up.php">Sign Up</a>

<?php
session_start();
if (isset($_GET['logout'])){
session_destroy();
header ("Location: index.php");
}

//session_destroy(); test if session_destroy() will end the session or just delete its variables
$mysqli = new mysqli('localhost', 'news', 'news', 'newssite');
 
if($mysqli->connect_errno) {
	printf("Connection Failed: %s\n", $mysqli->connect_error);
	exit;
}

$post_username=$_POST['username'];
$post_password=$_POST['password'];
$crypt_pass=crypt($post_password,"salt n' pepper");

$stmt = $mysqli->prepare("select id, username from Users where username=? and password=?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}


$stmt->bind_param('ss', $post_username, $crypt_pass);
$stmt->execute();
$stmt->bind_result($id,$username);
while($stmt->fetch()){
	if (isset($id)){//successful login
	$_SESSION['id']=$id;
	$_SESSION['token'] = substr(md5(rand()), 0, 10);
	header ("Location: home.php");
	}
}
if (empty($id)&&isset($_POST['username'])){
echo "invalid login";
}
?>
</body>
</html>