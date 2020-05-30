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
<div class="page-title">Videos</div>
<?php
require_once('dbconnpdo.php');
if (isset($_REQUEST["action"]) == false) {
	// View Videos
	$sql = "SELECT id, title, url from ds_videos WHERE show_id = ? and user_id = ?";
	$stmt = $db->prepare($sql);
	$stmt->execute(array($_REQUEST["show_id"], $_SESSION["userid"]));
	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
	echo "<table width='100%' border='0'>";
	foreach ($rows as $row) {
	?>
	<tr>
		<td class="videos">
			<a target="new" href="<?php echo $row['url'] ?>"><?php echo $row['title'] ?></a>
		</td>
		<td class="videos">
			<a href="videos.php?id=<?php echo $row["id"] ?>&show_id=<?php echo $_REQUEST["show_id"] ?>&action=delete">Delete</a>
		</td>
	</tr>
	<?php 
	}
	?>
	</table>
	<hr/><br/>
	<!-- Capture new videos links -->
	<form action="videos.php" method="get"> 
		<div class='set-header'>Video Title</div>
		<input style="background-color: antiquewhite; width: 100%;" name="title" id="title" type="text" maxlength="100" placeholder="Add song title(s)" /><br/><br/>
		<div class='set-header'>Video URL</div>
		<input style="background-color: antiquewhite; width: 100%;" name="url" id="url" type="text" maxlength="100" placeholder="Add video URL" />
		<input type="hidden" name="show_id" id="show_id" value="<?php echo $_REQUEST["show_id"] ?>" />
		<input type="hidden" name="action" id="action" value="add" />
		<br/><br/>
		<input type="submit">
	</form>
	<br/>
	<a href="show.php?show_id=<?php echo $_REQUEST["show_id"] ?>"><div class="page-title">Return to Show</div></a>
	<?php
}
else {
	// Delete or Add action
	if ($_REQUEST["action"] == "delete") {
		// Delete video
		$sql = "DELETE FROM ds_videos WHERE id = ?";
		$stmt = $db->prepare($sql);
		$stmt->execute(array($_REQUEST["id"]));
		reloadVideos();
	}
	else if ($_REQUEST["action"] == "add") {
		// Add new video
		$sql = "INSERT INTO ds_videos (title, url, show_id, user_id) VALUES (?, ?, ?, ?)";
		$stmt = $db->prepare($sql);
		$stmt->execute(array($_REQUEST["title"], $_REQUEST["url"], $_REQUEST["show_id"], $_SESSION["userid"]));
		reloadVideos();
	}
}
	
function reloadVideos() {
	// Reload Videos page
	echo '<script type="text/javascript">' . "\n";
	echo "window.location='videos.php?show_id=" . $_REQUEST["show_id"] . "'";
	echo '</script>';
}
?>
	
</body>
</html>
