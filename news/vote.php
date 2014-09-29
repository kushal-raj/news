<?php
require 'lib.php';

$story_id=$_GET['id'];
if (isset($_GET['direction'])){
$stmt = $mysqli->prepare("select count(vote_id) from votes where user_id=? and story_id=?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
$stmt->bind_param('ii', $_SESSION['id'],$_GET['id']);
$stmt->execute();
$stmt->bind_result($count);
while($stmt->fetch()){
	if ($count==0){
	$run=true;
	
	}
}
if(isset($run)){
	$newq = $mysqli->prepare("INSERT INTO votes (story_id,user_id,points) values (?,?,?)");
	if(!$newq){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
	}
	$points=0;
	if($_GET['direction']=="up"){
	$points=1;
	}
	else{
	$points=-1;
	}
	//echo "INSERT INTO votes (story_id,user_id,points) values (".$_GET['id'].",".$_SESSION['id'].",".$points.")";
	$newq->bind_param('iii', $_GET['id'],$_SESSION['id'],$points);
	$newq->execute();
	$newq->close();
	$newq2 = $mysqli->prepare("update stories SET score=score+? where story_id=? ");
	if(!$newq2){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
	}
	$newq2->bind_param('ii',$points, $_GET['id']);
	$newq2->execute();
	
	}
}
$newq3 = $mysqli->prepare("select score from stories where story_id=? ");
	if(!$newq3){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
	}
	$newq3->bind_param('i', $_GET['id']);
	$newq3->execute();
	$newq3->bind_result($points);
	while($newq3->fetch()){
	echo $points;
}



?>