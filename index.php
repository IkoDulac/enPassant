<?php
session_start();
if (isset($_SESSION['logged'])) {
	header('Location: home.php');
}
?>

<!DOCTYPE html>
<html>
<head>
        <title>Poney Nordest</title>
<?php include 'html/header.html'; ?>
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
	<div id="splash"><h1>Poney Nordest</h1></div>
	<hr>

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
	<script>const enPassant = false; //used to limit number of markers on map</script>

<!-- recommended by osm : <a href="https://www.openstreetmap.org/fixthemap">fix the map</a> -->
	
<div class="forms">
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
		<form id="wpQueryForm" class="blocklabel" onSubmit="queryOffers(this.id)" action="javascript:void(0)" method="post">
			<label for="dateMin">date minimum</label>
			<input id="dateMin" type="date" name="dateMin" value="" oninput="setNewMin(this.id, 'dateMax')" required />
			<label for="dateMax">date maximum (optionel)</label>
			<input id="dateMax" type="date" name="dateMax" value="" /><br>
			<input id="start" type="hidden" name="start" value="" required />
                        <input id="end" type="hidden" name="end" value="" required />
                        <input id="type" type="hidden" name="type" value="waypoints" />
			<input id="submitButton" type="submit" value="chercher un voyage" />
		</form>
	</div>
</div>

<div id="result"></div>
</div>
	<script> // set form's minimum date to today
		document.getElementById("dateMin").min = new Date().toLocaleDateString('en-ca');
		document.getElementById("dateMax").min = new Date().toLocaleDateString('en-ca');
	</script>

</body>
<!-- footer with email -->
</html>
