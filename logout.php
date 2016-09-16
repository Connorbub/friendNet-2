<?php include("./inc/connect.inc.php");
session_start();
$user = $_SESSION['user_login'];
$online_query = mysqli_query($connect,"UPDATE users SET online='no' WHERE username='$user'");
date_default_timezone_set('America/Detroit');
				$time = date("g:i:s A");
				$time_query = mysqli_query($connect,"UPDATE users SET time_last_seen='$time' WHERE username='$user'");
session_destroy();
header("Location: index.php");
?>