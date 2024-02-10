const wpKey = [];
const wpMarker = {};
map.on('click', function(e) {
	//if (markers[0] != undefined) {};
	let id = wpKey.push(undefined);
	wpMarker[id] = new L.marker(e.latlng, {
		draggable: true,
		autoPan: true
	}).addTo(map).on('click', function(e) {
		e.target.remove();
		delete wpMarker[id];
		console.log(wpMarker);
	});
	//markers[0].setLatLng(lat, lng);
	console.log(id);
	console.log(wpMarker[id]._latlng);// latlng.lat or latlng.lat
});
