<?php
/*
 * request from home.php => editcontact()
 * updates users table with contact infos
 */
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
	// constants
	$dbname = "test";
	$table = "users";

	$connection = pg_connect("dbname=$dbname")
		or die("connection failed: " . pg_last_error());

	$userID = isset($_SESSION['userID']) ? (int)$_SESSION['userID'] : '';
	$contactInfo = isset($_POST['contact']) ? htmlspecialchars($_POST['contact']) : "";
	$isPublic = $_POST['public'] == 'on' ? 't' : 'f';

	// update database
	$updateQuery = "UPDATE $table SET contact_info = $1, is_public = $2 WHERE id = $3;";
	pg_query_params($connection, $updateQuery, array($contactInfo, $isPublic, $userID)) or die("Query failed: " . pg_last_error());
	pg_close($connection);
}
?>
