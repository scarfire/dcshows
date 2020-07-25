<?php
	session_start();
?>
<!DOCTYPE html>
<html>
<?php
	header('Cache-Control: max-age=2592000');
?>
<head>
<title>Dead & Company Shows</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="styles.css" rel="stylesheet" type="text/css">
</head>
<body>
<a class="header-link" href="tours.php?userid=<?php echo $_SESSION["userid"] ?>"><div class="page-title">Search Results</div></a>

  <?php
    require_once('dbconnpdo.php');
    $search = "%" . trim($_REQUEST["search"]) . "%";
	// Get show header info
    $sql = "SELECT a.id, city_state_country, DATE_FORMAT(show_date, '%b %d, %Y') as showdate, poster FROM ds_shows a INNER JOIN ds_locations b ON a.location_id = b.id WHERE band = 'Dead and Co.' and a.id in (SELECT show_id FROM ds_set_lists where song_id IN (SELECT id from ds_songs where title LIKE ?)) ORDER BY show_date";
    $stmt = $db->prepare($sql);
    $stmt->execute(array($search));
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($rows == null) {
		echo "<br/>No matches found. Click Search Results to go back.";
	}
    foreach ($rows as $row){
	?>
	<div class="tile">
		<a href="show.php?show_id=<?php echo $row['id'] ?>">
			<img src="images/posters/<?php echo $row['poster'] ?>" class="poster">
		</a>
		<div class="tile-date"><?php echo $row["showdate"] ?></div>
		<div class="tile-location"><?php echo $row["city_state_country"] ?></div>
	</div>
	<?php 
	}
	?>
</body>
</html>
