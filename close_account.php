<?php
include("./inc/header.inc.php");

if($user) {
	if(isset($_POST['no'])) {
		header("Location: account_settings.php");
	}
	if(isset($_POST['yes'])) {
		$query = mysqli_query($connect,"UPDATE users SET closed='yes' WHERE username='$user'");
		echo "Your account has been closed!";
		session_destroy();
		header("Location: index.php");
	}
} else {
	die("You must log in to access this!");	
}

?>
<br />
<center>
<form action="close_account.php" method="POST">
<h2>Are you <u style='font-size: 18px;'>sure</u> you want to close your account?</h2>
<input type="submit" name="no" value="No, take me back!">
<input type="submit" name="yes" value="Yes, I'm sure.">
</form>
</center>