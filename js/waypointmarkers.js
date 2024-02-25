// add or remove draggable markers on the map by clicking on it. put coordinates in draggable boxes for the user to reorder before they can launch routing

// used to create ids for markers
const dummyArray = [];
const waypointsMarkers = {};

// activate function on map click
map.on('click', function(e) {
	// add an empty entry to 'dummyArray' and let id = new length of array (never 0). array will grow up undefinately, until page is refreshed.
	let id = dummyArray.push(undefined);

	// leaflet adds a marker on the map which is duplicated as 'waypointsMarkers[id]'
	waypointsMarkers[id] = new L.marker(e.latlng, {
		draggable: true,
		autoPan: true
	// make it removable (and remove the duplicate)
	}).addTo(map).on('click', function(e) {
		e.target.remove();
		delete waypointsMarkers[id];
		showWaypoints();
	// update waypointsMarkers's coordinates on drag
	}).on('drag', function() {
		let coordinates = waypointsMarkers[id].getLatLng();
		waypointsMarkers[id].setLatLng(coordinates)
		showWaypoints();
	});
	showWaypoints();
});

