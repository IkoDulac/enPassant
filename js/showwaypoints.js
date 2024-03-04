// show waypoints' coordinates in the "routingWaypoints" table

const tableDataIDsArray = [];
const coordinatesTable = document.getElementById("coordinatesTable");
const headerTable = document.getElementById("headerTable");
var removedWaypointsIndex = 1; // this var is used to make sure 'start' is replaced when removed before finish

function showWaypoints(){
	// make waypointsMarkers object an array (thus iterable) of [number, object] pairs
	let waypointsArray = Object.entries(waypointsMarkers).map(([key, markerObj]) => ([Number(key), markerObj]));
	// make an array with keys only to check for removed/moved markers
	let waypointsKeysArray = waypointsArray.map(([key, _]) => key);


	// check if all entries in table have active markers. if not delete HTML td element
	tableDataIDsArray.forEach((wpID, index) => {
		let row2remove = document.getElementById(`wp_${wpID}`);
		if (row2remove && !(waypointsKeysArray.includes(wpID))) {
			if (tableDataIDsArray.length > 2) {
				row2remove.remove();
				// also delete unecessary "waypoints" cells in headerTable
				headerTable.getElementsByTagName('tbody')[0].removeChild(headerTable.getElementsByTagName('tbody')[0].rows[1]);
			} else {
				row2remove.cells[0].innerText = "clique la carte";
				removedWaypointsIndex = row2remove.rowIndex; // remember who's been removed
			}
			tableDataIDsArray.splice(index, 1);
		}
	});


	// for each marker, check if corresponding div element exist. if so, update coordinates, if not create tr element in draggable coordinatesTable
	waypointsArray.forEach(([wpID, markerObj]) => {
		// text node with latitude, longitude
		let latlng = document.createTextNode(`${markerObj._latlng.lat.toFixed(6)}, ${markerObj._latlng.lng.toFixed(6)}`);
		let row2modify = document.getElementById(`wp_${wpID}`);
		// modify on drag
		if (row2modify && tableDataIDsArray.includes(wpID)) {
			row2modify.cells[0].innerText = latlng.nodeValue;
		// or add new element in table
		} else {
			if (tableDataIDsArray.length < 2) {
				// was 'start' removed before 'finish' ?
				if (removedWaypointsIndex == 0) {
					var newRowIndex = 0;
				} else {
					var newRowIndex = tableDataIDsArray.length;
				}
				let tr = coordinatesTable.rows[newRowIndex];
				tr.id = `wp_${wpID}`;
				tr.cells[0].innerText = latlng.nodeValue;
				tableDataIDsArray.push(wpID);
				removedWaypointsIndex = 1;
			} else {
				// 'length - 1' supposes that the first two markers are start and finish, laters are waypoints
				let coordinateRow = coordinatesTable.insertRow(tableDataIDsArray.length - 1);
					let coordinateCell = coordinateRow.insertCell(0);
					let fabarCell = coordinateRow.insertCell(1);
						let fabar = document.createElement("i");
				let headerRow = headerTable.insertRow(tableDataIDsArray.length - 1);
					let th = headerRow.insertCell(0); // actual html element created is <td>

				coordinateRow.id = `wp_${wpID}`;
				coordinateRow.classList.add("ui-sortable-handle");
				coordinateCell.innerText = latlng.nodeValue;
				fabar.classList.add("fa-bars");
				fabarCell.appendChild(fabar);

				// also add "waypoints" cells in headerTable as needed
				th.innerText = "waypoint";
				tableDataIDsArray.splice(tableDataIDsArray.length - 1, 0, wpID);
			}
		}
	});
};
