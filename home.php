<?php
include("./inc/header.inc.php"); include("./inc/connect.inc.php");
if (isset($_SESSION['user_login'])) {
	date_default_timezone_set('America/Detroit');
	$time = date("g:i:s A");
	$date = date("Y-m-d");
	$online_query = mysqli_query($connect,"UPDATE users SET online='yes' WHERE username='$user'");
	$time_query = mysqli_query($connect,"UPDATE users SET time_last_seen='$time' WHERE username='$user'");
	$date_query = mysqli_query($connect,"UPDATE users SET date_last_seen='$date' WHERE username='$user'");
} else {
	header("Location: index.php");
}
?>
<div class='newsfeed'>
<div class="textHeader">
<h2>Newsfeed</h2>
</div>

<div class='rightContent'>
<div class='title'>
<h2 style='text-decoration: underline; text-align: center;'>Your Friends</h2>
</div>
<div class='content'>
<?php

	$friendsArray = "";
	$countFriends = "";
	$friendsArray12 = "";
	$addAsFriend = "";
	$selectFriendsQuery = mysqli_query($connect,"SELECT friend_array FROM users WHERE username='$user'");
	$friendRow = mysqli_fetch_assoc($selectFriendsQuery);
	$friendArray = $friendRow['friend_array'];
	if ($friendArray != "") {
   		$friendArray = explode(",",$friendArray);
   		$countFriends = count($friendArray);
   		$friendArray12 = array_slice($friendArray, 0, 12);

	$i = 0;
	if($countFriends != 0) {
		foreach($friendArray as $key => $value) {
			$i++;
			$getFriendQuery = mysqli_query($connect,"SELECT * FROM users WHERE username='$value' AND closed='no' LIMIT 1");
			$getFriendRow = mysqli_fetch_assoc($getFriendQuery);
			$friendUsername = $getFriendRow['username'];
			$friendOnline = $getFriendRow['online'];
			$friendTime = $getFriendRow['time_last_seen'];
			$friendDate = $getFriendRow['date_last_seen'];
			$friendClosed = $getFriendRow['closed'];
			if ($friendClosed == 'no') {
				if ($friendOnline == 'yes') {
					echo "<div class='onlinefriend'>&bull;<a href='$friendUsername'>".$friendUsername."</a>&nbsp;&nbsp;<img src='./img/online.png' title='Online' width='14'> as of $friendTime (Detroit Timezone)&nbsp;&nbsp;<a href='send_msg.php?u=$friendUsername'><input type='submit' value='Message' style='width:75px !important; padding: 2px !important;'></a></div><br />";
				} else {
					echo "<div class='onlinefriend'>&bull;<a href='$friendUsername'>".$friendUsername."</a>&nbsp;&nbsp;<img src='./img/offline.png' title='Offline' width='14'> since $friendTime (Detroit Timezone) on $friendDate</div><br />";
				}
			} else {
				echo "<div class='onlinefriend'>This person has closed their account!</div>";
			}
		}
	} else {
		echo "<h1>No friends found! Try adding some...</h1>";
	}
} else {
		echo "<br /><h1 style='font-size: 14px;'>No friends found! Try adding some...</h1><br />";
}

?>
</div>
<br />
<div class='title' id='fnnewstitle'>
<h2 style='text-decoration: underline; text-align: center;'>friendNet News</h2>
</div>
<div class='content'>
<h1 style='font-size: 14px;'>friendNet Beta V0.6</h1>
<p>In this update, the following features have been added...</p>
<ul>
<li style='margin-left: 20px;'>Minor bug fixes.</li>
</ul>
Make sure to stick around for future updates!
</div>
<div class='content'>
<h1 style='font-size: 14px;'>friendNet Beta V0.5</h1>
<p>In this update, the following features have been added...</p>
<ul>
<li style='margin-left: 20px;'>Check when your friends are on and offline.</li>
<li style='margin-left: 20px;'>Comments: Share info with your friends, no external post required.</li>
<li style='margin-left: 20px;'>Once again, improved Styling and UI.</li>
</ul>
Make sure to stick around for future updates!
</div>
<div class='content'>
<h1 style='font-size: 14px;'>friendNet Beta V0.4</h1>
<p>In this update, the following features have been added...</p>
<ul>
<li style='margin-left: 20px;'>A Newsfeed to view relevant information about your friends.</li>
<li style='margin-left: 20px;'>Poking: A simple yet annoying feature that all good social sites need.</li>
<li style='margin-left: 20px;'>Improved Styling and UI.</li>
</ul>
Make sure to stick around for future updates!
</div>
<!-- <div class='content'>
<h1 style='font-size: 14px;'>friendNet Beta V0.3</h1>
<p>In this update, the following features have been added...</p>
<ul>
<li style='margin-left: 20px;'>Email-style messaging system. May add a DM system in the future.</li>
<li style='margin-left: 20px;'>Minor improvements to the friend adding system: faster and less buggy.</li>
<li style='margin-left: 20px;'>Improved profile styling.</li>
</ul>
Make sure to stick around for future updates!
</div> -->
</div>

<?php
$getposts = mysqli_query($connect, "SELECT * FROM posts WHERE user_posted_to='$user' OR added_by='$user' ORDER BY id DESC LIMIT 10") or die(mysqli_error());
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
		";
		if ($added_by == $user) {
			$added_by_original = $added_by;
			$added_by = "You";
		} else {
			$added_by_original = $added_by;
			$added_by = $added_by;
		}

		if ($user_posted_to == $user) {
			$user_posted_to_original = $user_posted_to;
			$user_posted_to = "your";
		} else {
			$user_posted_to_original = $user_posted_to;
			$user_posted_to = $user_posted_to_original."'s";
		}

		if ($user_posted_to_original == $user) {
			echo "<a href='$added_by' />$added_by</a> posted on your profile:";
		} else if ($user_posted_to_original != $user) {
				echo "$added_by posted on <a href='$user_posted_to' />$user_posted_to_original</a>'s profile:";
		} else if ($added_by_original == $user_posted_to_original) {
			echo "$added_by_original posted on their own profile:";
		} else {
			echo "$added_by_original posted on your profile:";
		}
		echo "
		</div>
		<br /><br />
		<div style='width: 98%; margin-left: 10px;'>
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
	echo "<h2>Nobody has posted on your profile yet!</h2>";
}

?>

<style type="text/css">
	.textHeader {
		text-decoration: underline;
		color: #0084C6;
		background-color: #F3F6F9;
		border: 1px solid #DCE5EE;
		width: 100%;
		margin-right: auto;
		margin-left: auto;
		height: 23px;
		text-align: center;
	}

	.textHeader h2 {
		padding: 0px !important;
	}

	.post {
		width: 70% !important;
	}

	.rightContent {
		background-color: #F3F6F9;
		border: 1px solid #DCE5EE;
		padding: 5px;
		margin-top: 6px;
		float: right;
		width: 28%;
		margin-right: -12px;
		height: 20px;
	}

	.rightContent h2 {
		padding: 0px !important;
	}

	.content {
		background-color: #FFFFFF;
	    border: 1px solid #DCE5EE;
	    padding: 6px;
	    margin-top: 6px;
	    margin-right: -6px;
	    margin-left: -6px;
	}

	.content h1 {
		text-decoration: underline;
	}

	#fnnewstitle {
		background-color: #F3F6F9;
	    border: 1px solid #DCE5EE;
	    padding: 4px;
	    margin-right: -6px;
	    margin-left: -6px;
	    margin-top: -9px;
	}
</style>
    <br />
