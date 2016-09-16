<?php
	$connect = mysqli_connect("localhost","root","") or die("Couldn't connect to the SQL server :(");
	mysqli_select_db($connect, "socialnetwork") or die("Couldn't select the database :(");
?>