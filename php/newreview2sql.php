<?php
/*
 * insert reviews into sql table
 */
session_start();

if ($_POST['review'] == null || !isset($_SESSION['userID']) || ($_POST['userID'] == $_SESSION['userID'])) {
	exit("erreur, l'avis n'a pas pu être publié <br>
		<a href='/index.php'>retour</a>");
} else {

	$dbname = "test";
	$table = "reviews";

	$userFrom = $_SESSION['userID'];
	$usernameFrom = $_SESSION['username'];
	$userTo = $_POST['userID'];
	$usernameTo = $_POST['username'];
	$review = htmlspecialchars($_POST['review']);

	$connection = pg_connect("dbname=$dbname")
		or die('connection failed: ' . pg_last_error());
	$query = "INSERT INTO $table (user_from, username_from, user_to, username_to, review) VALUES ($1, $2, $3, $4, $5);";
	$result = pg_query_params($connection, $query, array($userFrom, $usernameFrom, $userTo, $usernameTo, $review)) or die('Query failed: ' . pg_last_error());

	pg_close($connection);
}
header('Location: /home.php');
exit;
?>
