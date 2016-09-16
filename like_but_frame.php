<link rel="stylesheet" type="text/css" href="./css/style.css" />
<?php
	include("./inc/connect.inc.php");

	session_start();
	if(!isset($_SESSION["user_login"])) {
		$user = "";
	} else {
		$user = $_SESSION["user_login"];
	}

	$id = "";
	if (isset($_GET['uid'])) {
		$uid = mysqli_real_escape_string($connect, $_GET['uid']);
		if (ctype_alnum($uid)) {
			$get_likes = mysqli_query($connect,"SELECT * FROM likes WHERE uid='$uid'");
			if (mysqli_num_rows($get_likes) === 1) {
				$get = mysqli_fetch_assoc($get_likes);
				$uid = $get['uid'];
				$total_likes = $get['total_likes'];
			} else {
				die("Error...");	
			}
		if (isset($_POST['likebutton_'])) {
			if ($user != "") {
				$total_likes = $total_likes + 1;
				$like = mysqli_query($connect,"UPDATE likes SET total_likes='$total_likes' WHERE uid='$uid'");
				$user_likes = mysqli_query($connect,"INSERT INTO user_likes VALUES('','$user','$uid')");
				header("Location: like_but_frame.php?uid=$uid");
			} else {
				die("Please Log In!");
			}
		}
		if (isset($_POST['unlikebutton_'])) {
			$total_likes = $total_likes - 1;
			$like = mysqli_query($connect,"UPDATE likes SET total_likes='$total_likes' WHERE uid='$uid'");
			$remove_user = mysqli_query($connect,"DELETE FROM user_likes WHERE username='$user' && uid='$uid'");
			header("Location: like_but_frame.php?uid=$uid");
		}
	}
}

$check_for_likes = mysqli_query($connect,"SELECT * FROM user_likes WHERE username='$user' && uid='$uid'");
$numrows_likes = mysqli_num_rows($check_for_likes);
if ($numrows_likes >= 1) {
	echo '
	<form action="like_but_frame.php?uid='.$uid.'" method="POST">
		<input type="submit" name="unlikebutton_'.$id.'" value="Unlike">
		<div style="display:inline;">
			'.$total_likes.'';
			if ($total_likes == 1) {
				echo " like";
			}
			else {
				echo " likes";	
			}
			echo '
		</div>
	</form>
	';
} else if($numrows_likes == 0) {
	echo '
	<form action="like_but_frame.php?uid='.$uid.'" method="POST">
		<input type="submit" name="likebutton_'.$id.'" value="Like">
		<div style="display:inline;">
			'.$total_likes.'';
			if ($total_likes == 1) {
				echo " like";
			}
			else {
				echo " likes";	
			}
			echo '
		</div>
	</form>
	';
}
?>