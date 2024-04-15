<?php

$path2registration = "../registration.php";

if (!isset($_POST)) {
	exit("erreur : le nom d'utilisatrice ou le mot de passe n'ont pas été tranféré correctement<br><a href='$path2registration'>retour au formulaire</a>");
} else {

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
		print("cette adresse courriel est déjà enregistrée");
	} else {
		// insert new user in table
		$query = "INSERT INTO $table (username, email, password) VALUES ($1, $2, $3);";
		pg_query_params($connection, $query, array($username, $email, $hash)) or die('Query failed: ' . pg_last_error());

		// Free resultset
		pg_free_result($result);
		pg_close($connection);

		// generate a file with random name of lenght '$l' in the registrations directory
		$l = 30;
		$randomBytes = bin2hex(random_bytes($l));
		$validationFile = "../registrations/" . $randomBytes . ".php";
		// loading file url will automaticaly validate user's email and log they
		$fileContent = <<<EOT
			<!DOCTYPE html>
			<html>
			<head>
				<title>validation du courriel</title>
				<?php include '../header.html'; ?>
			</head>
			<body>
			<?php
			\$path2login = "../login.php";
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

		shell_exec('echo "une personne veut s\'inscrire sur le site de covoiturage «poney-nordest» avec cette adresse courriel... cette personne pourrait suivre <a href=\'http://localhost:8000/registrations/'.$randomBytes.'.php\'>ce lien</a> pour valider cette adresse courriel" | mailx -a \'Content-Type: text/html\' -s "validation courriel" ' . escapeshellarg($_POST['email'])); 

		print("le compte a été créé, il doit être validé à l'aide du lien envoyé par courriel"); 
		}
}
?>
