<?php
/*
 * query info on a user
 */
session_start();

$userID = (int)$_GET['usr'];

if ($userID == null) {
	header('Location: searchuser.php');
	exit;
} else {
	// database connection constants
	$dbname = "test";
	$usersTable = "users";
	$offersTable = "offers";
	$reviewsTable = "reviews";

	$connection = pg_connect("dbname=$dbname")
               or die('connection failed: ' . pg_last_error());

	// check if user's profile is public
        $query = "SELECT is_public FROM $usersTable WHERE id = $1;";
        $result = pg_query_params($connection, $query, array($userID));
        $isPublic = pg_fetch_result($result, 0, 0);
        pg_free_result($result);
	if ($isPublic !== "t" && !isset($_SESSION['logged'])) {
		header('Location: index.php');
		exit;
	}
}
require __DIR__."/php/queryusersprofile.php";
?>

<!DOCTYPE html> 
<html> 
<head> 
	<?php include 'html/header.html'; ?>
	<title>profil de membre</title>
	<script>
		function toggleHidden(id) {
			let element = document.getElementById(id);
			if (element.style.display === "none") {
				element.style.display = "block";
			} else {
				element.style.display = "none";
			}
		}
	</script>
</head>
<body>
	<div><?php queryProfile($userID); ?></div>

	<button onclick="toggleHidden('profilerides')">voir les offres</button><br>
	<div id="profilerides" style="display: none;">
	<?php queryRides($userID); ?>
	</div>
	<button onclick="toggleHidden('profilereviews')">voir les avis</button><br>
	<div id="profilereviews" style="display: none;">
	<?php queryReviews($userID); ?>
	</div>
	<button onclick="toggleHidden('newreview')">écrire un avis</button><br>
	<div id="newreview" style="display: none;">
		<form id="newreview" class="blocklabel" action="php/newreview2sql.php" method="post">
			<input name="userID" type="hidden" value="<?php echo $userID; ?>"/>
			<label for="review">un très haut niveau de bienveillance est exigé</label>
			<textarea id="review" name="review" rows="4" cols="46"></textarea><br>
			<input id="submitButton" type="submit" value="publier cet avis" />
		</form>
	</div>
</body>
</html>

<?php pg_close($connection); ?>
