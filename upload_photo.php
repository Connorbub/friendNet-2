<?php
include("inc/header.inc.php");

if ($user == "") {
  header("Location: index.php");
} else {

}
?>
<link rel="stylesheet" href="./inc/bootstrap/css/bootstrap.css">
<?php
$get_album_name = $_GET['album'];
$get_album_uid = $_GET['uid'];

$get_album_owner = mysqli_query($connect,"SELECT * FROM albums WHERE uid='$get_album_uid'");
$row2 = mysqli_fetch_assoc($get_album_owner);
$album_owner = $row2['created_by'];

if (isset($_POST['uploadpic'])) {
  if ($get_album_name != "") {
    if ($album_owner == $user) {
    	if (((@$_FILES['profilepic']['type']=="image/jpeg") || (@$_FILES['profilepic']['type']=="image/png") || (@$_FILES['profilepic']['type']=="image/gif")) && (@$_FILES['profilepic']['size'] < (1048576)*2)) {
      			$chars = "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890";
      			$rand_dir_name = substr(str_shuffle($chars),0,15);
      			mkdir("userdata/user_photos/$rand_dir_name");

      		if (file_exists("userdata/user_photos/$rand_dir_name/".@$_FILES['profilepic']["name"])) {
      			echo @$_FILES['profilepic']["name"]."already exists! Please try again.";
      		} else {
      			move_uploaded_file(@$_FILES["profilepic"]["tmp_name"], "userdata/user_photos/$rand_dir_name/".$_FILES["profilepic"]["name"]);
      			$profile_pic_name = @$_FILES["profilepic"]["name"];
      			$img_id_before_md5 = "$rand_dir_name/$profile_pic_name";
      			$img_id = md5($img_id_before_md5);
      			$date = date("Y-m-d");

      			if(isset($_POST['description'])) {
      				$description = $_POST['description'];
      			} else {
      				$description = "";
      			}

      			$profile_pic_query = mysqli_query($connect, "INSERT INTO photos VALUES ('','$get_album_uid','$user','$date','$description','../../userdata/user_photos/$rand_dir_name/$profile_pic_name','no','$img_id')");
      			echo "Successfully uploaded your picture!";
      		}
    	} else {
    		echo "Invalid file! Please use a valid format or a smaller file.";
    	}
    } else {
      echo "You don't own this album!";
    }
  } else {
    echo "Please select an album! If you have none, make one <a href='./create_album.php'>here</a>.";
  }
}

?>
<h2>Upload your Photo</h2>
<hr />
<br />
<?php
    if ($get_album_name == "") {
      $get_album_name = "Choose an Album";
    }
?>
<form action="" method="POST" enctype="multipart/form-data">
<div class="dropdown">
  <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><?php echo $get_album_name; ?>
  <span class="caret"></span></button>
  <ul class="dropdown-menu">
  <?php
    $get_albums = mysqli_query($connect,"SELECT * FROM albums WHERE created_by='$user'");
    $album_numrows = mysqli_num_rows($get_albums);
    if ($album_numrows >= 1) {
      while ($row = mysqli_fetch_assoc($get_albums)) {
        $album_name = $row['album_title'];
        $album_uid = $row['uid'];
        echo "<li><a href='./upload_photo.php?album=$album_name&uid=$album_uid'>&bull; $album_name</a></li>";
      }
    } else {
      echo "<h2 style='padding: 4px; margin-bottom: -6px'>You have no albums! Create one <a href='./create_album.php' style='font-size: 18px'>here</a>.</h2>";
    }
  ?>
  <li class="divider"></li>
  <li><a href='./upload_photo.php?album=&uid='>Deselect Album</a></li>
  <li><a href="./create_album.php">Create an Album</a></li>
  </ul>
</div>
<input type="file" name="profilepic" /><br />
<input type="text" name="description" style="width:293px;" placeholder="Description" />
<br />
<input type="submit" name="uploadpic" value="Upload Picture" />
</form>

<style type="text/css">
  * {
    font-family: 'Open Sans', sans-serif !important;
  }

  body {
    background-color: #EFF5F9 !important;
  }

  input[type="file"] {
    margin-bottom: -17px;
  }

  hr {
    margin-bottom: 0px;
    width:100%;
    border-color:#C0C0C0 !important;
  }

  h2 {
    font-family: 'Open Sans', sans-serif;
    font-size: 20px;
    color: #0084C6;
    margin: 0px;
    margin-bottom: -15px;
    font-weight: 600;
  }
</style>
