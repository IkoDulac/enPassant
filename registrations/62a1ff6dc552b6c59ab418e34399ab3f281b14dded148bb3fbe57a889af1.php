<!DOCTYPE html>
<html>
<head>
	<title>validation du courriel</title>
	<?php include '../header.html'; ?>
</head>
<body>
<?php
$path2login = "../login.html";
$username = "hugue";
$dbname = "test";
$table = "users";

$connection = pg_connect("dbname=$dbname")
	or die('Connection failed: ' . pg_last_error());
$query = "UPDATE $table SET valid = TRUE WHERE username = $1;";
pg_query_params($connection, $query, array($username))
	or die('"valid = TRUE" query failed: ' . pg_last_error());

print("le courriel est validé, bienvenue et bonne route !<br>
	<a href='$path2login'>se connecter</a>");
?>
</body>
</html>