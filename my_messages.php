<?php include("./inc/header.inc.php"); include("./inc/connect.inc.php"); ?>
<?php
if ($user != "") {
	echo "<h2 style='text-decoration:underline;'>My Unread Messages</h2>";

	$grab_messages = mysqli_query($connect,"SELECT * FROM pvt_messages WHERE user_to='$user' && opened='no' && deleted='no' ORDER BY id");
	$numrows = mysqli_num_rows($grab_messages);
	if ($numrows >= 1) {
	while($get_msg = mysqli_fetch_assoc($grab_messages)) {
		$id = $get_msg['id'];
		$user_from = $get_msg['user_from'];
		$user_to = $get_msg['user_to'];
		$msg_title = $get_msg['msg_title'];
		$msg_body = $get_msg['msg_body'];
		$date = $get_msg['date'];
		$opened = $get_msg['opened'];
		$deleted = $get_msg['deleted'];
		?>
		<script language='javascript'>
		function toggle<?php echo $id; ?>() {
			var ele = document.getElementById("toggleText<?php echo $id; ?>");
			var text = document.getElementById("displayText<?php echo $id; ?>");
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
		if(strlen($msg_title) > 50) {
			$msg_title = substr($msg_title,0,50)."...";
		} else {
			$msg_title = $msg_title;
		}
		if(strlen($msg_body) > 150) {
			$msg_body = substr($msg_body,0,150)."...";
		} else {
			$msg_body = $msg_body;
		}
		if ($msg_title == "") {
			$msg_title = "[No Subject]";
		}

		if (@$_POST['setopened_'.$id.'']) {
			$setopened_query = mysqli_query($connect,"UPDATE pvt_messages SET opened='yes' WHERE id='$id'");
			header("Location: my_messages.php");
			exit;
		}

		echo "
		<form method='POST' action='my_messages.php' name='$id'>
		<a href='$user_from' style='font-size:14px;'><input type='button' value='$user_from'></a>
		<input type='button' name='openmsg' value='$msg_title' onClick='javascript:toggle$id();'>
		<input type='submit' name='setopened_$id' style='height:26px; font-size: 14px; padding: 2px;' value='Mark as Read'>
		</form>
		<div id='toggleText$id' style='display:none;'>
		<br />$msg_body
		</div>
		<br /><hr /><br />
		";
	}
	} else {
	echo "You have no unread messages!<br />";
}

	echo "<h2 style='text-decoration:underline;'>My Read Messages</h2>";

	$grab_messages = mysqli_query($connect,"SELECT * FROM pvt_messages WHERE user_to='$user' && opened='yes' && deleted='no'");
	$numrows_read = mysqli_num_rows($grab_messages);
	if ($numrows_read >= 1) {
	while($get_msg = mysqli_fetch_assoc($grab_messages)) {
		$id = $get_msg['id'];
		$user_from = $get_msg['user_from'];
		$user_to = $get_msg['user_to'];
		$msg_title = $get_msg['msg_title'];
		$msg_body = $get_msg['msg_body'];
		$date = $get_msg['date'];
		$opened = $get_msg['opened'];
		$deleted = $get_msg['deleted'];
		?>
		<script language='javascript'>
		function toggle<?php echo $id; ?>() {
			var ele = document.getElementById("toggleText<?php echo $id; ?>");
			var text = document.getElementById("displayText<?php echo $id; ?>");
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
	if(strlen($msg_title) > 50) {
			$msg_title = substr($msg_title,0,50)."...";
		} else {
			$msg_title = $msg_title;
		}
		if(strlen($msg_body) > 150) {
			$msg_body = substr($msg_body,0,150)."...";
		} else {
			$msg_body = $msg_body;
		}
		if ($msg_title == "") {
			$msg_title = "[No Subject]";
		}

		if (@$_POST['delete_'.$id.'']) {
			$delete_msg_query = mysqli_query($connect,"UPDATE pvt_messages SET deleted='yes' WHERE id='$id'");
			echo '<meta http-equiv="refresh" content="0; url=my_messages.php";>';
		}

		if (@$_POST['reply_'.$id.'']) {
			echo '<meta http-equiv="refresh" content="0; url=msg_reply.php?u='.$user_from.'";>';
		}

		echo "
		<form method='POST' action='my_messages.php' name='$id'>
		<a href='$user_from' style='font-size:14px;'><input type='button' value='$user_from'></a>
		<input type='button' name='openmsg' value='$msg_title' onClick='javascript:toggle$id();'>
		<input type='submit' name='delete_$id' style='height:26px; font-size: 14px; width: 20px; padding: 2px;' value='X' title='Delete Message'>
		<input type='submit' name='reply_$id' style='height:26px; font-size: 14px; padding: 2px;' value='Reply'>
		</form>
		<div id='toggleText$id' style='display:none;'>
		<br />$msg_body
		</div>
		<br /><hr /><br />";
	}
} else {
	echo "You haven't read any messages yet!<br />";
}
} else {
	header("Location: index.php");
}
?>
