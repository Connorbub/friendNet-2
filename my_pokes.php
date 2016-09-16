<?php 
include("./inc/header.inc.php"); include("./inc/connect.inc.php");
?>
<?php

$poke_num = 0;

if (isset($_SESSION['user_login'])) {
	$check_for_pokes = mysqli_query($connect,"SELECT * FROM pokes WHERE user_to='$user'");
	$poke_num = mysqli_num_rows($check_for_pokes);
	if ($poke_num <= 0) {
			echo "<h2 style='text-decoration:underline;'>You don't have any pokes...</h2>";
	} else {
		while($poke = mysqli_fetch_assoc($check_for_pokes)) {
			$user_to = $poke['user_to'];
			$user_from = $poke['user_from'];

			if (isset($_POST["ignorepoke_".$user_from])) {
				$ignore_request = mysqli_query($connect,"DELETE FROM pokes WHERE user_to='$user' && user_from='$user_from'");
				echo "<h1>Poke ignored.</h1>";
				header("Location:my_pokes.php");
			}

			if (@$_POST['poke_'.$user_from]) {
				$delete_poke = mysqli_query($connect,"DELETE FROM pokes WHERE user_from='$user_from' && user_to='$user_to'");
				$create_new_poke = mysqli_query($connect,"INSERT INTO pokes VALUES ('','$user','$user_from')");
				header("Location: my_pokes.php");
			} else {
				echo "
				<form action='my_pokes.php' method='POST' name='pokeform'>
				<h1>You have been poked by <a href='$user_from'>$user_from</a>!</h1><br /><hr style='width: 100% !important;'  /><br />
				<input type='submit' name='poke_$user_from' style='height:30px; font-size: 16px; padding: 2px;' value='Poke Back'>
				<input type='submit' name='ignorepoke_$user_from' style='height:30px; font-size: 16px; margin-left: 6px; padding: 2px;' value='Ignore'>
				</form><br />";
			}
		}
	}
} else {
	header("Location: index.php");
}
?>

<style>
form[name="pokeform"] {
	background-color: #FFFFFF;
	border: 1px solid #DCE5EE;
	padding: 5px;
	width: 260px;
}

form[name="pokeform"]:hover {
	background-color: #F3F6F9;
}

form[name="pokeform"] a {
	font-size: 16px;
	color: #000000;
	text-decoration: none;
}

form[name="pokeform"] a:hover {
	text-decoration: underline;
}

form[name="pokeform"] h1 {
	font-size: 16px;
}
</style>