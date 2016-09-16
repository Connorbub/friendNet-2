<?php
include("../inc/connect.inc.php");
if(isSet($_POST['lastmsg']))
{
$lastmsg=$_POST['lastmsg'];
$lastmsg=mysqli_real_escape_string($connect,$lastmsg);
$result=mysqli_query($connect,"select * from test_msg where id<'$lastmsg' order by id desc limit 9");
while($row=mysqli_fetch_array($result))
{
$msg_id=$row['id'];
$message=$row['msg'];
?>
<li>
<?php echo $message; ?>
</li>
<?php
}
?>

<div id="more<?php echo $msg_id; ?>" class="morebox">
<a href="#" id="<?php echo $msg_id; ?>" class="more">more</a>
</div>
<?php
}
?>