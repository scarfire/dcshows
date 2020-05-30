<!DOCTYPE html>
<html>
<head>
<title>Dead & Company Shows</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="styles.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="page-title"><?php echo $_REQUEST["year"] ?> 
	<a class="header-link" href="year.php?year=<?php echo $_REQUEST["year"] ?>"> Shows</a></div>
<table width="100%" border="0">
   <?php
    require_once('dbconnpdo.php');

	// Get show header info
    $sql = "SELECT a.id, city_state_country, DATE_FORMAT(show_date, '%b %d') as showdate, attended, poster FROM ds_shows a INNER JOIN ds_locations b ON a.location_id = b.id WHERE YEAR(show_date) = ? ORDER BY show_date";
    $stmt = $db->prepare($sql);
    $stmt->execute(array($_REQUEST["year"]));
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $row){
	?>
	<tr>
		<td style="width: 60px; text-align: left; padding-left: 10px;">
			<a href="show.php?show_id=<?php echo $row['id'] ?>">
				<img class="poster-thumbnail" src="images/posters/<?php echo $row['poster'] ?>" /></a></td>
		<td style="text-align: left; padding-left: 5px;"><a href="show.php?show_id=<?php echo $row['id'] ?>"><?php echo $row["showdate"] ?></a></td>
		<td style="text-align: right; padding-right: 10px;"><a href="show.php?show_id=<?php echo $row['id'] ?>"><?php echo $row["city_state_country"] ?></a></td>
	</tr>
	<?php 
	}
	?>
</table>
	
</body>
</html>
