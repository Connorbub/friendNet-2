<?php
include("./inc/connect.inc.php") ;
session_start();
if(!isset($_SESSION["user_login"])) {
	$user = "";
} else {
	$user = $_SESSION["user_login"];
}

if (isset($_SESSION['user_login'])) {
	date_default_timezone_set('America/Detroit');
	$time = date("g:i:s A");
	$date = date("Y-m-d");
	$online_query = mysqli_query($connect,"UPDATE users SET online='yes' WHERE username='$user'");
	$time_query = mysqli_query($connect,"UPDATE users SET time_last_seen='$time' WHERE username='$user'");
	$date_query = mysqli_query($connect,"UPDATE users SET date_last_seen='$date' WHERE username='$user'");
} else {

}

$get_unread_query = mysqli_query($connect,"SELECT opened FROM  pvt_messages WHERE user_to='$user' && opened='no'");
$unread_numrows = mysqli_num_rows($get_unread_query);

$get_requests_query = mysqli_query($connect,"SELECT id FROM friend_requests WHERE user_to='$user'");
$requests_numrows = mysqli_num_rows($get_requests_query);

$get_pokes_query = mysqli_query($connect,"SELECT id FROM pokes WHERE user_to='$user'");
$pokes_numrows = mysqli_num_rows($get_pokes_query);

$total_numrows = $unread_numrows + $requests_numrows + $pokes_numrows;

?>
<!doctype html>
<html>
	<head>
		<title>friendNet</title>
		<link rel="icon" type="image/ico" href="/friendNet/img/favicon.ico">
		<link rel="stylesheet" type="text/css" href="/friendnet/css/style.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
		<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
		<script src="./inc/sticky/jquery.sticky.js"></script>
		<script src="js/main.js" type="text/javascript"></script>
	</head>
	<body>
		<div class='headerMenu'>
			<div id="wrapper">
				<?php if(!$user) {
					echo '<div class="logo">
						<a href="index.php"><img src="/friendNet/img/friendNetLogo.png" /></a>
					</div>';
				} else {
				echo '<div class="logo">
					<a href="home.php"><img src="/friendNet/img/friendNetLogo.png" /></a>
				</div>';
				}
				?>
				<div class="search_box">
					<form action="search.php" method="GET" id="search">
						<input type="text" name="q" size="60" placeholder="Search...">
					</form>
				</div>
				<?php
				if(!$user) {
				echo '<div id="notloggedinmenu">
					<a href="http://localhost/friendNet/" class="menuItem" />Home</a>
					<a href="/friendNet/about.php" class="menuItem" />About</a>
					<a href="http://localhost/friendNet/" class="menuItem" />Sign Up</a>
					<a href="http://localhost/friendNet/" class="menuItem" />Sign In</a>
				</div>';
			} else {
				//I'll create original images for these at some point to save space
				echo '<div id="menu">
					<a href="/friendNet/home.php" class="menuItem" />Home</a>
					<a href="/friendNet/'.$user.'" class="menuItem" />Profile</a>
					<a href="/friendNet/account_settings.php" class="menuItem" />Account Settings</a>
					<div class="dropdown">
				  <a href="#" class="menuItem" onclick="myFunction()">More ('.$total_numrows.')</a>
				  	<div id="myDropdown" class="dropdown-content">
						<a href="/friendNet/my_messages.php" />Inbox ('.$unread_numrows.')</a>
						<a href="/friendNet/friend_requests.php" />Friend Requests ('.$requests_numrows.')</a>
						<a href="/friendNet/my_pokes.php" />Pokes ('.$pokes_numrows.')</a>
						<a href="/friendNet/create_niche.php" />Create a Niche</a>
					  </div>
					</div>
					<a href="/friendNet/logout.php" id="logout" class="menuItem"/>Log Out</a>
				</div>';

				date_default_timezone_set('America/Detroit');
				$time = date("g:i:s A");
				$date = date("Y-m-d");
				$time_query = mysqli_query($connect,"UPDATE users SET time_last_seen='$time' WHERE username='$user'");
				$date_query = mysqli_query($connect,"UPDATE users SET date_last_seen='$date' WHERE username='$user'");

			}
				?>
			</div>
		</div>
		<div class="wrapper">
		<script language="javascript">
					/* When the user clicks on the button,
			toggle between hiding and showing the dropdown content */
			function myFunction() {
				document.getElementById("myDropdown").classList.toggle("show");
			}

			// Close the dropdown menu if the user clicks outside of it
			window.onclick = function(event) {
			if (!event.target.matches('.menuItem')) {
				var dropdowns = document.getElementsByClassName("dropdown");
				var i;
				for (i = 0; i < dropdowns.length; i++) {
					var openDropdown = dropdowns[i];
					if (openDropdown.classList.contains('show')) {
						openDropdown.classList.remove('show');
					}
				}
			}
			}

			window.onbeforeunload = function () {
				$.get("setoffline.php");
				return null;
			}
		</script>
