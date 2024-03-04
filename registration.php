<!DOCTYPE html>
<html>
<head>
	<title>nouveau compte</title>
	<?php include 'header.html'; ?>
	<script src="js/checkunique.js"></script>
	<script src="js/showpassword.js"></script>

</head>


<body>
	<div>
		<h2>nouveau compte</h2>
		<form action="php/adduser2psql.php" method="post">
			<label for="username">&#128126</label>
			<input id="username" onkeydown="return /[a-z0-9\-\_\ ]/i.test(event.key)" name="username" placeholder="username" type="text" onkeyup="checkUniq(this.name, this.value)" title="le nom ne peut contenir que des lettres, chiffres, tirets, espaces ou barres de soulignement" required pattern="[a-zA-Z0-9 \-_]+" /><br>
			<label id="notUnique" style="color:red;font-size:80%;"></label><br>
			<label for="email">&#128231</label>
			<input id="email" name="email" placeholder="email" type="email" required /><br>
			<label for="password">&#128273</label>
			<input id="password" name="password" placeholder="password" type="password" required /><br>
			<input type="checkbox" onclick="showPassword()">afficher le mot de passe<br>
			<input id="submitButton" name="register" type="submit" value="valider" />
		</form>
	</div>

</body>
</html>
