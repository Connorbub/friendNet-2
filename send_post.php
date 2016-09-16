<?php 
include("./inc/connect.inc.php");;
session_start();
if(!isset($_SESSION["user_login"])) {
	$user = "";
} else {
	$user = $_SESSION["user_login"];
}

$post = $_POST['post'];
if ($post != "") {
	$date_added = date("Y-m-d");
	$added_by = $user;
	$user_posted_to = $_SESSION['u'];
	$sqlCommand = "INSERT INTO posts VALUES('','$post','$date_added','$added_by','$user_posted_to')";
	$query = mysqli_query($connect, $sqlCommand) or die(mysqli_error());
} else {
	echo "Please type something to post!";
}
?>