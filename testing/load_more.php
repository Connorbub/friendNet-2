<html>
<head>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/
libs/jquery/1.3.0/jquery.min.js"></script>
<script type="text/javascript">
$(function() 
{
$('.more').live("click",function() 
{
var ID = $(this).attr("id");
if(ID>=0)
{
$("#more"+ID).html('<img src="loader.gif" />');

$.ajax({
type: "POST",
url: "ajax_more.php",
data: "lastmsg="+ ID, 
cache: false,
success: function(html){
$("ol#updates").append(html);
$("#more"+ID).remove(); // removing old more button
}
});
}
else
{
$(".morebox").html('No More Posts!');// no results
}

return false;
});
});
</script>
    
<style>
*{ margin:0px; padding:0px }
ol.timeline
{ 
list-style:none
}
ol.timeline li
{ 
position:relative;
border-bottom:1px #dedede dashed; 
padding:8px; 
}
.morebox
{
font-weight:bold;
color:#333333;
text-align:center;
border:solid 1px #333333;
padding:8px;
margin-top:8px;
margin-bottom:8px;
-moz-border-radius: 6px;
-webkit-border-radius: 6px;
}
.morebox a{ color:#333333; text-decoration:none}
.morebox a:hover{ color:#333333; text-decoration:none}
#container{margin-left:60px; width:580px }
</style>
    
</head>
<body>
    <div id='container'>
<ol class="timeline" id="updates">
  <?php
include('../inc/connect.inc.php');
$sql=mysqli_query($connect,"select * from test_msg ORDER BY id DESC LIMIT 9");
while($row=mysqli_fetch_array($sql))
{
$msg_id=$row['id'];
$message=$row['msg'];
?>
<li>
<?php echo $message; ?>
</li>
<?php } ?>
</ol>
<div id="more<?php echo $msg_id; ?>" class="morebox">
<a href="#" class="more" id="<?php echo $msg_id; ?>">more</a>
</div>
</div>    
</body>
</html>