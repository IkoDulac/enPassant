const path2query = 'php/query.php';

function queryOffers() {

	let formData = new FormData();
	formData.append('dateMin', document.getElementById("dateMin").value);
	formData.append('dateMax', document.getElementById("dateMax").value);
	let startID = sortedWaypoints[0];
        formData.append('start', `${waypointsMarkers[startID]._latlng.lng.toFixed(6)} ${waypointsMarkers[startID]._latlng.lat.toFixed(6)}`);
        let endID = sortedWaypoints[1];
        formData.append('end', `${waypointsMarkers[endID]._latlng.lng.toFixed(6)} ${waypointsMarkers[endID]._latlng.lat.toFixed(6)}`);

	fetch(path2query, {
		method: 'POST',
		body: formData
	})
	.then(response => {
		if (!response.ok) {
			throw new Error('reponse from post2sql is not ok');
		}
		return response.text();
	})
	.then(data => {
		document.getElementById("result").innerHTML = data;
	})
	.catch(error => {
		console.error('error sending to post2sql : ', error);
	});
}
