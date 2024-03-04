<!DOCTYPE html>
<html>
<head>
	<title="connexion">
	<?php include '../header.html'; ?>
</head>
<body>
<?php
$path2login = "../login.php";

if (!isset($_POST['username'], $_POST['password'])) {
	exit("erreur : le nom d'utilisatrice ou le mot de passe n'ont pas été tranféré correctement<br><a href='$path2login'>retour au formulaire</a>");
} else if (isset($_POST['register'])) {


	$path2passwordReset = "../passwordreset.php";
	$dbname = "test";
	$table = "users";

	$value = $_POST['username'];
	$password = $_POST['password'];
	if (str_contains($value, '@')) {
		$field = "email";
	} else {
		$field = "username";
	}

	$connection = pg_connect("dbname=$dbname")
		or die('connection failed: ' . pg_last_error());
	$query = "SELECT id, password, valid FROM $table WHERE $field=$1";
	$result = pg_query_params($connection, $query, array($value)) or die('Query failed: ' . pg_last_error());
	$row = pg_fetch_assoc($result);

	// check if the username or email is in database
	if ($row) {
		$id = $row['id'];
		$hash = $row['password'];
		$valid = $row['valid'] === 't' ? true : false;

		// check if email was validated and verify password
		if ($valid) {
			if (password_verify($password, $hash)) {
				// set session variables and stuff => $_SESSION[]
				print("youpi");
			} else {
				print("mauvais mot de passe.<br>
					<a href='$path2login'>retour à la page d'accueil</a><br>
					ou<br>
					<a href='$path2passwordReset'>honte à moi, j'ai perdu mon mot de passe</a>");
			}
		} else {
			print("le email ne semble pas avoir été validé.<br>
				<a href='$path2login'>retour à la page d'accueil</a><br>");
		}

	} else {
		print("le nom d'utilisatrice ou le email n'a pu être récupéré dans la base de donnée.<br>
			<a href='$path2login'>retour à la page d'accueil</a><br>");
	}

	// Free resultset
	pg_free_result($result);
	pg_close($connection);

}
?>
</body>
</html>
