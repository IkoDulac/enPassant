<?php
session_start();
if (!isset($_SESSION['logged'])) {
	header('Location: index.php');
	exit;
}
?>

<!DOCTYPE html>
<html>
<head>
        <title>Poney maison</title>
        <?php include 'html/header.html'; ?>
	<script src="js/jquery-3.7.1.min.js"></script>
	<script src='js/editoffer.js'></script>
	<script src='js/deleteoffer.js'></script>
</head>

<body>
	<?php include 'html/navbar.html'; ?>
	<div>
		<div class="tabnavbar">
			<a id="defaultTab" href="javascript:void(0)" onclick="switchTab(event, 'rides');">
				<div class="tablink hiddenTab">mes offres</div>
			</a>
			<a href="javascript:void(0)" onclick="switchTab(event, 'profile');">
				<div class="tablink hiddenTab">mon profile</div>
			</a>
	<!--		<a href="javascript:void(0)" onclick="switchTab(event, '');">
				<div class="tablink"></div>
			</a> -->
		</div>

		<div id="rides" class="switchtab">
			<?php include 'php/myrides.php'; ?>
		</div>

		<div id="profile" class="switchtab">
			<?php include 'php/myprofile.php'; ?>
		</div>
	</div>		
	<script>document.getElementById("defaultTab").click();</script>
</body>
</html>
