<?php include("./inc/header.inc.php"); include("./inc/connect.inc.php"); ?>
<?php 
if (isset($_GET['u'])) {
	$username = mysqli_real_escape_string($connect, $_GET['u']);
	if (ctype_alnum($username)) {
		$check = mysqli_query($connect,"SELECT username, first_name FROM users WHERE username='$username'");
		if (mysqli_num_rows($check)===1) {
			$get = mysqli_fetch_assoc($check);
			$username = $get['username'];
			
			if ($username != $user) {
				if (isset($_POST['submit'])) {
					$msg_title = strip_tags(@$_POST['msg_title']);
					$msg_body = strip_tags(@$_POST['msg_body']);
					$date = date("Y-m-d");
					$opened = "no";
					$deleted = "no";

					if ($msg_body == "") {
						echo "Please write a message!";
					} else {
						$send_msg = mysqli_query($connect,"INSERT INTO pvt_messages VALUES ('','$user','$username','$msg_title','$msg_body','$date','$opened','$deleted')");
						echo "Your message has been sent!";
					}
				}
				echo "

				<form action='send_msg.php?u=$username' method='POST'>
				<h2 style='text-decoration:underline;'>Compose a Message to $username:</h2>
				<input type='text' name='msg_title' size='30' placeholder='Enter a Title...' /><br />
				<textarea cols='70' rows='12' name='msg_body' placeholder='Enter a Message...'></textarea><br />
				<input type='submit' name='submit' value='Send Message'>
				</form>

				";
			} else {
				header("Location: $user");
			}
		}
	}
}
?>