<?php
include("./inc/header.inc.php"); include("./inc/connect.inc.php");
if (isset($_SESSION['user_login'])) {
	date_default_timezone_set('America/Detroit');
	$time = date("g:i:s A");
	$date = date("Y-m-d");
	$online_query = mysqli_query($connect,"UPDATE users SET online='yes' WHERE username='$user'");
	$time_query = mysqli_query($connect,"UPDATE users SET time_last_seen='$time' WHERE username='$user'");
	$date_query = mysqli_query($connect,"UPDATE users SET date_last_seen='$date' WHERE username='$user'");
} else {
	header("Location: index.php");
}

?>
