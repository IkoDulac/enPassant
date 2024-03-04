<!DOCTYPE html>
<html>
<head>
	<title="enregistrement">
	<?php include '../header.html'; ?>
</head>
<body>
<?php

$path2registration = "../registration.php";

if (!isset($_POST['username'], $_POST['password'], $_POST['email'])) {
	exit("erreur : le nom d'utilisatrice ou le mot de passe n'ont pas été tranféré correctement<br><a href='$path2registration'>retour au formulaire</a>");
} else if (isset($_POST['register'])) {


	$path2login = "../login.php";
	$path2passwordReset = "passwordreset.php";
	$dbname = "test";
	$table = "users";

	$username = $_POST['username'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$hash = password_hash($password, PASSWORD_DEFAULT);

	$connection = pg_connect("dbname=$dbname")
		or die('connection failed: ' . pg_last_error());
	// check if email is already in database
	$query = "SELECT * FROM $table WHERE email=$1";
	$result = pg_query_params($connection, $query, array($email)) or die('Query failed: ' . pg_last_error());
	if (pg_affected_rows($result) == 1) {
		print("cette adresse email est déjà enregistrée.<br>
			<a href='$path2login'>retour à la page d'accueil</a><br>
			ou<br>
			<a href='$path2passwordReset'>honte à moi, j'ai perdu mon mot de passe</a>");
	} else {
		// insert new user in table
		$query = "INSERT INTO users (username, email, password) VALUES ($1, $2, $3);";
		pg_query_params($connection, $query, array($username, $email, $hash)) or die('Query failed: ' . pg_last_error());

		// Free resultset
		pg_free_result($result);
		pg_close($connection);

		// generate a file with random name of lenght '$l' in the registrations directory
		$l = 30;
		$randomBytes = bin2hex(random_bytes($l));
		$validationFile = "../registrations/" . $randomBytes . ".php";
		$fileContent = <<<EOT
			<!DOCTYPE html>
			<html>
			<head>
				<title>validation du courriel</title>
				<?php include '../header.html'; ?>
			</head>
			<body>
			<?php
			\$path2login = "../login.html";
			\$username = "$username";
			\$dbname = "test";
			\$table = "users";

			\$connection = pg_connect("dbname=\$dbname")
				or die('Connection failed: ' . pg_last_error());
			\$query = "UPDATE \$table SET valid = TRUE WHERE username = $1;";
			pg_query_params(\$connection, \$query, array(\$username))
				or die('"valid = TRUE" query failed: ' . pg_last_error());

			print("le courriel est validé, bienvenue et bonne route !<br>
				<a href='\$path2login'>se connecter</a>");
			?>
			</body>
			</html>
			EOT;

		file_put_contents(
			$validationFile,
			$fileContent
		); 

		print("le compte a été créé, il doit être validé dans les 10 prochaines minutes à l'aide du lien envoyé par courriel."); 
		// TO DO : send email with link
		}
}
?>
</body>
</html>
