<?php include("./inc/header.inc.php"); ?>
<?php
if(@$_POST['unclose']) {
	$user_login = $_POST['user_login'];
	$password_login = $_POST['password_login'];
	$password_login_md5 = md5($password_login);
	$sql = mysqli_query($connect, "SELECT * FROM users WHERE username='$user_login' AND password='$password_login_md5' LIMIT 1");
	$numrows = mysqli_num_rows($sql);
	if ($numrows >=1) {
		$get = mysqli_fetch_assoc($sql);
		$closed = $get['closed'];
		if ($closed != "no") {
			$query = mysqli_query($connect,"UPDATE users SET closed='no' WHERE username='$user_login'");
			echo "Your account has been unclosed! <a href='index.php'>Log in</a> to continue using friendNet!";	
		} else {
			echo "No need to unclose your account, it's still open!";	
		}
	} else {
		echo "That information is incorrect! Please try again.";
	}
}	
?>
<h2 style='text-decoration: underline;'>Unclose your Account</h2>
<form action="unclose.php" method="POST">
<input type="text" name="user_login" placeholder="Username"><br />
<input type="password" name="password_login"placeholder="Password"><br />
<input type="submit" name="unclose" value="Unclose">
</form>