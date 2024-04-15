/* 
 * called on form submit
 * submit form to php2sql script
 * get data from markers 
 * ask confirmation for waypoints and time
 */
const months = ["", "janvier", "fevrier", "mars", "avril", "mai", "juin", "juillet", "aout", "septembre", "octobre", "novembre", "decembre"];
function confirmRoute(event) {
        event.preventDefault(); // wait for confirmation, needed because async
	let type = document.getElementById("type").value;

        if (!routeOverview && type == "enpassant") { // routeOverview is set by getroute.js
                window.alert("il faut un itinéraire");
                return false;
        } else {
		document.getElementById("route").value = jsonData;
		let timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
		document.getElementById("timezone").value = timezone;
		 //let url1 = 'url1.xml';
                // get first and last waypoints' coordinates. (array is set by showwaypoints.js and modified by sortablewaypoints.js)
		let startID = sortedWaypoints[0]; 
		let startURL = `https://nominatim.openstreetmap.org/reverse?lat=${waypointsMarkers[startID]._latlng.lat.toFixed(6)}&lon=${waypointsMarkers[startID]._latlng.lng.toFixed(6)}&zoom=10`;
		let endID = sortedWaypoints.slice(-1); 
		let endURL = `https://nominatim.openstreetmap.org/reverse?lat=${waypointsMarkers[endID]._latlng.lat.toFixed(6)}&lon=${waypointsMarkers[endID]._latlng.lng.toFixed(6)}&zoom=10`;
                //let url2 = 'url2.xml';
		let dateInput = document.getElementById("date").value;
                let timeInput = document.getElementById("time").value;
		let weekday = getWeekday(dateInput);
		let month = months[Number(dateInput.substr(5,2))];

                let p1 = fetchNominatim(startURL);
                let p2 = fetchNominatim(endURL);

		document.getElementById("start_lng").value = waypointsMarkers[startID]._latlng.lng.toFixed(6);
		document.getElementById("start_lat").value = waypointsMarkers[startID]._latlng.lat.toFixed(6);
		document.getElementById("end_lng").value = waypointsMarkers[startID]._latlng.lng.toFixed(6);
		document.getElementById("end_lat").value = waypointsMarkers[startID]._latlng.lat.toFixed(6);
        
                return Promise.all([p1, p2])
                        .then(([start, end]) => {
				document.getElementById("start_ref").value = start;
				document.getElementById("end_ref").value = end;
				let answer = confirm(`publier un voyage de ${start} à ${end} pour le ${weekday} ${dateInput.substr(8)} ${month} à ${timeInput} (fuseau horaire de : ${timezone}) ?`);

				if (answer) {
					// if confirmed, submit
					event.target.submit();
				}
			}).catch(error => {
				console.error('error : ', error);
			});
        }
}

/*
 * called on form submit by confirmRoute()
 * fetch nominatim geocoder to convert gps
 * to city names
 */
function fetchNominatim(url) {
        return fetch(url)
                .then(response => {
                        if (!response.ok) {
                                throw new Error('Nominatim response was not ok');
                        }
                        return response.text();
                })
                .then(str => {
                        let data = new window.DOMParser().parseFromString(str, "text/xml");
			let city = data.querySelector('city')?.textContent;
                        let ref = data.querySelector('result')?.getAttribute('ref');
                        if (!ref && !city) {
                                throw new Error('could not parse reponse from nominatim');
                        }
                        return city || ref;
                })
        .catch(error => {
                console.error('there was a problem fetching Geocoder : ', error);
		throw error;
        });
}


/*
 * get weekday from 'date' type input with corrected timezone error
 */
const weekdays = ['dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'];
function getWeekday(d) {
	let utcDate = new Date(d + 'T12:00Z');
	return weekdays[utcDate.getDay()];
}


/*
 * show waypoints' coordinates in the "routingWaypoints" table
 * is called by markers.js
 */
const sortedWaypoints = [];
var removedWaypointsIndex = 1; // this var is used to make sure 'start' is replaced when it is removed before finish

function showWaypoints(){
	// make waypointsMarkers object an array (thus iterable) of [number, object] pairs
	let waypointsArray = Object.entries(waypointsMarkers).map(([key, markerObj]) => ([Number(key), markerObj]));
	// make an array with keys only to check for removed/moved markers
	let waypointsKeysArray = waypointsArray.map(([key, _]) => key);


	// check if all entries in table have active markers. if not delete HTML td element
	sortedWaypoints.forEach((wpID, index) => {
		let row2remove = document.getElementById(`wp_${wpID}`);
		if (row2remove && !(waypointsKeysArray.includes(wpID))) {
			if (sortedWaypoints.length > 2) {
				row2remove.remove();
				// also delete unecessary "waypoints" cells in headerTable
				headerTable.getElementsByTagName('tbody')[0].removeChild(headerTable.getElementsByTagName('tbody')[0].rows[1]);
			} else {
				row2remove.cells[0].innerText = "clique la carte";
				removedWaypointsIndex = row2remove.rowIndex; // remember who's been removed
			}
			sortedWaypoints.splice(index, 1);
		}
	});


	// for each marker, check if corresponding div element exist. if so, update coordinates, if not create tr element in draggable coordinatesTable
	waypointsArray.forEach(([wpID, markerObj]) => {
		// text node with latitude, longitude
		let latlng = document.createTextNode(`${markerObj._latlng.lat.toFixed(6)}, ${markerObj._latlng.lng.toFixed(6)}`);
		let row2modify = document.getElementById(`wp_${wpID}`);
		// modify on drag
		if (row2modify && sortedWaypoints.includes(wpID)) {
			row2modify.cells[0].innerText = latlng.nodeValue;
		// or add new element in table
		} else {
			if (sortedWaypoints.length < 2) {
				// was 'start' removed before 'finish' ?
				if (removedWaypointsIndex == 0) {
					var newRowIndex = 0;
					sortedWaypoints.unshift(wpID);
				} else {
					var newRowIndex = sortedWaypoints.length;
					sortedWaypoints.push(wpID);
				}
				let tr = coordinatesTable.rows[newRowIndex];
				tr.id = `wp_${wpID}`;
				tr.cells[0].innerText = latlng.nodeValue;
				removedWaypointsIndex = 1;
			} else {
				// 'length - 1' supposes that the first two markers are start and finish, laters are waypoints
				let coordinateRow = coordinatesTable.insertRow(sortedWaypoints.length - 1);
					let coordinateCell = coordinateRow.insertCell(0);
					let dragCell = coordinateRow.insertCell(1);
						let dragBar = document.createElement("i");
				let headerRow = headerTable.insertRow(sortedWaypoints.length - 1);
					let th = headerRow.insertCell(0); // actual html element created is <td>

				coordinateRow.id = `wp_${wpID}`;
				coordinateRow.classList.add("ui-sortable-handle");
				coordinateCell.innerText = latlng.nodeValue;
				dragBar.classList.add("dragBars");
				dragCell.appendChild(dragBar);

				// also add "waypoints" cells in headerTable as needed
				th.innerText = "waypoint";
				sortedWaypoints.splice(sortedWaypoints.length - 1, 0, wpID);
			}
		}
	});
};

/*
 * fetch route from routing machine
 * post polyline to map
 * save route as jsonData variable
 */
let routeOverview;
let jsonData;
function getRoute() {
	if (sortedWaypoints.length < 2) {
		alert("il faut au moins deux points sur la carte pour calculer un trajet");
	} else {
		routeOverview && routeOverview.remove(map);
		let waypoints = [];	
		sortedWaypoints.forEach(id => {
			// waypoints for osrm resquests must be writen as {longitude},{latitude};...
			let lnglat = `${waypointsMarkers[id]._latlng.lng.toFixed(6)},${waypointsMarkers[id]._latlng.lat.toFixed(6)}`;
			waypoints.push(lnglat);		
		});
		let url = `http://router.project-osrm.org/route/v1/driving/${waypoints.join(';')}?steps=true&overview=full`;
		//let url = 'json/testOSRM.json';
		fetch(url)
			.then(response => {
				if (!response.ok) {
					throw new Error('Network response was not ok');
				}
				return response.json();
			})
			.then(data => {
				let nodes = polyline.decode(data.routes[0].geometry);
				routeOverview = L.polyline(nodes, {color: 'blue', weight: 4, opacity: 0.6}).addTo(map);
				map.fitBounds(routeOverview.getBounds());
				jsonData = JSON.stringify(data);
			})
		.catch(error => {
			console.error('there was a problem fetching the routing machine : ', error);
		});
	}
}


/* jquery-ui function call to make the waypoints' table sortable.
 * Use the 'stop' propriety to keep track of the changes in the
 * 'sortedWaypoints' in order to send the correct request to OSRM server
 */

$(function() {
	$("#coordinatesTable tbody").sortable({
		cursor: "move",
		placeholder: "sortable-placeholder",
		stop: function( event, tr) {
			let ID = tr.item.attr("id");
			let intID = Number(ID.replace(/^\D+/g, "")); // regex to extract NUMBER from "wp_NUMBER" 
			let oldPosition = sortedWaypoints.indexOf(intID);
			let newPosition = tr.item.index();
			sortedWaypoints.splice(oldPosition, 1);
			sortedWaypoints.splice(newPosition, 0, intID);
			//console.log(sortedWaypoints);
		},
		helper: function(e, tr) {
			var $originals = tr.children();
			var $helper = tr.clone();
			$helper.children().each(function(index) {
				// Set helper cell sizes to match the original sizes
				$(this).width($originals.eq(index).width());
			});
			return $helper;
		}
	}).disableSelection();
});


/*
 * load waypoints' coordinates into form
 * used by search.js
 */
function addCoordinates() {
	let startID = sortedWaypoints[0];
	let start = `${waypointsMarkers[startID]._latlng.lng.toFixed(6)} ${waypointsMarkers[startID]._latlng.lat.toFixed(6)}`;
	let endID = sortedWaypoints.slice(-1);
	let end = `${waypointsMarkers[endID]._latlng.lng.toFixed(6)} ${waypointsMarkers[endID]._latlng.lat.toFixed(6)}`;
	
	document.getElementById("start").value = start;
	document.getElementById("end").value = end;
}

/*
 * adjusts type in ride post form also toggle visibility for 'getroute' button
 * and 'detour' input
 */
function addRouting() {
	let enpassantCheckbox = document.getElementById("enpassant");
	let type = document.getElementById("type");
	let routeButton = document.getElementById("getroute");
	let maxDetour = document.getElementById("detour");
	let detourLabel = maxDetour.previousElementSibling;
	let detourOutput = maxDetour.nextElementSibling;

	if (enpassantCheckbox.checked) {
		type.value = "enpassant";
		routeButton.style.display = "block";
		maxDetour.style.display = "block";
		detourLabel.style.display = "block";
		detourOutput.style.display = "block";
	} else {
		type.value = "waypoints";
		routeButton.style.display = "none";
		maxDetour.style.display = "none";
		detourLabel.style.display = "none";
		detourOutput.style.display = "none";
	}
}
