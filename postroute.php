<!DOCTYPE html>
<html>
<head>
  <title>en passant</title>
  <?php include 'header.html'; ?>
  <link rel="stylesheet" href="css/leaflet.css">
  <link rel="stylesheet" href="css/jquery-ui.css">
  <script src="js/leaflet/leaflet.js"></script>
  <script src="js/jquery-3.7.1.min.js"></script>
  <script src="js/jquery-ui-1.13.2.sortable/jquery-ui.min.js"></script>
</head>

<body>
	<div id="map"></div>
	<div id="wpContainer"></div>
	<script>
		var map = L.map('map').setView([47,-70], 6);
		L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
			maxZoom: 19,
			attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
		}).addTo(map);
	</script>
<!-- recommended by osm : <a href="https://www.openstreetmap.org/fixthemap">fix the map</a> -->
	
	
	<script src="js/sortablewaypoints.js"></script>
	<div class="waypointTable">
		<table id="headerTable">
			<tr><th>départ :</th></tr>
			<tr><th>arrivée :</th></tr>
		</table>
	</div>	
	<div class="waypointTable">
		<table id="coordinatesTable" class="ui-state-default">
			<tr><td>clique la carte</td><td><i class="fa-bars"></i></td></tr>
			<tr><td>clique la carte</td><td><i class="fa-bars"></i></td></tr>
		</table>
	</div>	
	<div class="clearfix"></div>
	
	<script src="js/waypointmarkers.js"></script>
	<script src="js/showwaypoints.js"></script>
	<script src="js/getroute.js"></script>
	<button onclick="getRoute()">Get route !</button>

</body>
<!-- footer with email -->
</html>
