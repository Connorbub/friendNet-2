<?php include("inc/header.inc.php");
if ($user) {

} else {
	die("You must be logged in to access this page!");
}
?>

<?php
	$senddata = @$_POST['submitniche'];

	$nichename = @$_POST['nichename'];

	if ($senddata) {
			if (((@$_FILES['thumbnail']['type']=="image/jpeg") || (@$_FILES['thumbnail']['type']=="image/png") || (@$_FILES['thumbnail']['type']=="image/gif")) && (@$_FILES['thumbnail']['size'] < 1048576243234432432332243223432423)) {
			  			$chars = "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890";
			  			$rand_dir_name = substr(str_shuffle($chars),0,15);
			  			$uid = substr(str_shuffle($chars),0,15);
			  			mkdir("userdata/niche_icons/$rand_dir_name");

			  		if (file_exists("userdata/niche_icons/$rand_dir_name/".@$_FILES['thumbnail']["name"])) {
			  			echo @$_FILES['thumbnail']["name"]."already exists! Please try again.";
			  		} else {
			  			move_uploaded_file(@$_FILES["thumbnail"]["tmp_name"], "userdata/niche_icons/$rand_dir_name/".$_FILES["thumbnail"]["name"]);
			  			$profile_pic_name = @$_FILES["thumbnail"]["name"];
			  			$img_id_before_md5 = "$rand_dir_name/$profile_pic_name";
			  			$img_id = md5($img_id_before_md5);
			  			$date = date("Y-m-d");
			  			$nichename = $nichename;

			  			if(isset($_POST['nichedescription'])) {
			  				$nichedesc = @$_POST['nichedescription'];
			  			} else {
			  				$nichedesc = "";
			  			}

			  			if ($nichename != "") {
								if (strlen($nichename) <= 4) {
					         echo "The name must be more than 4 characters long!";
					      } else {
					         $chars = "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890";
					         $rand_id = substr(str_shuffle($chars),0,15);
					 				 $date = date("Y-m-d");
					         $niche_query = mysqli_query($connect,"INSERT INTO niches VALUES('', '$rand_id', '$user', '$nichename', '$nichedesc', '$date', 'userdata/niche_icons/$rand_dir_name/$profile_pic_name', 'no')");
					         echo "Success! Your niche has been created! Go <a href='niche.php?id=$rand_id'>here</a> to see it!";
					      }
			  			} else {
			  				echo "Please enter a name!";
			  			}
			  		}
				} else {
					echo "Invalid thumbnail! Please use a valid format or a smaller file.";
				}
  }

?>

<h2 style="font-size: 20px;">Create a Niche</h2>
<hr />
<p style="margin-top: 10px; margin-bottom: 15px">A niche is a way for you to share text, images, video, and more with your friends.</p>
<hr />
<form action="create_niche.php" method="POST" enctype="multipart/form-data">
<h1 style="margin-top: 15px;"><u>Choose a Name</u></h1><br />
<input type="text" name="nichename" placeholder="Niche Name" style="font-size:12px; width: 150px;"><br />
<h1 style="margin-top: 15px;"><u>Choose a Description</u></h1><br />
<input type="text" name="nichedescription" placeholder="Niche Description" style="font-size:12px; width: 150px;"><br />
<h1 style="text-decoration:underline;">Choose a Thumbnail</h1><br />
<input type="file" name="thumbnail" /><br /><br />
<input type="submit" name="submitniche" value="Create Niche"><br />
</form>
