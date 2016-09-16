<?php
	if (isset($_GET['start'])) {
	echo get_posts($_GET['start'],$_GET['desiredPosts']);
	$_SESSION['posts_start']+=$_GET['desiredPosts'];
	die();
}
?>