<?php include("./inc/header.inc.php"); include("./inc/connect.inc.php"); 
if ($user) {

} else {
	die("You must be logged in to access this page!");
}
?>
<?php
	$senddata = @$_POST['senddata'];
	
	$old_password = @$_POST['oldpassword'];
	$new_password = @$_POST['newpassword'];
	$repeat_password = @$_POST['newpassword2'];

	if ($senddata) {
  		$password_query = mysqli_query($connect, "SELECT * FROM users WHERE username='$user'");
  		while ($row = mysqli_fetch_assoc($password_query)) {
        	$db_password = $row['password'];
        	$old_password_md5 = md5($old_password);
        	if ($old_password_md5 == $db_password) {
         		if ($new_password == $repeat_password) {
           			if (strlen($new_password) <= 4) {
             			echo "Sorry! But your password must be more than 4 character long!";
            		} else {
           				$new_password_md5 = md5($new_password);
           				$password_update_query = mysqli_query($connect,"UPDATE users SET password='$new_password_md5' WHERE username='$user'");
           				echo "Success! Your password has been updated!";
            		}
         		} else {
          			echo "Your two new passwords don't match!";
         		}
        	} else {
         		echo "The old password is incorrect!";
        	}
  		}
   	} else {
   		echo "";
  	}

  	$updateinfo = @$_POST['updateinfo'];
  	$get_info = mysqli_query($connect, "SELECT first_name, last_name, bio FROM users WHERE username='$user'");
  	$get_row = mysqli_fetch_assoc($get_info);
  	$db_firstname = $get_row['first_name'];
  	$db_lastname = $get_row['last_name'];
  	$db_bio = $get_row['bio'];

  	if ($updateinfo) {
  		$firstname = strip_tags(@$_POST['fname']);
  		$lastname = strip_tags(@$_POST['lname']);
  		$bio = strip_tags(@$_POST['aboutyou']);

  		if (strlen($firstname) < 3) {
  			echo "Your first name must be more 3 or more characters long!";
  		} else {
  			if (strlen($lastname) < 3) {
  			echo "Your last name must be more 3 or more characters long!";
  			} else {
  				$info_submit_query = mysqli_query($connect,"UPDATE users SET first_name='$firstname', last_name='$lastname', bio='$bio' WHERE username='$user'");
  				echo "Your information has been updated!";
  				header("Location: $user");
  			} 
  		}
  	}

  	$check_pic = mysqli_query($connect,"SELECT profile_pic FROM users WHERE username='$user'");
  	$get_pic_row = mysqli_fetch_assoc($check_pic);
  	$profile_pic_db = $get_pic_row['profile_pic'];
  	
  	if($profile_pic_db == "") {
  		$profile_pic = "img/default_pic.jpg";
  	} else {
  		$profile_pic = "userdata/profile_pics/".$profile_pic_db;
  	}

  	if (isset($_FILES['profilepic'])) {
  		if (((@$_FILES['profilepic']['type']=="image/jpeg") || (@$_FILES['profilepic']['type']=="image/png") || (@$_FILES['profilepic']['type']=="image/gif")) && (@$_FILES['profilepic']['size'] < 1048576)) {
  			$chars = "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890";
  			$rand_dir_name = substr(str_shuffle($chars),0,15);
  			mkdir("userdata/profile_pics/$rand_dir_name");

  			if (file_exists("userdata/profile_pics/$rand_dir_name/".@$_FILES['profilepic']["name"])) {
  				echo @$_FILES['profilepic']["name"]."already exists! Please try again.";
  			} else {
  				move_uploaded_file(@$_FILES["profilepic"]["tmp_name"], "userdata/profile_pics/$rand_dir_name/".$_FILES["profilepic"]["name"]);
  				$profile_pic_name = @$_FILES["profilepic"]["name"];
  				$profile_pic_query = mysqli_query($connect, "UPDATE users SET profile_pic='$rand_dir_name/$profile_pic_name' WHERE username='$user'");
  				header("Location: account_settings.php");
  				echo "Successfully uploaded your picture!";
  			}
  		} else {
  			echo "Invalid File! Your image must be no larger than 1MB and must be a JPG, PNG, or GIF!";
  		}
  	}

?>
<h2>Edit your Account Settings Below</h2>
<hr /><br />
<h1><u>Upload your Profile Photo</h1></u>
<br />
<form action="" method="POST" enctype="multipart/form-data">
<img src="<?php echo $profile_pic; ?>" width="100" /><br />
<input type="file" name="profilepic" /> <br />
<input type="submit" name="uploadpic" value="Upload Picture" />
</form>
<br />
<hr /><br />
<form action="account_settings.php" method="POST">
<h1><u>Change your Password</u></h1><br />
<input type="password" name="oldpassword" placeholder="Your Old Password" id="oldpassword" style="font-size:12px; width: 150px;"><br />
<input type="password" name="newpassword" placeholder="Your New Password" id="newpassword" style="font-size:12px; width: 150px;"><br />
<input type="password" name="newpassword2" placeholder="Repeat Password" id="newpassword2" style="font-size:12px; width: 150px;"><br />
<input type="submit" name="senddata" id="senddata" value="Update Information">
</form>
<form action="account_settings.php" method="POST">
<br /><hr /><br />
<h1><u>Update Your Profile Info</u></h1><br />
First Name: <input type="text" name="fname" id="fname" value=<?php echo $db_firstname; ?> style="font-size:12px; width: 150px;"><br />
Last Name: <input type="text" name="lname" id="lname" value=<?php echo $db_lastname; ?> style="font-size:12px; width: 150px;"><br />
About You: <br /><textarea name="aboutyou" id="aboutyou" rows="7" cols="35"><?php echo $db_bio; ?></textarea>
<br />
<input type="submit" name="updateinfo" id="updateinfo" value="Update Information">
</form>
<br />
<hr />
<form action="close_account.php" method="POST">
<br />
<h1><u>Close your Account</u></h1><br />
<input type="submit" name="closeaccount" id="closeaccount" value="Close Account">
</form>
<br />
<hr />