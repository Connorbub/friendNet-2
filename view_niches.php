<?php include("inc/header.inc.php");

if (isset($_GET['u'])) {
  if ($_GET['u'] != "") {
  	$username = mysqli_real_escape_string($connect, $_GET['u']);
  	if (ctype_alnum($username)) {
  		$check = mysqli_query($connect,"SELECT username, first_name FROM users WHERE username='$username'");
  		if (mysqli_num_rows($check)===1) {
  			$get = mysqli_fetch_assoc($check);
  			$username = $get['username'];
  			$_SESSION['u'] = $username;
  			$firstname = $get['first_name'];
  		} else {
  			echo "<meta http-equiv=\"refresh\" content=\"0; url=http://localhost/friendNet/index.php\">";
  			exit();
  		}
  	}
  } else {
    echo "<meta http-equiv=\"refresh\" content=\"0; url=http://localhost/friendNet/index.php\">";
    exit();
  }
}

?>

<h2 style="font-size: 20px;"><?php echo $username; ?>'s Niches</h2>
<hr />
<table>
	<tr>
<?php
$get_niches = mysqli_query($connect,"SELECT * FROM niches WHERE created_by='$username' && deleted='no'");
$numrows = mysqli_num_rows($get_niches);
	if ($numrows >= 1) {
		while ($row = mysqli_fetch_assoc($get_niches)) {
			$id = $row['id'];
			$uid = $row['unique_id'];
			$niche_name = $row['name'];
			$nice_desc = $row['desc'];
      $img_src = $row['icon_url'];

			if (isset($_POST['remove_niche_'.$uid.''])) {
				$remove_album = mysqli_query($connect,"UPDATE niches SET deleted='yes' WHERE unique_id='$uid'");
				echo "Niche Removed! Refresh to see the change.";
			}

		?>
				<td>
		        <div class='albums'>
		        <a href="niche.php?id=<?php echo $uid; ?>">
              <img src="<?php echo $img_src; ?>" height="170" width="170" /><br />
		            <div class="albumlink"><?php echo $niche_name; ?></div>
		            </a>
		        </div>
		        <?php
		        if($user == $username) {
		        echo '
		        <center>
			        <form action="./<?php echo $user; ?>" method="POST">
			        	<input type="submit" name="remove_niche_<?php echo $uid; ?>" value="Remove Niche">
			        </form>
		        </center>
		        </td>
		        ';
		        } else {

		        }
		        ?>
		<?php
		}
		?>
			</tr>
		</table>
<?php
	} else {
		echo "<h2>This user has no niches!</h2>";
	}
?>
</td>
</tr>
</table>
<style type="text/css">
	#createAlbum {
		background-color: #006FC4;
		border: 1px solid #00508D;
		font-size: 15px;
		color: #FFFFFF;
		padding: 5px;
		text-decoration: none;
		margin-left: 2px;
	}

	#createAlbum:hover {
		background-color: #0084C6;
		cursor: pointer;
	}
</style>
