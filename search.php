<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<title>recherche</title>
	<?php include 'html/header.html'; ?>

	<link rel="stylesheet" href="js/leaflet/leaflet.css">
	<!--  <link rel="stylesheet" href="css/jquery-ui.css"> -->
	<script src="js/leaflet/leaflet.js"></script>
	<script src="js/jquery-3.7.1.min.js"></script>
	<script src="js/jquery-ui-1.13.2.sortable/jquery-ui.min.js"></script>
	<script src="js/polyline.js"></script>
	<script src="js/enpassant.js"></script>
	<script src="js/queryoffers.js"></script>
</head>

<body>
	<?php include 'html/navbar.html'; ?>
	
<div>
	<div class="tabnavbar">
		<a id="defaultTab" href="javascript:void(0)" onclick="switchTab(event, 'wpsearch');">
			<div class="tablink hiddenTab">par coordonées</div>
		</a>
		<a href="javascript:void(0)" onclick="switchTab(event, 'quicksearch');">
			<div class="tablink hiddenTab">trajets courants</div>
		</a>
		<a href="javascript:void(0)" onclick="switchTab(event, 'hashtagsearch');">
			<div class="tablink hiddenTab">par évènements</div>
		</a>
<!--            <a href="javascript:void(0)" onclick="switchTab(event, '');">
			<div class="tablink"></div>
		</a> -->
	</div>
	<div id="wpsearch" class="switchtab">
		<?php include 'html/wpsearch.html'; ?>
	</div>
	<div id="quicksearch" class="switchtab">
		<p>quick</p>
	</div>
	<div id="hashtagsearch" class="switchtab">
		<p>hastag</p>
	</div>
</div>
	<script>document.getElementById("defaultTab").click();</script>

<!--	<script src="js/showwaypoints.js"></script>
	<script src="js/getroute.js"></script>
	<button onclick="getRoute()">calculer l'itinéraire</button>
	<script src="js/postroute.js"></script>
	<button onclick="confirmRoute('enpassant')">publier ce voyage</button>
-->

</body>
<!-- footer with email -->
</html>
