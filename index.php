<!DOCTYPE html>
<html>
<head>
  <meta charset='utf-8'>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>en passant</title>
  <link rel="icon" type="image/x-icon" href="img/favicon.ico">
  <link rel="stylesheet" href="css/leaflet.css">
  <link rel="stylesheet" href="css/rideShare.css">
  <link rel="stylesheet" href="css/jquery-ui.css">
  <script src="js/leaflet/leaflet.js"></script>
  <script src="js/jquery-3.7.1.min.js"></script>
  <script src="js/jquery-ui-1.13.2.sortable/jquery-ui.min.js"></script>
  <script>
</script>

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
	<table id="routingWaypoints" class="responsive-table">
		<tr id="headerRow" style="text-align:right;">
			<th>départ :</th>
			<th>arrivée :</th>
		</tr>
		<tr id="coordinatesRow" class="ui-state-default">
			<td>clique la carte</td>
			<td>clique la carte</td>
		</tr>
	</table>
	
	<script src="js/waypointmarkers.js"></script>
	<script src="js/showwaypoints.js"></script>
	<script src="js/getroute.js"></script>
	<button onclick="getRoute()">Get route !</button>
		
</body>
<!-- footer with email -->
</html>
