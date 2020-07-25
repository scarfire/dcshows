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
	<link rel='icon' href="images/favicon-180.png?v=2" type='image/x-cxon'
</head>
<body>
	<div class="page-title">Tours</div>
<?php
	require_once('dbconnpdo.php');
	// Valid user check
	if (isset($_SESSION["userid"])) {
		// Reload - already logged in
	}
	else {
		if (isset($_REQUEST["userid"])) {
			// Check if valid user id
			$sql = "SELECT * FROM ds_users WHERE user_id = ?";
			$stmt = $db->prepare($sql);
			$stmt->execute(array($_REQUEST["userid"]));
			$row = $stmt->fetch();

			if ($row != null) {
				// Valid user - save user id
				$_SESSION["userid"] = $_REQUEST["userid"];
			}
			else {
				// No matching user found
				invalidUser();
			}
		}
		else {
			// No user id provided
			invalidUser();
		}
	}

	// Get random show
	$sql = "SELECT id FROM ds_shows WHERE band = 'Dead and Co.' ORDER BY RAND() LIMIT 1";
	$stmt = $db->prepare($sql);
	$stmt->execute();
	$row = $stmt->fetch();

function invalidUser() {
	// Prints out invalid user
	echo "<h3>Invalid User ID</h2><div>Email <a style='color:blue; text-decoration:underline;' href='mailto:scarfireweb@gmail.com'>scarfireweb@gmail.com</a> to request free access</div>";
	die();
}
?>

	<form name="form1" id="form1" action="searchresults.php" method="get">
<div style="display: flex; align-items:stretch;">
		<input style="background-color: antiquewhite; width: 140; margin-bottom: 4px; margin-top: 8px; margin-left: 12px;" name="search" id="search" type="text" maxlength="20" 
			   placeholder="Search song/location" />
		<a href="javascript:form1.submit();"><img style="margin-left: 5px; margin-top: 7px;" src="images/search.png" height="46" alt="Search"/></a>
	<a href="show.php?show_id=<?php echo $row["id"] ?>"><img style="margin-left:10px; margin-top: 9px;" src="images/random.png" height="44" alt="Random"/></a>
</div>
	</form>
<hr/>

<div class="tile">
	<a href="year.php?year=2020">
		<img src="images/posters/2020mexico.png" class="poster">
	</a>
	<div class="tile-date">2020</div>
</div>
<div class="tile">
	<a href="year.php?year=2019">
		<img src="images/posters/2019saratoga.png" class="poster">
	</a>
	<div class="tile-date">2019</div>
</div>
<div class="tile">
	<a href="year.php?year=2018">
		<img src="images/posters/2018neworleans.png" class="poster">
	</a>
	<div class="tile-date">2018</div>
</div>
<div class="tile">
	<a href="year.php?year=2017">
		<img src="images/posters/2017mountainview.png" class="poster">
	</a>
	<div class="tile-date">2017</div>
</div>
<div class="tile">
	<a href="year.php?year=2016">
		<img src="images/posters/2016camden.png" class="poster">
	</a>
	<div class="tile-date">2016</div>
</div>
<div class="tile">
	<a href="year.php?year=2015">
		<img src="images/posters/2015nashville.png" class="poster">
	</a>
	<div class="tile-date">2015</div>
</div>	
</body>
</html>
