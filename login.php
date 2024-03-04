<!DOCTYPE html>
<html>
<head>
	<title>connexion</title>
	<?php include 'header.html'; ?>
	<script src="js/showpassword.js"></script>

</head>


<body>
	<div>
		<h2>connexion</h2>
		<form action="php/checkpassword.php" method="post">
			<label for="username">&#128126</label>
			<input id="username" onkeydown="return /[a-z0-9\-\_\ \@\.]/i.test(event.key)" name="username" placeholder="username" type="text" required pattern="[a-zA-Z0-9 \-_\@\.]+" /><br>
			<label for="password">&#128273</label>
			<input id="password" name="password" placeholder="password" type="password" required /><br>
			<input type="checkbox" onclick="showPassword()">afficher le mot de passe<br>
			<input id="submitButton" name="register" type="submit" value="valider" />
		</form>
	</div>

</body>
</html>
