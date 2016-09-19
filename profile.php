<?php include("./inc/header.inc.php"); include("./inc/connect.inc.php"); ?>
<?php
if (isset($_GET['u'])) {
	$username = mysqli_real_escape_string($connect, $_GET['u']);
	if (ctype_alnum($username)) {
		$check = mysqli_query($connect,"SELECT username, first_name, closed FROM users WHERE username='$username'");
		if (mysqli_num_rows($check)===1) {
			$get = mysqli_fetch_assoc($check);
			$username = $get['username'];
			$_SESSION['u'] = $username;
			$firstname = $get['first_name'];
			$closed = $get['closed'];
		} else {
			echo "<meta http-equiv=\"refresh\" content=\"0; url=http://localhost/friendNet/index.php\">";
			exit();
		}
	}
}

	$check_pic = mysqli_query($connect,"SELECT profile_pic FROM users WHERE username='$username'");
  	$get_pic_row = mysqli_fetch_assoc($check_pic);
  	$profile_pic_db = $get_pic_row['profile_pic'];

  	if($profile_pic_db == "") {
  		$profile_pic = "img/default_pic.jpg";
  	} else {
  		$profile_pic = "userdata/profile_pics/".$profile_pic_db;
  	}
?>
<script language="javascript">
$(document).ready(function() {
	$(".profileInfo").sticky({topSpacing:1});
});
</script>
<style type="text/css">
	.profileLeftSideContent img {
		padding-left: 5px;
	}
</style>
<div class="rightColumn">
<div class="postForm">
	<form action="<?php echo $username; ?>" method="POST">
	<textarea id="post" name="post" rows="4" cols="76" placeholder="What's on your mind..."></textarea>
	<input type="submit" name="send" onclick="" value="Post" class="sendPost" style="background-color: #DCE5EE; border: 1px solid #666; float: left; margin-right: 0px; margin-top: 0px; width: 70px; height: 30px; color: #000000;">
	<div id="status">
	<?php
	$post = @$_POST['post'];
	if (isset($post)) {
		if ($post == "") {
			echo "&nbsp;&nbsp;Please type something to post!";
		}
	}
	if ($post != "") {
		$slashedPost = addslashes($post);
		$date_added = date("Y-m-d");
		$added_by = $user;
		$user_posted_to = $username;
		$sqlCommand = "INSERT INTO posts VALUES('','$slashedPost','$date_added','$added_by','$user_posted_to')";
		$query = mysqli_query($connect, $sqlCommand) or die(mysqli_error($connect));
	}
	?>
	</div>
	</form>
</div>
<div class="profilePosts" style="margin-top: 10px;">
<?php

$getposts = mysqli_query($connect, "SELECT * FROM posts WHERE user_posted_to='$username' ORDER BY id DESC LIMIT 10") or die(mysqli_error());
if (mysqli_num_rows($getposts) != 0) {
while($row = mysqli_fetch_assoc($getposts)) {
	$id = $row['id'];
	$body = $row['body'];
	$date_added = $row['date_added'];
	$added_by = $row['added_by'];
	$user_posted_to = $row['user_posted_to'];

	$get_user_info = mysqli_query($connect,"SELECT * FROM users WHERE username='$added_by'");
	$get_info = mysqli_fetch_assoc($get_user_info);
	$profilepic_info = $get_info['profile_pic'];

	if ($profilepic_info == "") {
		$profilepic_info = "img/default_pic.jpg";
	} else {
		$profilepic_info = "userdata/profile_pics/$profilepic_info";
	}
	?>
    <script language="javascript">
		    function toggle<?php echo $id; ?>() {
			var ele = document.getElementById("toggleComment<?php echo $id; ?>");
			var text = document.getElementById("displayComment<?php echo $id; ?>");
			if (ele.style.display == "block") {
				ele.style.display = "none";
			}
			else
			{
				ele.style.display = "block";
			}
        }
		</script>
    <?php

	echo "
	<div class='post'>
	<div class='postOptions'>
        <a href='#' onClick='javascript:toggle$id();'>Show Comments</a>
    </div>
	<div style='float:left; padding: 5px;'>
	<a href='$added_by'><img src='$profilepic_info' height='60'></a>
	</div>
	<div class='posted_by'>
	<a href='$added_by'>$added_by</a> &bull; $date_added:</div>
	<br /><br />
	<div style='width: 540px; margin-left: 10px;'>
	$body
	</div>
	<br /><div id='toggleComment$id' style='display:none;'>
		<br />
		<iframe src='./comment_frame.php?id=$id' style='max-height: 150px; height: auto; width: 100%; min-height: 10px;'></iframe>
		</div><br />
		</div>
	";
}
} else {
	echo "<b>No posts on this user's profile...</b><hr />";
}
echo "
</div>
</div>
";

	$errormsg = "";
	if (isset($_POST['addfriend'])) {
		$friend_request = $_POST['addfriend'];

		$user_to = $user;
		$user_from = $username;
		if ($user != "") {
			if($user_to == $user_from) {
				$errormsg = "You can't send a friend request to yourself!<br />";
			} else {
				$create_request = mysqli_query($connect,"INSERT INTO friend_requests VALUES ('','$user_to','$user_from')");
				$errormsg = "Your friend request has been sent!<br />";
			}
		} else {
			$errormsg = "You must be logged in to do that!<br />";
		}
	}

	if (@$_POST['poke']) {
		$check_if_poked = mysqli_query($connect,"SELECT * FROM pokes WHERE user_to='$username' && user_from='$user'");
		$num_poke_found = mysqli_num_rows($check_if_poked);
		if ($num_poke_found >= 1) {
			$errormsg = "You must wait to be poked back!";
		} else if ($username == $user) {
				$errormsg = "You can't poke yourself!<br />";
		} else {
			$poke_user = mysqli_query($connect,"INSERT INTO pokes VALUES ('','$user','$username')");
			$errormsg = "$username has been poked!<br />";
		}
	}

?>
<?php
if ($closed == 'yes') {
	echo "<h1 style='color:red;'>WARNING: This user has closed their account...</h1>";
}
?>
<div class="profileInfo">
<div class="topInfo">
<img src="<?php echo $profile_pic; ?>" style="border: 1px solid #C0C0C0;" height="250" width="200" alt="<?php echo $username; ?>'s Profile" title="<?php echo $username; ?>'s Profile" />
<br />
<form action="<?php echo $username; ?>" method="POST">
<?php echo $errormsg; ?>
<?php

	if(isset($_POST['sendmsg'])) {
		if ($username != $user) {
			?>
			<script type="text/javascript">
            window.location.href = "./send_msg.php?u=<?php echo $username ?>"
        	</script>
			<?php
		} else {
			echo "You can't send a message to yourself!";
		}
	}

	$friendsArray = "";
	$countFriends = "";
	$friendsArray12 = "";
	$addAsFriend = "";
	$selectFriendsQuery = mysqli_query($connect,"SELECT friend_array FROM users WHERE username='$username'");
	$friendRow = mysqli_fetch_assoc($selectFriendsQuery);
	$friendArray = $friendRow['friend_array'];
	if ($friendArray != "") {
   		$friendArray = explode(",",$friendArray);
   		$countFriends = count($friendArray);
   		$friendArray12 = array_slice($friendArray, 0, 12);

	$i = 0;
	if (in_array($user,$friendArray)) {
		$addAsFriend = '<input type="submit" style="margin-right: 5px; width: 110px;" name="removefriend" value="Unfriend">';
	} else {
 		$addAsFriend = '<input type="submit" style="margin-right: 5px; width: 110px;" name="addfriend" value="Add Friend">';
	}
	echo $addAsFriend;
	} else {
 		$addAsFriend = '<input type="submit" style="margin-right: 5px; width: 110px;" name="addfriend" value="Add Friend">';
 		echo $addAsFriend;
	}
	if (@$_POST['removefriend']) {
		//Friend array for logged in user
		$add_friend_check = mysqli_query($connect,"SELECT friend_array FROM users WHERE username='$user'");
		$get_friend_row = mysqli_fetch_assoc($add_friend_check);
		$friend_array = $get_friend_row['friend_array'];
		$friend_array_explode = explode(",",$friend_array);
		$friend_array_count = count($friend_array_explode);

		//Friend array for user who owns profile
		$add_friend_check_username = mysqli_query($connect,"SELECT friend_array FROM users WHERE username='$username'");
		$get_friend_row_username = mysqli_fetch_assoc($add_friend_check_username);
		$friend_array_username = $get_friend_row_username['friend_array'];
		$friend_array_explode_username = explode(",",$friend_array_username);
		$friend_array_count_username = count($friend_array_explode_username);

		$usernameComma = ",".$username;
		$usernameComma2 = $username.",";

		$userComma = ",".$user;
		$userComma2 = $user.",";

		if (strstr($friend_array,$usernameComma)) {
		 	$friend1 = str_replace("$usernameComma","",$friend_array);
		}
		else
		if (strstr($friend_array,$usernameComma2)) {
			$friend1 = str_replace("$usernameComma2","",$friend_array);
		}
		else
		if (strstr($friend_array,$username)) {
			$friend1 = str_replace("$username","",$friend_array);
		}
		//Remove logged in user from other persons array
		if (strstr($friend_array_username,$userComma)) {
			$friend2 = str_replace("$userComma","",$friend_array_username);
		}
		else
		if (strstr($friend_array_username,$userComma2)) {
			$friend2 = str_replace("$userComma2","",$friend_array_username);
		}
		else
		if (strstr($friend_array_username,$user)) {
			$friend2 = str_replace("$user","",$friend_array_username);
		}

		$removeFriendQuery = mysqli_query($connect,"UPDATE users SET friend_array='$friend1' WHERE username='$user'");
		$removeFriendQuery_username = mysqli_query($connect,"UPDATE users SET friend_array='$friend2' WHERE username='$username'");
		echo "<meta http-equiv=\"refresh\" content=\"0; url=http://localhost/friendNet/$username\">";
	}

	$check_like_button = mysqli_query($connect,"SELECT * FROM like_buttons WHERE uid='$username' ");
	$check_like_numrows = mysqli_num_rows($check_like_button);
	if ($check_like_numrows >= 1) {

	} else {
		$date = date("Y-m-d");
		$create_button = mysqli_query($connect,"INSERT INTO like_buttons VALUES ('','$username','http://localhost/friendNet/$username','$date','$username')");
		$insert_like = mysqli_query($connect,"INSERT INTO likes VALUES ('','$username','0','$username')");
	}
?>
<input type="submit" name="poke" style="width:87px;" value="Poke" />
<input type="submit" name="sendmsg" style="width: 202px" value="Send Message" />
</form>
<form action="/friendNet/albums/<?php echo $username; ?>" method="POST">
<input type="submit" name="viewalbums" style="width: 202px" value="View Albums" />
</form>
<form action="/friendNet/view_niches.php?u=<?php echo $username; ?>" method="POST">
<input type="submit" name="viewniches" style="width: 202px" value="View Niches" />
</form>
<?php
if ($username == $user) {
	?>
	<form action="/friendNet/upload_photo.php?album=&uid=" method="POST">
	<input type="submit" name="uploadphoto" style="width: 202px" value="Upload a Photo" />
	</form>
<?php
} else {

}
?>
<div style='text-align: right;'>
<iframe src='./like_but_frame.php?uid=<?php echo $username; ?>' style='border: 0px; height: 40px; width: 200px;'> </iframe>
</div>
</div>
<div class="textHeader"><?php echo $username; ?>'s Profile
<?php
	$onlineQuery = mysqli_query($connect,"SELECT online FROM users WHERE username='$username'");
	$getOnline = mysqli_fetch_assoc($onlineQuery);

	if ($getOnline['online'] == 'yes') {
		echo "<img src='./img/online.png' width='16' title='Online'>";
	} else {
		echo "<img src='./img/offline.png' width='16' title='Offline'>";
	}
?>
</div>
<div class="profileLeftSideContent">
<?php

?>
<?php
	$about_query = mysqli_query($connect,"SELECT bio FROM users WHERE username='$username'");
	$get_result = mysqli_fetch_assoc($about_query);
	$about_the_user = $get_result['bio'];

	echo $about_the_user;
?>
</div>
<div class="textHeader"><?php echo $username; ?>'s Friends</div>
<div class="profileLeftSideContent">
<?php
	if($countFriends != 0) {
		foreach($friendArray12 as $key => $value) {
			$i++;
			$getFriendQuery = mysqli_query($connect,"SELECT * FROM users WHERE username='$value' LIMIT 1");
			$getFriendRow = mysqli_fetch_assoc($getFriendQuery);
			$friendUsername = $getFriendRow['username'];
			$friendProfilePic = $getFriendRow['profile_pic'];

			if ($friendProfilePic == "") {
				echo "<a href='$friendUsername'><img src='img/default_pic.jpg' alt=\"$friendUsername's Profile\" title=\"$friendUsername's Profile\" height='50' width='40' style='padding-right: 6px;' /></a>";
			} else {
				echo "<a href='$friendUsername'><img src='userdata/profile_pics/$friendProfilePic' alt=\"$friendUsername's Profile\" title=\"$friendUsername's Profile\" height='50' width='40' style='padding-right: 6px;' /></a>";
			}
		}
	} else {
		echo $firstname." has no friends yet...";
	}
?>
</div>
</div>
