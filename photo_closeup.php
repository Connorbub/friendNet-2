<?php include("inc/header.inc.php");
  if ($_GET['img_id'] != "") {
      $picture = mysqli_real_escape_string($connect, $_GET['img_id']);
      if (ctype_alnum($picture)) {
    		$check = mysqli_query($connect,"SELECT * FROM photos WHERE img_id='$picture'");
    		if (mysqli_num_rows($check) >= 1) {
    			$get = mysqli_fetch_assoc($check);
    			$uid = $get['uid'];
    			$username = $get['username'];
          $description = $get['description'];
          $image_url = $get['image_url'];
    			$query = mysqli_query($connect,"SELECT * FROM albums WHERE uid='$uid'");
    			$fetch_assoc = mysqli_fetch_assoc($query);
    			$album_name = $fetch_assoc['album_title'];
          $removed = $get['removed'];
          $image_url_correct = substr($image_url, 6);

          if ($removed == 'no') {
          ?>

            <h2 style="font-size: 20px;  text-decoration: underline;">Photo Details</h2>
            <div class='albums2'>
              <br><br>
		        	<img src="<?php echo $image_url_correct; ?>" style="max-width: 900px; max-height: 720px;"/><br />
		            <?php echo "<h3>".$description."</h3>"; ?><br />
                Uploaded by <b><a href=<?php echo $username ?>><?php echo $username ?></a></b> to their album <b>"<a href="albums/view_photo/<?php echo $uid; ?>"><?php echo $album_name; ?></a>"</b><br /><br />
		        </div>
            <br />
            <div class='albums2'>
              <h3>Share with your friends!</h3><br />
              <input type="text" id="pageURL" style="width: 500px !important;"></input>
              <script type="text/javascript">
                var el = document.getElementById("pageURL");
                el.value = document.URL;
              </script>
            </div>

          <?php
          } else {
            echo "<h2 style='font-size: 20px;'>This photo was deleted!</h2>";
          }

    		} else {
    			echo "<h2 style='font-size: 20px;'>Photo not found!</h2>";
    		}
    	}
  } else {
    echo "<h2 style='font-size: 20px;'>Photo not found!</h2>";
  }
?>
