<?php
/* script launched by post.php's form
 * sends ride information to the ride offers table
 * sends additional informations according to offer type
 */

session_start();

if (!isset($_POST)) {
	exit("erreur : aucun objet OSRM transféré");
} else {
	// constants
	$path2home = "/home.php";
	$dbname = "test";
	$table = "offers";
	$enpassantTable = "enpassant";
	$connection = pg_connect("dbname=$dbname")
		or die('connection failed: ' . pg_last_error());

	// variables, autoset from session and form
	$route = isset($_POST['route']) ? json_encode(json_decode($_POST['route'], true)) : null;
	$offerType = isset($_POST['type']) ? $_POST['type'] : null;
	$date = isset($_POST['date']) ? $_POST['date'] : null;
	// empty $time must be explicitely set to null for sql insert
	$time = !empty($_POST['time']) ? $_POST['time'] : null;
	$timezone = isset($_POST['timezone']) ? $_POST['timezone'] : null;
	$startRef = isset($_POST['start_ref']) ? $_POST['start_ref'] : null;
	$startLng = isset($_POST['start_lng']) ? $_POST['start_lng'] : null;
	$startLat = isset($_POST['start_lat']) ? $_POST['start_lat'] : null;
	$endRef = isset($_POST['end_ref']) ? $_POST['end_ref'] : null;
	$endLng = isset($_POST['end_lng']) ? $_POST['end_lng'] : null;
	$endLat = isset($_POST['end_lat']) ? $_POST['end_lat'] : null;
	$description = isset($_POST['description']) ? htmlspecialchars($_POST['description']) : null;
	$detour = isset($_POST['detour']) ? $_POST['detour'] : null;
	$seats = isset($_POST['seats']) ? $_POST['seats'] : null;
	$userID = $_SESSION['userID'];
	
	// db querys according to offerType
	if ($offerType == "enpassant") {
		// returned id (type serial) used as 'rideID' in enpassant table
		$query = "INSERT INTO $table (userID, rides_date, time, timezone, start_ref, end_ref, offer_type, seats, max_detour, description) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10) RETURNING id;";
		$result = pg_query_params($connection, $query, array($userID, $date, $time, $timezone, $startRef, $endRef, $offerType, $seats, $detour, $description));
		if ($result) {
			$serialID = pg_fetch_result($result, 0, 0);
			// insert all intersections' coordinates, one pair per row
			$enPassantQuery = "
				WITH data AS (
					SELECT jsonb_path_query_array($1::jsonb, '$.routes[*].legs[*].steps[*].intersections[*].location') AS lng_lat
				)
				INSERT INTO $enpassantTable (rideID, rides_date, coordinates)
				SELECT $2, $3, ST_MakePoint((coordinate->>0)::float, (coordinate->>1)::float)
				FROM data,
					jsonb_array_elements(data.lng_lat) AS coordinate;
			";

			$result2 = pg_query_params($connection, $enPassantQuery, array($route, $serialID, $date));

			if ($result2) {
				//echo "Data inserted successfully!";
				header("location: $path2home");
				exit();
			} else {
				echo "Error inserting data: " . pg_last_error($connection);
			}
		} else {
			echo "Error inserting data: " . pg_last_error($connection);
		}
	} elseif ($offerType == "waypoints") {
		$query = "INSERT INTO $table (userID, rides_date, time, timezone, start_ref, start_coordinates, end_ref, end_coordinates, offer_type, seats, description) VALUES ($1, $2, $3, $4, $5, ST_MakePoint($6::float, $7::float), $8, ST_MakePoint($9::float, $10::float), $11, $12, $13);";
		$result = pg_query_params($connection, $query, array($userID, $date, $time, $timezone, $startRef, $startLng, $startLat, $endRef, $endLng, $endLat, $offerType, $seats, $description));
		if ($result) {
			//echo "Data inserted successfully!";
			header("location: $path2home");
			exit();
		} else {
			echo "Error inserting data: " . pg_last_error($connection);
		}
	} elseif ($offerType == "polygones") {
		$query = "INSERT INTO $table (userID, rides_date, time, timezone, start_ref, end_ref, offer_type, seats, description) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9);";
		$result = pg_query_params($connection, $query, array($userID, $date, $time, $timezone, $startRef, $endRef, $offerType, $seats, $description));
		if ($result) {
			header("location: $path2home");
			exit();
		}
	} else {
		echo "wrong offer type";
	}
	pg_close($connection);
}
?>
