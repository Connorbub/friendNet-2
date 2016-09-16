<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="./css/style.css">
<style type="text/css">
	* {
		background-color: transparent !important;
		font-size: 12px;
	}

	a {
		color: #000000;
		text-decoration: none;
		font-size: 14px;
	}

	a:hover {
		text-decoration: underline;
	}

	hr {
		background-color: #C0C0C0 !important;
		width: 100%;
		height: 1px;
	}

	input[type="submit"] {
		background-color: #006FC4 !important;
	}

	input[type="submit"]:hover {
		background-color: #0084C6 !important;
		cursor: pointer;
	}

</style>
<?php
	include("./inc/connect.inc.php");
	
	session_start();
	if(!isset($_SESSION["user_login"])) {
		$user = "";
	} else {
		$user = $_SESSION["user_login"];
	}

	$getid = $_GET['id'];
	?>

	<?php
	if (@$_POST['postComment'.$getid.'']) {
		if ($user != "") {
			if (@$_POST['post_body'] != null) {
				$post_body = @$_POST['post_body'];
				$posted_to = "";
				$insertPost = mysqli_query($connect,"INSERT INTO post_comments VALUES ('','$post_body','$user','$posted_to','0','$getid')");
				echo "Comment Posted!";
			} else {
				echo "Please type something to comment!";
			}
		} else {
			echo "You must be logged in to comment!";
		}
	}
	?>

	<div style='float:right; display: inline;'><a href="javascript:toggle<?php echo $getid; ?>();" style='font-size: 12px;'>Post a Comment</a></div>
	<div id='toggleComment<?php echo $getid; ?>' style='display:none;'>
	<form action="comment_frame.php?id=<?php echo $getid ?>" method="POST" name="postComment<?php echo $getid; ?>">
	Enter a Comment Below:<br />
		<textarea name='post_body' rows="5" cols="50" style="font-size: 12px; background-color:#FFFFFF !important;"></textarea>
		<br /><input type="submit" name="postComment<?php echo $getid; ?>" value="Post">
	</form>
	</div>

	<?php
	$get_comments = mysqli_query($connect,"SELECT * FROM post_comments WHERE post_id='$getid' ORDER BY id DESC");
    $count = mysqli_num_rows($get_comments);
    
    if ($count != 0) {
	    while ($comment = mysqli_fetch_assoc($get_comments)) {
		    $comment_body = $comment['post_body'];
		    $posted_by = $comment['posted_by'];
		    $removed = $comment['post_removed'];

		    echo "<div class='comment'><b style='font-size: 12px;'>$posted_by said: </b><br />".$comment_body."</div><hr /><br />";
		}
	} else {
		echo "<center><br /><br /><h1>No Comments!</h1></center>";
	}

?>
<script language="javascript">
	function toggle<?php echo $getid; ?>() {
		var ele = document.getElementById("toggleComment<?php echo $getid; ?>");
		var text = document.getElementById("displayComment<?php echo $getid; ?>");
		if (ele.style.display == "block") {
			ele.style.display = "none";
		}
		else
		{
			ele.style.display = "block";
		}
    }
</script>