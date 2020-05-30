<?php
	session_start();
?>
<!DOCTYPE html>
<html>
<head>
<title>Dead & Company Shows</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="styles.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="page-title">Edit Show</div>
<?php
	require_once('dbconnpdo.php');
	// Get info that can be edited
	$sql = "SELECT audio, notes, rating, attended from ds_notes WHERE show_id = ? and user_id = ?";
	$stmt = $db->prepare($sql);
	$stmt->execute(array($_REQUEST["show_id"], $_SESSION["userid"]));
	$row = $stmt->fetch();

	if (isset($_REQUEST["editing"])) {
		// Edit mode
		?>
		<br/><div class='set-header'>Notes</div>
		<form action="editshow.php" method="get"> 
			<textarea style="background-color: antiquewhite; width: 100%;" name="notes" id="notes" rows="4"><?php echo $row["notes"] ?></textarea>
			<br/><br/><div class='set-header'>Rating <i>(1 worse - 10 better)</i></div>
			<input style="background-color: antiquewhite; width: 30px;" name="rating" id="rating" type="text" maxlength="2" value="<?php echo $row["rating"] ?>" />
			<br/><br/><div class='set-header'>Audio</div>
			<input style="background-color: antiquewhite; width: 100%;" name="audio" id="audio" type="text" maxlength="100" value="<?php echo $row["audio"] ?>" />
			<br/><br/><div class='set-header'>Attended</div>
			<select name="attended" id="attended">
				<?php
				if ($row["attended"] == "Yes") {
					echo "<option selected value='Yes'>Yes</option>";
					echo "<option value='No'>No</option>";
				}
				else {
					echo "<option value='Yes'>Yes</option>";
					echo "<option selected value='No'>No</option>";
				}
				?>
			</select>
			<input type="hidden" name="show_id" id="show_id" value="<?php echo $_REQUEST["show_id"] ?>" />
			<input type="hidden" name="save" id="save" value="Y" />
			<br/><br/>
			<input type="submit">
		</form>
		<?php
	}
	else if (isset($_REQUEST["save"])) {
	  // Save info
      require_once('dbconnpdo.php');
	  $sql = "SELECT COUNT(*) cnt FROM ds_notes WHERE show_id = ? and user_id = ?";
      $stmt = $db->prepare($sql);
      $stmt->execute(array($_REQUEST["show_id"], $_SESSION["userid"]));
	  $rows = $stmt->fetch();
	  if ($_REQUEST["rating"] == "") {
		  $rating = null;
	  }
	  else {
		  $rating = $_REQUEST["rating"];
	  }
	  if ($rows["cnt"] == 0) {
		  // No user records for this show yet - Add one
		  $sql = "INSERT INTO ds_notes (notes, rating, attended, audio, show_id, user_id) VALUES (?, ?, ?, ?, ?, ?)";
		  $stmt = $db->prepare($sql);
		  $stmt->execute(array($_REQUEST["notes"], $rating, $_REQUEST["attended"], $_REQUEST["audio"], $_REQUEST["show_id"], $_SESSION["userid"]));
	  }
	  else {
		  // User record for this show exists - Update it
		  $sql = "UPDATE ds_notes SET notes = ?, rating = ?, attended = ?, audio = ? WHERE show_id = ? and user_id = ?";
		  $stmt = $db->prepare($sql);
		  $stmt->execute(array($_REQUEST["notes"], $rating, $_REQUEST["attended"], $_REQUEST["audio"], $_REQUEST["show_id"], $_SESSION["userid"]));
	  }
		
      // Reload Show page
      echo '<script type="text/javascript">' . "\n";
      echo "window.location='show.php?show_id=" . $_REQUEST["show_id"] . "'";
      echo '</script>';
	}
	?>
</body>
</html>
