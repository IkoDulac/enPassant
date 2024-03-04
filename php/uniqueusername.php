<?php

$newuser = $_GET["username"];
$dbname = "test";
$table = "users";
$warningMessage = "nom déjà utilisé";

// connect to database
$connection = pg_connect("dbname=$dbname")
	or die('connection failed: ' . pg_last_error());
// compare user input with existing value
$query = "SELECT * FROM $table WHERE username='$newuser'";
$result = pg_query($query) or die('Query failed: ' . pg_last_error());
// print warning if name already exist
if (pg_affected_rows($result) == 1) {
	print($warningMessage);
} else {
	print("");
}

// Free resultset
pg_free_result($result);
pg_close($connection);
?>
