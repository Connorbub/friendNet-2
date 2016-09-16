<?php include("inc/header.inc.php");

if (isset($_GET['uid'])) {
	$picture = mysqli_real_escape_string($connect, $_GET['uid']);
	if (ctype_alnum($picture)) {
		$check = mysqli_query($connect,"SELECT * FROM photos WHERE uid='$picture'");
		if (mysqli_num_rows($check) >= 1) {
			$get = mysqli_fetch_assoc($check);
			$uid = $get['uid'];
			$username = $get['username'];
			$query = mysqli_query($connect,"SELECT * FROM albums WHERE uid='$picture'");
			$fetch_assoc = mysqli_fetch_assoc($query);
			$album_name = $fetch_assoc['album_title'];
		} else {
			$check2 = mysqli_query($connect,"SELECT * FROM albums WHERE created_by='$user' && uid='$picture'");
			$rows = mysqli_num_rows($check2);
			$fetch = mysqli_fetch_assoc($check2);
			$album_name = $fetch['album_title'];
		}
	}
}

?>
<a href='#' id="goback" onclick='javascript:history.go(-1);'>Go Back &larr;</a>
<h2 style="font-size: 20px;">Photos in this Album</h2>
<hr />
<table>
<?php
$get_photos = mysqli_query($connect,"SELECT * FROM photos WHERE uid='$picture' && removed='no'");
$numrows = mysqli_num_rows($get_photos);
	if($numrows >= 1) {
		$count = 1;
		while ($row = mysqli_fetch_assoc($get_photos)) {
			if ($count%4 == 1) {
				echo "<tr>";
			}
			$count++;
			$id = $row['id'];
			$uid = $row['uid'];
			$username = $row['username'];
			$date_posted = $row['date_posted'];
			$description = $row['description'];
			$image_url = $row['image_url'];
			$img_id = $row['img_id'];

			$md5_image = md5($image_url);

			if (isset($_POST['remove_photo_'.$md5_image.''])) {
				$remove_photo = mysqli_query($connect,"UPDATE photos SET removed='yes' WHERE uid='$uid' && img_id='$img_id'");
				echo "Photo Removed! Refresh to see the change.";
			}
		?>
				<td>
		        <div class='albums'>
		        	<a href="../../photo_closeup.php?img_id=<?php echo $img_id; ?>"><img src="<?php echo $image_url; ?>" height="170" width="170" /></a><br />
		            <?php echo $description; ?>
		        </div>
		<?php
				if ($username == $user) {
		 ?>
		        <center>
			        <form action="./<?php echo $uid; ?>" method="POST">
			        	<input type="submit" name="remove_photo_<?php echo $md5_image; ?>" value="Remove Photo">
			        </form>
		        </center>
			<?php
			}
			 ?>
		    </td>
		<?php
		}

		if ($count%4 == 0) {
			echo "</tr>";
		}
		?>
		</table>
<?php
	} else {
		if ($rows < 1) {
			echo "<h2>No photos in this album!</h2>";
		} else {
			echo "<h2>No photos in this album! Try uploading some <a style='font-size: 16px; color: #0084C6;' href='../../upload_photo.php?album=$album_name&uid=$picture'>here</a>.</h2>";
		}
	}

	?>

	<style>
		#goback {
			background-color: #006FC4;
			border: 1px solid #00508D;
			font-size: 15px;
			color: #FFFFFF;
			padding: 5px;
			margin-bottom: 3px;
			margin-left: 8px;
			text-decoration: none;
		}

		#goback:hover {
			background-color: #0084C6;
			cursor: pointer;
		}

	</style>
