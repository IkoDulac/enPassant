<?php
/* 
 * query database for rides
 * where date and coordinates matche
 */

session_start();

if (!isset($_POST)) {
	exit("erreur : aucun objet transféré");
} else {
	// constants
	$dbname = "test";
	$table = "offers";
	$enpassantTable = "enpassant";
	$polygonsTable = "polygons";
	$connection = pg_connect("dbname=$dbname")
		or die('connection failed: ' . pg_last_error());

	// variables
	$dateMin = isset($_POST['dateMin']) ? $_POST['dateMin'] : null;
	$dateMax = isset($_POST['dateMax']) ? $_POST['dateMax'] : null;
	$start = isset($_POST['start']) ? explode(" ", $_POST['start']) : null;
	$end = isset($_POST['end']) ? explode(" ", $_POST['end']) : null;
	$startRef = isset($_POST['start_ref']) ? $_POST['start_ref'] : null;
	$endRef = isset($_POST['end_ref']) ? $_POST['end_ref'] : null;
	$type = isset($_POST['type']) ? $_POST['type'] : null;

	if ($type == "waypoints") {
		$params = array(
			$dateMin,
			$start[0],
			$start[1],
			$end[0],
			$end[1]
		);
	} elseif ($type == "wp_ref") {
		$params = array(
			$dateMin,
			$startRef,
			$endRef
		);
	}

	if ($dateMax) {
		$dateStr = $type == "waypoints" ? 'BETWEEN $1 AND $6' : 'BETWEEN $1 AND $4';
		array_push($params, $dateMax);
	} else {
		$dateStr = '= $1';
	}

	if ($type == "waypoints") {
		$query = "
			SELECT DISTINCT o.*
			FROM $table o
			INNER JOIN $enpassantTable e ON o.id = e.rideID
			WHERE o.rides_date $dateStr
			AND o.offer_type = 'enpassant'
			AND ST_DWithin(e.coordinates, ST_MakePoint($2::float, $3::float), GREATEST(o.max_detour, 5) * 1000, false)
			AND e.id < (SELECT e2.id
				FROM $enpassantTable e2
				INNER JOIN $table o2 ON e2.rideID = o2.id
				WHERE o2.rides_date $dateStr
				AND ST_DWithin(e2.coordinates, ST_MakePoint($4::float, $5::float), GREATEST(o2.max_detour, 5) * 1000, false)
				ORDER BY e2.id DESC
				LIMIT 1
			)
			
			UNION ALL

			SELECT p.*
			FROM $table p
			JOIN polygons p_start ON p.start_ref = p_start.city_name
			JOIN polygons p_end ON p.end_ref = p_end.city_name
			WHERE p.offer_type = 'polygons'
				AND ST_Within(ST_SetSRID(ST_MakePoint($2::float, $3::float), 4326), p_start.city_bounds)
				AND ST_Within(ST_SetSRID(ST_MakePoint($4::float, $5::float), 4326), p_end.city_bounds)
				AND p.rides_date $dateStr

			UNION ALL

			SELECT w.*
			FROM $table w
			WHERE w.offer_type = 'waypoints'
				AND w.rides_date $dateStr
				AND ST_DWithin(ST_SetSRID(ST_MakePoint($2::float, $3::float), 4326), w.start_coordinates, 15000)
				AND ST_DWithin(ST_SetSRID(ST_MakePoint($4::float, $5::float), 4326), w.end_coordinates, 15000)
			;";

		$result = pg_query_params($connection, $query, $params) or die('Query failed: ' . pg_last_error());
		$rows = pg_fetch_all($result);

		pg_free_result($result);
		pg_close($connection);

	} elseif ($type == "wp_ref") {
		$query = "
			SELECT *
			FROM $table
			WHERE rides_date $dateStr
			AND start_ref = $2
			AND end_ref = $3
			;";
		$result = pg_query_params($connection, $query, $params) or die('Query failed: ' . pg_last_error());
		$rows = pg_fetch_all($result);

		pg_free_result($result);
		pg_close($connection);
	}
}
?>

<?php if ($rows): ?>
	<table id='ridesQuery'>
	<tr>
		<th>sieges</th>
		<th>trajet</th>
		<th>date</th>
		<th>heure</th>
		<th>description</th>
	</tr>
<?php foreach ($rows as $row): ?>
	<tr>
		<td><?php echo $row['seats']; ?></td>
		<td><?php echo $row['start_ref'] . " => " . $row['end_ref']; ?></td>
		<td><?php echo $row['rides_date']; ?></td>
		<td><?php echo $row['time']/* . " " . $row['timezone']*/; ?></td>
	<td><?php echo $row['description']; ?></td>
	<td style="border: none; cursor: help;" class="tooltip">
	<a href="/profile.php?usr=<?php echo $row['userid']; ?>">contact
	<span class="tooltiptext left">certains profils ne sont visibles que par les utilisatrices</span>
	</a></td>
	</tr>
<?php endforeach; ?>
</table>
<?php else: ?>
	<p>Aucune offre au babillard. Achete-toi donc un char, esti de pauvre !</p>
<?php endif; ?>
