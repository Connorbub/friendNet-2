<?php 
include("inc/header.inc.php");
?>
<?php
if (isset($_SESSION['user_login'])) {
	$user = $_SESSION['user_login'];
} else {
	header("Location: index.php");
}

if(isset($_POST['submit'])) {
	if (((@$_FILES['thumbnail']['type']=="image/jpeg") || (@$_FILES['thumbnail']['type']=="image/png") || (@$_FILES['thumbnail']['type']=="image/gif")) && (@$_FILES['thumbnail']['size'] < 1048576243234432432332243223432423)) {
	  			$chars = "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890";
	  			$rand_dir_name = substr(str_shuffle($chars),0,15);
	  			$uid = substr(str_shuffle($chars),0,15);
	  			mkdir("userdata/album_thumbnails/$rand_dir_name");

	  		if (file_exists("userdata/album_thumbnails/$rand_dir_name/".@$_FILES['thumbnail']["name"])) {
	  			echo @$_FILES['thumbnail']["name"]."already exists! Please try again.";
	  		} else {
	  			move_uploaded_file(@$_FILES["thumbnail"]["tmp_name"], "userdata/album_thumbnails/$rand_dir_name/".$_FILES["thumbnail"]["name"]);
	  			$profile_pic_name = @$_FILES["thumbnail"]["name"];
	  			$img_id_before_md5 = "$rand_dir_name/$profile_pic_name";
	  			$img_id = md5($img_id_before_md5);
	  			$date = date("Y-m-d");
	  			$album_title = $_POST['album_title'];

	  			if(isset($_POST['album_description'])) {
	  				$description = @$_POST['album_description'];
	  			} else {
	  				$description = "";
	  			}

	  			if ($album_title != "") {
	  				$profile_pic_query = mysqli_query($connect, "INSERT INTO albums VALUES ('','$album_title','$description','$user','$date','$uid','no','../userdata/album_thumbnails/$rand_dir_name/$profile_pic_name')");
	  				echo "Successfully created your album!";
	  			} else {
	  				echo "Please enter a title!";
	  			}
	  		}
		} else {
			echo "Invalid thumbnail! Please use a valid format or a smaller file.";
		}
}

?>

<h2 style='text-decoration:underline;'>Create an Album</h2>

<form action='create_album.php' method='POST' enctype="multipart/form-data">
<input type='text' name='album_title' size='30' placeholder='Album Title' style="width:304px;"/><br />
<textarea cols='41' rows='5' name='album_description' placeholder='Album Description'></textarea><br />
<h1 style="text-decoration:underline;">Choose a Thumbnail</h1>
<input type="file" name="thumbnail" /><br />
<input type='submit' name='submit' value='Create Album'>
</form>