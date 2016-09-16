<?php
	include("inc/header.inc.php");
?>
<h2>Create Your Like Button</h2><hr /><br />
<form action="create_like_button.php" method="POST">
	<input type="text" name="like_button_url" placeholder="Enter the URL of the page you want to like..." style="width: 310px !important;">
    <input type="submit" name="create" value="Create" />
</form>
<?php
if(isset($_POST['like_button_url'])) {
	$like_button_url = strip_tags(@$_POST['like_button_url']);
	$username = $user;
	$date = date("Y-m-d");
	$uid = rand(4313243,9999999999999999999999999999999999999999999999999999999999999999999999999999);
	$uid = md5($uid);
	
	$b_check = mysqli_query($connect,"SELECT page_url FROM like_buttons WHERE page_url='$like_button_url'");
	$numrows_check = mysqli_num_rows($b_check);
	if ($numrows_check >= 1) {
		echo "This page is already in our database!";
	} else {
		$like_button_url2 = strstr($like_button_url, 'http://');
		$like_button_url3 = strstr($like_button_url, 'https://');
		$like_button_url4 = strstr($like_button_url, 'www.');
		if ($like_button_url2||$like_button_url3||$like_button_url4) {
			$create_button = mysqli_query($connect,"INSERT INTO like_buttons VALUES ('','$username','$like_button_url','$date','$uid')");
			$insert_like = mysqli_query($connect,"INSERT INTO likes VALUES ('','$username','0','$uid')");
			echo "
			<div style='width: 382px; height: 250px; border: 1px solid #CCCCCC; background-color: #FFFFFF;'>
			<h2>Embed this Button:</h2>
			&lt;iframe src='http://localhost/friendNet/like_but_frame.php?uid=$uid' style='border: 0px; height: 40px; width: 200px;'&gt;
			&lt;/iframe&gt;
			</div>
			";
		} else {
			$like_button_url = "http://www.".$like_button_url.".com";
			$create_button = mysqli_query($connect,"INSERT INTO like_buttons VALUES ('','$username','$like_button_url','$date','$uid')");
			$insert_like = mysqli_query($connect,"INSERT INTO likes VALUES ('','$username','0','$uid')");
			echo "
			<div style='width: 382px; height: 250px; border: 1px solid #CCCCCC; background-color: #FFFFFF;'>
			<h2>Embed this Button:</h2>
			&lt;iframe src='http://localhost/friendNet/like_but_frame.php?uid=$uid' style='border: 0px; height: 40px; width: 200px;'&gt;
			&lt;/iframe&gt;
			</div>
			";
		}
	}
}
?>