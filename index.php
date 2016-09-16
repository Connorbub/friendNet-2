<?php include("./inc/header.inc.php"); include("./inc/connect.inc.php"); ?>
<?php

if(isset($_SESSION['user_login'])) {
	header("Location: home.php");
} else {

}

$reg = @$_POST['reg'];
//declaring variables to prevent errors
$fn = ""; //First Name
$ln = ""; //Last Name
$un = ""; //Username
$em = ""; //Email
$em2 = ""; //Email 2
$pswd = ""; //Password
$pswd2 = ""; // Password 2
$d = ""; // Sign up Date
$u_check = ""; // Check if username exists
//registration form
$fn = strip_tags(@$_POST['fname']);
$ln = strip_tags(@$_POST['lname']);
$un = strip_tags(@$_POST['username']);
$em = strip_tags(@$_POST['email']);
$em2 = strip_tags(@$_POST['email2']);
$pswd = strip_tags(@$_POST['password']);
$pswd2 = strip_tags(@$_POST['password2']);
$d = date("Y-m-d"); // Year - Month - Day

if ($reg) {
	if ($em==$em2) {
		// Check if user already exists
		$u_check = mysqli_query($connect,"SELECT username FROM users WHERE username='$un'");
		// Count the amount of rows where username = $un
		$check = mysqli_num_rows($u_check);
		//Check email
		$e_check = mysqli_query($connect, "SELECT email FROM users WHERE email='$em'");
		$email_check = mysqli_num_rows($e_check);
		if($check==0) {
			if($email_check == 0) {
			//check all of the fields have been filed in
			if ($fn&&$ln&&$un&&$em&&$em2&&$pswd&&$pswd2) {
				// check that passwords match
				if ($pswd==$pswd2) {
					// check the maximum length of username/first name/last name does not exceed 25 characters
					if (strlen($un)>25||strlen($fn)>25||strlen($ln)>25) {
						echo "The maximum limit for username/first name/last name is 25 characters!";
					} else {
						// check the maximum length of password does not exceed 25 characters and is not less than 5 characters
						if (strlen($pswd)>30||strlen($pswd)<5) {
							echo "Your password must be between 5 and 30 characters long!";
						} else {
							//encrypt password and password 2 using md5 before sending to database
							$pswd = md5($pswd);
							$pswd2 = md5($pswd2);
							$time = date('H:i:s');
							$query = mysqli_query($connect,"INSERT INTO users VALUES ('','$un','$fn','$ln','$em','$pswd','$d','0','','','','yes','$time','no',$d)");
							die("<h2>Welcome to friendNet!</h2><h1 style='margin-left:10px;'>Log in to your account to get started ...</h1>");
						}
					}
				} else {
					echo "Your passwords don't match!";
				}
			} else {
				echo "Please fill in all of the fields";
			}
		} else {
			echo "It looks like someone is already using that email!";
		}
	} else {
		echo "Username already taken ...";		
	} 
} else {
	echo "Your E-mails don't match!";
}
}

//User Login
if (isset($_POST["user_login"]) && isset($_POST["password_login"])) {
	$user_login = preg_replace('#[^A-Za-z0-9]#i', '', $_POST["user_login"]);
	$password_login = preg_replace('#[^A-Za-z0-9]#i', '', $_POST["password_login"]);
	$password_login_md5 = md5($password_login);
	$sql = mysqli_query($connect, "SELECT id FROM users WHERE username='$user_login' AND password='$password_login_md5' AND closed='no' LIMIT 1");
	$userCount = mysqli_num_rows($sql);
	if($userCount == 1) {
		while($row = mysqli_fetch_array($sql)) {
			$id = $row["id"];
		}
		$_SESSION["user_login"] = $user_login;
		header("Location: home.php");
		exit();
	} else {
		echo "That information is incorrect, please try again. If your account has been closed, unclose it <a href='unclose.php'>here</a>.";
	}
}
?>
		<table>
			<tr>
				<td width="60%" valign="top">
				<h2>Already joined us? Sign in below!</h2>
				<form action="index.php" method="POST">
					<input type="text" name="user_login" size="25" placeholder="Username" /><br /><br />
					<input type="password" name="password_login" size="25" placeholder="Password" /><br /><br />
					<input type="submit" name="login" value="Log In" />
				</form>
				</td>
				<td width="40%" valign="top">
					<h2>Sign Up Below!</h2>
					<form action="index.php" method="POST">
						<input type="text" name="fname" size="25" placeholder="First Name" /><br /><br />
						<input type="text" name="lname" size="25" placeholder="Last Name" /><br /><br />
						<input type="text" name="username" size="25" placeholder="Username" /><br /><br />
						<input type="text" name="email" size="25" placeholder="Email" /><br /><br />
						<input type="text" name="email2" size="25" placeholder="Confirm Email" /><br /><br />
						<input type="password" name="password" size="25" placeholder="Password" /><br /><br />
						<input type="password" name="password2" size="25" placeholder="Confirm Password" /><br /><br />
						<input type="submit" name="reg" value="Sign Up" />
					</form>
				</td>
			</tr>
		</table>
<?php include("./inc/footer.inc.php"); ?>