<!DOCTYPE html>
<html>
<head>
  <meta charset='utf-8'>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>qbc-rideshare</title>
  <link rel="icon" type="image/x-icon" href="img/favicon.ico">
  <link rel="stylesheet" href="css/leaflet.css">
  <link rel="stylesheet" href="css/rideShare.css">
  <script src="js/leaflet/leaflet.js"></script>
  <script src="js/jquery-3.7.1.min.js"></script>
</head>
<body>
	<div id="map"></div>
	<script>
		var map = L.map('map').setView([47,-70], 6);
		L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
			maxZoom: 19,
			attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
		}).addTo(map);
	</script>
<!-- recommended by osm : <a href="https://www.openstreetmap.org/fixthemap">fix the map</a> -->
<?php /*	<script>
		// send osrm request and process routes' polylines
		var serialized_data = `<?php include 'php/getnodes.php';?>`;
		var polylineNodes = JSON.parse(serialized_data);
		// add polylines to map and zoom map to main route's polyline
		var mainroute = L.polyline(polylineNodes, {color: 'blue', weight: 4, opacity: 0.6}).addTo(map);
		map.fitBounds(mainroute.getBounds());
	</script> */ ?>
	<script src="js/waypointmarkers.js"></script>

</body>
<!-- footer with email -->
</html>
