const path2query = 'php/query.php';

function queryOffers(formID) {
	let form = document.getElementById(formID);
	let formData = new FormData(form);
	if (formID == "wpQueryForm") {
		let startID = sortedWaypoints[0];
		formData.append('start', `${waypointsMarkers[startID]._latlng.lng.toFixed(6)} ${waypointsMarkers[startID]._latlng.lat.toFixed(6)}`);
		let endID = sortedWaypoints[1];
		formData.append('end', `${waypointsMarkers[endID]._latlng.lng.toFixed(6)} ${waypointsMarkers[endID]._latlng.lat.toFixed(6)}`);
	}

	fetch(path2query, {
		method: 'POST',
		body: formData
	})
	.then(response => {
		if (!response.ok) {
			throw new Error('failed to fetch response from server');
		}
		return response.text();
	})
	.then(data => {
		document.getElementById("result").innerHTML = data;
	})
	.catch(error => {
		console.error('error sending data to server : ', error);
	});
}
