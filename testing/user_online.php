<?php include("../inc/connect.inc.php"); ?>
<?php

session_start();
$session = session_id();
$time = time();
$time_check = $time-600;

$sql = "SELECT * FROM user_online_data WHERE session='$session'";
$result = mysqli_query($connect,$sql);

$count = mysqli_num_rows($result);
if ($count == 0) {
	$sql1 = "INSERT INTO user_online_data VALUES ('','$session','$time')";
	$result1 = mysqli_query($connect,$sql1);
} else {
	$sql2 = "UPDATE user_online_data SET time='$time' WHERE session='$session'";
	$result2 = mysqli_query($connect,$sql2);
}

$sql3 = "SELECT * FROM user_online_data";
$result3 = mysqli_query($connect,$sql3);
$count_user_online = mysqli_num_rows($result3);
echo "User online: $count_user_online";

$sql4 = "DELETE FROM user_online_data WHERE time<$time_check";
$result4 = mysqli_query($connect,$sql4);

?>