<?php include("inc/header.inc.php"); include("inc/connect.inc.php"); ?>
<?php
	$friend_requests = mysqli_query($connect,"SELECT * FROM friend_requests WHERE user_to='$user'");
	$numrows = mysqli_num_rows($friend_requests);
	if ($numrows == 0) {
		echo "<h2 style='text-decoration: underline;'>You have no friend requests at this time...</h2>";
		$user_from = "";
	} else {
		while ($get_row = mysqli_fetch_assoc($friend_requests)) {
			$id = $get_row['id'];
			$user_to = $get_row['user_to'];
			$user_from = $get_row['user_from'];
?>
<?php
	$add_friend_check = "";
	$add_friend_row = "";
	$friend_array = "";
	if (isset($_POST['acceptrequest'.$user_from])) {
		$add_friend_check = mysqli_query($connect,"SELECT friend_array FROM users WHERE username='$user'");
		$add_friend_row = mysqli_fetch_assoc($add_friend_check);
		$friend_array = $add_friend_row['friend_array'];
		$friendArray_explode = explode(",",$friend_array);
		$friendArray_count = count($friendArray_explode);


		$add_friend_check_friend = mysqli_query($connect,"SELECT friend_array FROM users WHERE username='$user_from'");
		$add_friend_row_friend = mysqli_fetch_assoc($add_friend_check_friend);
		$friend_array_friend = $add_friend_row_friend['friend_array'];
		$friendArray_explode_friend = explode(",",$friend_array_friend);
		$friendArray_count_friend = count($friendArray_explode_friend);

		if ($friend_array == "") {
			$friendArray_count = count(NULL);
		}

		if ($friend_array_friend == "") {
			$friendArray_count_friend = count(NULL);
		}

		if ($friendArray_count == NULL) {
			$add_friend_query = mysqli_query($connect,"UPDATE users SET friend_array=CONCAT(friend_array,'$user_from') WHERE username='$user'");
		}

		if ($friendArray_count_friend == NULL) {
			$add_friend_query_friend = mysqli_query($connect,"UPDATE users SET friend_array=CONCAT(friend_array,'$user_to') WHERE username='$user_from'");
		}

		if ($friendArray_count >= 1) {
			$add_friend_query = mysqli_query($connect,"UPDATE users SET friend_array=CONCAT(friend_array,',$user_from') WHERE username='$user'");
		}

		if ($friendArray_count_friend >= 1) {
			$add_friend_query_friend = mysqli_query($connect,"UPDATE users SET friend_array=CONCAT(friend_array,',$user_to') WHERE username='$user_from'");
		}

		$delete_request = mysqli_query($connect,"DELETE FROM friend_requests WHERE user_to='$user_to' && user_from='$user_from'");
		echo "<h1>You are now friends!</h1>";
		header("Location:friend_requests.php");
	}

	if (isset($_POST["ignorerequest".$user_from])) {
		$ignore_request = mysqli_query($connect,"DELETE FROM friend_requests WHERE user_to='$user_to' && user_from='$user_from'");
		echo "<h1>Request ignored.</h1>";
		header("Location:friend_requests.php");
	}
?>

<form action="friend_requests.php" method="POST" name="friendform">
<h2><?php echo "<a href='".$user_from."''>".$user_from."</a>";  ?> wants to be friends.</h2>
<input type="submit" style="margin-left: 7px;" name="acceptrequest<?php echo $user_from; ?>" value="Accept Request">
<input type="submit" style="margin-left: 7px;" name="ignorerequest<?php echo $user_from; ?>" value="Ignore Request">
</form><br />

<?php 
}
}
?>

<style>
form[name="friendform"] {
	background-color: #FFFFFF;
	border: 1px solid #DCE5EE;
	padding: 5px;
	width: 260px;
}

form[name="friendform"]:hover {
	background-color: #F3F6F9;
}

form[name="friendform"] a {
	font-size: 18px;
	color: #0084C6;
	text-decoration: none;
}

form[name="friendform"] a:hover {
	text-decoration: underline;
}
</style>