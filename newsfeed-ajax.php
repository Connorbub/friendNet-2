<?php
include("./inc/connect.inc.php");
if(isSet($_POST['lastmsg']))
{
$lastmsg=$_POST['lastmsg'];
$lastmsg=mysqli_real_escape_string($connect,$lastmsg);
$result=mysqli_query($connect,"select * from posts where id<'$lastmsg' order by id desc limit 9");
while($row=mysqli_fetch_array($result))
{
$id=$row['id'];
$message=$row['body'];
?>
<?php echo $message; ?><br />
<?php
}
?>
<?php
if($lastmsg != $id) { ?>
<div id="more<?php echo $id; ?>" class="morebox">
<a href="#" id="<?php echo $id; ?>" class="more">More Posts</a>
</div>
<?php
    } else {
?>
<div id="more<?php echo $id; ?>" class="morebox">
<a href="#" id="<?php echo $id; ?>" class="more">No More Posts</a>
</div>
<?php
}
}
echo $lastmsg;
echo $id;
?>