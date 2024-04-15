<?php
session_start();
if (isset($_SESSION['logged'])) {
	header('Location: home.php');
}
?>

<!DOCTYPE html>
<html>
<head>
        <title>home page</title>
        <?php include 'html/header.html'; ?>

	<script>
	let sessvar = "<?php echo htmlentities($_SESSION['username']); ?>"; 
	if (sessvar) {
		window.location.replace('home.php');
	}
	</script>

	<link rel="stylesheet" href="js/leaflet/leaflet.css">
	<script src="js/leaflet/leaflet.js"></script>
	<script src="js/jquery-3.7.1.min.js"></script>
	<script src="js/jquery-ui-1.13.2.sortable/jquery-ui.min.js"></script>
	<script src="js/polyline.js"></script>
	<script src="js/enpassant.js"></script>
	<script src="js/queryoffers.js"></script>
</head>
<body>
	<?php include 'html/guestsnavbar.html'; ?>

<div class="switchtab">	
	<div id="map"></div>
<!--	<div id="wpContainer"></div> -->
	<script>
		var map = L.map('map').setView([47,-70], 6);
		L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
			maxZoom: 19,
			attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
		}).addTo(map);
	</script>
	<script src="js/waypointmarkers.js"></script>
	<script>const searchRide = 1; //used in wpmarkers.js to limit number of markers</script>

<!-- recommended by osm : <a href="https://www.openstreetmap.org/fixthemap">fix the map</a> -->
	
<div class="forms">
<!--	<script src="js/sortablewaypoints.js"></script> -->
	<div class="waypointTable">
		<table id="headerTable">
			<tr><th>départ :</th></tr>
			<tr><th>arrivée :</th></tr>
		</table>
	</div>	
	<div class="waypointTable">
		<table id="coordinatesTable" class="ui-state-default">
			<tr><td>clique la carte</td><td><i class="dragBars"></i></td></tr>
			<tr><td>clique la carte</td><td><i class="dragBars"></i></td></tr>
		</table>
	</div>	
	<script>
		const coordinatesTable = document.getElementById("coordinatesTable");
		const headerTable = document.getElementById("headerTable");
	</script>
	<div class="clearfix"></div>
	
	<div>
		<form id="query" class="blocklabel" onSubmit="queryOffers()" action="javascript:void(0)" method="post">
			<label for="dateMin">date minimum</label>
			<input id="dateMin" type="date" name="dateMin" value="" oninput="setNewMin()" required />
			<label for="dateMax">date maximum (optionel)</label>
			<input id="dateMax" type="date" name="dateMax" value="" /><br>
			<input id="start" type="hidden" name="start" value="" />
                        <input id="end" type="hidden" name="end" value="" />
			<input id="submitButton" type="submit" value="chercher un voyage" />
		</form>
	</div>
</div>
</div>
	<script> // set form's minimum date to today
		document.getElementById("dateMin").min = new Date().toLocaleDateString('en-ca');
		document.getElementById("dateMax").min = new Date().toLocaleDateString('en-ca');
	</script>
	<p id="result"></p>

</body>
<!-- footer with email -->
</html>
