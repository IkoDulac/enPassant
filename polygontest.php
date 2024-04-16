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
	<div id="map"></div>
<!--	<div id="wpContainer"></div> -->
	<script>
		var map = L.map('map').setView([47,-70], 6);
		L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
			maxZoom: 19,
			attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
		}).addTo(map);

		function makeArray(jsonData) {
			const json = jsonData;
			const cityPolygon = [];
			if (json && Array.isArray(json.coordinates)) {
				json.coordinates.forEach(coordinates => {
					coordinates.forEach(coord => {
						let [lng, lat] = coord;
						cityPolygon.push([lat, lng]);
					});
				});
			}
			return cityPolygon;
		}

		const jsonUrl = 'json/trois-rivieres.json';

		fetch(jsonUrl)
			.then(response => {
				return response.json();
				//console.log(response);
			})
			.then(jsonData => {
			//console.log(jsonData);
				let cityPolygon = makeArray(jsonData.geojson);
				let polygon = L.polygon(cityPolygon, {color: 'red'}).addTo(map);
				map.fitBounds(polygon.getBounds());
			});

	</script>

</body>
</html>
