<?php
	session_start();
?>
<!DOCTYPE html>
<html>
<head>
<?php
	header('Cache-Control: max-age=2592000');
?>

<title>Dead & Company Shows</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="styles.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="setlist">
   <?php
    require_once('dbconnpdo.php');
	
	if ($_SESSION["userid"] != null) {
		// NOTES & RATING
		$sql = "SELECT notes, rating, attended, audio FROM ds_notes WHERE show_id = ? and user_id = ?";
		$stmt = $db->prepare($sql);
		$stmt->execute(array($_REQUEST["show_id"], $_SESSION["userid"]));
		$row = $stmt->fetch();	
		$notes = $row["notes"];
		$rating = $row["rating"];
		$attended = $row["attended"];
		$audio = $row["audio"];
	}

	// Get show header info
    $sql = "SELECT city_state_country, building, show_date, DATE_FORMAT(show_date, '%b %d, %Y') as showdate, audio, poster FROM ds_shows a INNER JOIN ds_locations b ON a.location_id = b.id WHERE a.id = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute(array($_REQUEST["show_id"]));
    $row = $stmt->fetch();
	$showdate = $row["show_date"];
	$defaultAudio = $row["audio"];
	$year = substr($row["show_date"], 0, 4);
	?>
	
	<div class="show-header">
		<a href="tours.php">
			<img class="show-header-image" src="images/posters/<?php echo $row['poster'] ?>" />
		</a>
		<div class="show-header-text">
			<div class="show-header-date">
				<?php 
					echo $row["showdate"]; 
					if ($attended == "Yes") {
						echo "<br/><span class='attended'>" . "ATTENDED" . "</span>";
					}
				?>
			</div>
			<div class="show-header-location"><?php echo $row["city_state_country"] . "<br/>" . $row["building"] ?></div>
			<?php
			if ($_SESSION["userid"] != null) {
				if ($rating != null) {
					?>
					<div class="show-header-location">Rating: 
						<?php echo $rating ?>
					</div>
					<?php
				}
			}
			?>
		</div>
	</div>
	<hr/>

	<?php
	// VIDEO
    if ($_SESSION["userid"] != null) {
	   ?>
		<a href="videos.php?show_id=<?php echo $_REQUEST["show_id"] ?>"><img src="images/video.png" height="42" alt="Videos"/></a>
	   <?php
   }

	// AUDIO
	if ($audio == null)
	{
		// User hasn't entered their on audio link, show default instead 
		$audio = $defaultAudio;
	}
	?>
	<a target="new" href="<?php echo $audio ?>"><img style="margin-left:18px;" src="images/audio.png" height="42" alt="Audio"/></a>

	<?php
	// Get random show
	$sql = "SELECT id FROM ds_shows WHERE band = 'Dead and Co.' ORDER BY RAND() LIMIT 1";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $row = $stmt->fetch();
	?>
	<a href="show.php?show_id=<?php echo $row["id"] ?>"><img style="margin-left:18px;" src="images/random.png" height="41" alt="Random"/></a>

	<?php
	// Get previous show ID
    $sql = "SELECT id FROM ds_shows WHERE band = 'Dead and Co.' and show_date < ? ORDER BY show_date DESC LIMIT 1";
    $stmt = $db->prepare($sql);
    $stmt->execute(array($showdate));
    $row = $stmt->fetch();
	$prevshowid = $row["id"];

	// Get next show ID
    $sql = "SELECT id FROM ds_shows WHERE band = 'Dead and Co.' and show_date > ? ORDER BY show_date LIMIT 1";
    $stmt = $db->prepare($sql);
    $stmt->execute(array($showdate));
    $row = $stmt->fetch();
	$nextshowid = $row["id"];

	if ($prevshowid != null) {
	?>
		<a href="show.php?show_id=<?php echo $prevshowid ?>"><img style="margin-left:22px;" src="images/previous.png" height="44" alt="Previous Show"/></a>
	<?php
	}
	if ($nextshowid != null) {
	?>
		<a href="show.php?show_id=<?php echo $nextshowid ?>"><img style="margin-left:16px;" src="images/next.png" height="44" alt="Next Show"/></a><hr/>
	<?php
	}

	// Get show set list
    $sql = "SELECT a.id, title, set_number FROM ds_set_lists a INNER JOIN ds_songs b
    ON a.song_id = b.id INNER JOIN ds_shows c on a.show_id = c.id WHERE a.show_id = ? ORDER BY id";
    $stmt = $db->prepare($sql);
    $stmt->execute(array($_REQUEST["show_id"]));
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $set_number = 1;
	echo "<div class='set-header'>1st Set</div>";
	foreach ($rows as $row){
		if ($row["set_number"] == $set_number) {
		   // Set didn't change, just show song
			echo "<div class='song-title'>" . $row["title"] . "<div/>";
		}
		else {
			if ($row["set_number"] == "E") {
				// Changing from 2nd or 3rd set to Encore
				echo "<hr/><div class='set-header'>Encore</div>";
				$set_number = $row["set_number"];
				echo "<div class='song-title'>" . $row["title"] . "<div/>";
			}
			else if ($row["set_number"] == "2") {
				// Changing from 1st to 2nd set
				echo "<hr/><div class='set-header'>2nd Set</div>";
				$set_number = $row["set_number"];
				echo "<div class='song-title'>" . $row["title"] . "<div/>";
			}
			else {
				// Changing from 2nd to 3rd set
				echo "<hr/><div class='set-header'>3rd Set</div>";
				$set_number = $row["set_number"];
				echo "<div class='song-title'>" . $row["title"] . "<div/>";
			}
		}
	}
	
	if ($notes != null) {
		if ($_SESSION["userid"] != null) {
			echo "<hr/><div class='set-header'>Notes</div>";
			echo $notes . "<br/>";
	    }
	}
   if ($_SESSION["userid"] != null) {
	   ?>
		<hr/>
		<a style="color: maroon;" href="editshow.php?editing=Y&show_id=<?php echo $_REQUEST['show_id'] ?>">Edit Show</a>
	   <?php
   }
	?>
	<br/>
</body>
</html>
