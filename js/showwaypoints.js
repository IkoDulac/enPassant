// show waypoints' coordinates in the "routingWaypoints" table

const tableDataIDsArray = [];
const coordinatesRow = document.getElementById("coordinatesRow");
const headerRow = document.getElementById("headerRow");

function showWaypoints(){
	// make waypointsMarkers object an array (thus iterable) of [number, object] pairs
	let waypointsArray = Object.entries(waypointsMarkers).map(([key, markerObj]) => ([Number(key), markerObj]));
	// make an array with keys only to check for removed/moved markers
	let waypointsKeysArray = waypointsArray.map(([key, _]) => key);

	// check if all entries in table have active markers. if not delete HTML td element
	tableDataIDsArray.forEach((wpID, index) => {
		let cell2remove = document.getElementById(`wp_${wpID}`);
		if (cell2remove && !(waypointsKeysArray.includes(wpID))) {
			// also delete unecessary "waypoints" cells in headerRow
			if (tableDataIDsArray.length > 2) {
				cell2remove.remove();
				headerRow.removeChild(headerRow.children[1]);
			} else {
				cell2remove.innerText = "clique la carte";
			}
			tableDataIDsArray.splice(index, 1);
			//console.log(tableDataIDsArray);
		}
	});

	// for each marker, check if corresponding div element exist. if so, update coordinates, if not create draggable div element
	waypointsArray.forEach(([wpID, markerObj]) => {
		// text node with latitude, longitude
		let latlng = document.createTextNode(`${markerObj._latlng.lat.toFixed(6)}, ${markerObj._latlng.lng.toFixed(6)}`);
		let cell2modify = document.getElementById(`wp_${wpID}`);
		// modify on drag
		if (cell2modify && tableDataIDsArray.includes(wpID)) {
			cell2modify.innerText = latlng.nodeValue;
		// or add new element in table
		} else {
			if (tableDataIDsArray.length < 2) {
				let td = document.getElementById("coordinatesRow").children[tableDataIDsArray.length];
				td.id = `wp_${wpID}`;
				td.innerText = latlng.nodeValue;
				tableDataIDsArray.push(wpID);
			} else {
			let td = coordinatesRow.insertCell(tableDataIDsArray.length - 1);
			let th = headerRow.insertCell(tableDataIDsArray.length - 1);
			td.id = `wp_${wpID}`;
			td.classList.add("ui-sortable-handle");
			td.innerText = latlng.nodeValue;
			// also add "waypoints" cells in headerRow as needed
			th.innerText = "waypoint";
			tableDataIDsArray.splice(tableDataIDsArray.length - 1, 0, wpID);
			}
			console.log(tableDataIDsArray);
		}
	});
};
