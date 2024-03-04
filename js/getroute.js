/* for future use with a running OSRM instance
// call routing machine

	function getRoute() {
		// send osrm request and process routes' polylines
		var serialized_data = `<?php include 'php/getnodes.php';?>`;
		var polylineNodes = JSON.parse(serialized_data);// this line needs to change for a more appropriate method such as var = XMLHttpRequest(), var.onload
		// add polylines to map and zoom map to main route's polyline
		var mainroute = L.polyline(polylineNodes, {color: 'blue', weight: 4, opacity: 0.6}).addTo(map);
		map.fitBounds(mainroute.getBounds());
	}
*/

/* command reminder
latlng.join('<br>');*/


// temporary develeppoment function, console.log coordinates in correct order for a OSRM request
function getRoute() {
	tableDataIDsArray.forEach(id => {
		let latlng = waypointsMarkers[id]._latlng;
		console.log(latlng);
	});
}
