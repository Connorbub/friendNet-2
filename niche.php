<?php include("inc/header.inc.php"); ?>
<script language="javascript">

      function photochange() {
        var ele3 = document.getElementById("photoimg");
        ele3.src = "img/cameracheck.png";
      }
  </script>
  <?php
if ($_GET['id'] != "") {
    $niche = mysqli_real_escape_string($connect, $_GET['id']);
    if (ctype_alnum($niche)) {
      $check = mysqli_query($connect,"SELECT * FROM niches WHERE unique_id='$niche'");
      if (mysqli_num_rows($check) >= 1) {
        $get = mysqli_fetch_assoc($check);
        $id = $get['id'];
        $nicheuid = $get['unique_id'];
        $created_by = $get['created_by'];
        $name = $get['name'];
        $description = $get['desc'];
        $removed = $get['deleted'];

        if ($removed == 'no') {
        ?>
          <div class='albums2'>
            <br><br>
              <?php echo "<h3 style='font-size: 30px;'>".$name."</h3>"; ?><br />
              Created by <b><a href=<?php echo $created_by ?>><?php echo $created_by ?></a></b><br /><br />
          </div>
          <div class="post">
            <br>
              <?php echo "<h3>".$description."</h3>"; ?><br />
          </div>
          <div id="status">
            <?php
            $post = @$_POST['post'];
            if (isset($_POST['send'])) {
                if ($post != "") {
                  $attached_img = "";
                  $slashedPost = addslashes($post);
                  $date_added = date("Y-m-d");
                  $added_by = $user;
                  $niche_posting_to = $nicheuid;
                } else {
                  echo "Please type something to post!";
                }
                if (@$_FILES['photoinput']['size'] > 0) {
                  if (((@$_FILES['photoinput']['type']=="image/jpeg") || (@$_FILES['photoinput']['type']=="image/png") || (@$_FILES['photoinput']['type']=="image/gif")) && (@$_FILES['photoinput']['size'] < 999999999999999)) {
                          $chars = "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890";
                          $rand_dir_name = substr(str_shuffle($chars),0,15);
                          mkdir("userdata/niche_user_photos/$rand_dir_name");

                        if (file_exists("userdata/niche_user_photos/$rand_dir_name/".@$_FILES['photoinput']["name"])) {
                          echo @$_FILES['photoinput']["name"]."already exists! Please try again.";
                        } else {
                          move_uploaded_file(@$_FILES["photoinput"]["tmp_name"], "userdata/niche_user_photos/$rand_dir_name/".$_FILES["photoinput"]["name"]);
                          $profile_pic_name = @$_FILES["photoinput"]["name"];
                          $img_id_before_md5 = "$rand_dir_name/$profile_pic_name";
                          $img_id = md5($img_id_before_md5);
                          $attached_img = 'userdata/niche_user_photos/'.$rand_dir_name.'/'.$profile_pic_name;
                  }
                } else {
                  echo "Your image is either too big or not a photo file!";
                }
              } else {
                $attached_img = "";
              }
            $sqlCommand = "INSERT INTO niche_posts VALUES('','$slashedPost','$date_added','$added_by','$niche_posting_to', '$attached_img')";
            $query1 = mysqli_query($connect, "INSERT INTO photos VALUE('', '$nicheuid', '$user', '$date', '$slashedPost', '$attached_img', 'no', '$img_id')");
            $query = mysqli_query($connect, $sqlCommand) or die(mysqli_error($connect));
            if ($attached_img == "") {
              echo "Posted successfully!";
            } else {
              echo "Image posted successfully!";
            }

          }

            ?>
          </div>
          <div class="postForm" style="margin-top: 10px; float: left !important; width: 99% !important; height: auto !important;">
          	<form action="niche.php?id=<?php echo $nicheuid; ?>" method="POST" enctype='multipart/form-data'>
          	   <textarea style="float:left !important; width: 99% !important;" id="post" name="post" rows="4" cols="76" placeholder="Post to this niche..."></textarea>
               <input type="submit" name="send" onclick="" value="Post" class="sendPost" style="background-color: #DCE5EE; border: 1px solid #666; float: left; margin-right: 0px; margin-top: 3px; width: 70px; height: 30px; color: #000000;">
            <a href="#" onClick='javascript:toggle()'><img src="img/larr.png" id="arrow" width="30" height="30" style="margin-left: 10px; margin-top: 3px;"></a>
                <div id="icons" style="display:none">
                <label for="photo">
                    <img src="img/camera.png" width="30" height="30" style="margin-left: 10px;" id="photoimg">
                </label>

                <input type="file" onchange="javascript:photochange()" name="photoinput" id="photo" style="display:none;" />
                <label for="video-input">
                    <img src="img/video.png" width="30" height="30" style="margin-left: 10px;">
                </label>

                <input name="video-input" type="file" style="display:none;" />
                <a href="#" onclick="javascript:link()"><img src="img/link.png" width="30" height="30" style="margin-left: 10px;"></a>
                </div>
              </form>
          </div><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />

          <?php
          $getposts = mysqli_query($connect, "SELECT * FROM niche_posts WHERE niche_posted_to='$nicheuid' ORDER BY id DESC LIMIT 10") or die(mysqli_error());
          if (mysqli_num_rows($getposts) != 0) {
          while($row = mysqli_fetch_assoc($getposts)) {
          	$id = $row['id'];
          	$body = $row['body'];
          	$date_added = $row['date_added'];
          	$added_by = $row['added_by'];
          	$niche_posted_to = $row['niche_posted_to'];
            $attached_img_url = $row["attached_img"];

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
          	<br />
          	<div style='width: 540px; margin-left: 10px;'>
          	$body<br />";
            if ($attached_img_url != "") {
              $getphotos = mysqli_query($connect, "SELECT * FROM photos WHERE image_url='$attached_img_url'");
              $get = mysqli_fetch_assoc($getphotos);
              $img_id = $get['img_id'];
              echo "<a href='photo_closeup_niche.php?img_id=$img_id'><img src=$attached_img_url width='100' heigh='100'/></a>";
            }
            echo "
          	</div>
          	<br /><div id='toggleComment$id' style='display:none;'>
          		<br />
          		<iframe src='./comment_frame.php?id=$id' style='max-height: 150px; height: auto; width: 100%; min-height: 10px;'></iframe>
          		</div><br />
          		</div>
          	";
          }
          } else {
          	echo "<b>No posts on this niche...</b><hr />";
          }
          echo "
          </div>
          </div>
          ";
        }

          ?>

          <script language="javascript">
    		    function toggle() {
          			var ele = document.getElementById("icons");
                var ele2 = document.getElementById("arrow");
          			var text = document.getElementById("displayComment<?php echo $id; ?>");
          			if (ele.style.display == "inline") {
          				ele.style.display = "none";
                  ele2.src = "img/larr.png";
          			}
          			else
          			{
          				ele.style.display = "inline";
                  ele2.src = "img/rarr.png";
          			}
            }

          function link() {
              $("#post").append("[url=' ' text=' ']");
          }
  		</script>

        <?php
        } else {
          echo "<h2 style='font-size: 20px;'>This niche was deleted!</h2>";
        }

      } else {
        echo "<h2 style='font-size: 20px;'>Niche not found!</h2>";
      }
    } else {
      echo "<h2 style='font-size: 20px;'>Niche not found!</h2>";
    }

?>

<style>

img:hover {
  cursor: pointer;
}

</style>
