/*
 * add or remove draggable markers on the map on click.
 */ 

const dummyArray = [];
const waypointsMarkers = {};

map.on('click', function(e) {
	// control number of markers (max 5, max 2 for searches)
	if (!(Object.keys(waypointsMarkers).length > 4 || (Object.keys(waypointsMarkers).length > 1 && (typeof searchRide !== 'undefined')))) {

		let id = dummyArray.push(undefined); // set id as a 'SERIAL' sql-like int
		
		clearRoute();
		// duplicate leaflet's marker as 'waypointsMarkers[id]'
		waypointsMarkers[id] = new L.marker(e.latlng, {
			draggable: true,
			autoPan: true
		// make marker and its duplicate removable
		}).addTo(map).on('click', function(e) {
			e.target.remove();
			delete waypointsMarkers[id];
			showWaypoints();
			clearRoute();
		// update coordinates on drag
		}).on('drag', function() {
			let coordinates = waypointsMarkers[id].getLatLng();
			waypointsMarkers[id].setLatLng(coordinates)
			showWaypoints();
			clearRoute();
		});
		// custom function, passes coordinates to DOM
		showWaypoints();
	}
});

// clear last polyline from the map
function clearRoute() {
	if (routeOverview) {
		routeOverview.remove(map);
		routeOverview = undefined;
	}
}
