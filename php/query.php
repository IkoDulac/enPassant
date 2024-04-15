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
	$connection = pg_connect("dbname=$dbname")
		or die('connection failed: ' . pg_last_error());

	// variables
	$dateMin = $_POST['dateMin'];
	$dateMax = $_POST['dateMax'];
	$start = explode(" ", $_POST['start']);
	$end = explode(" ", $_POST['end']);

	if (empty($dateMax)) {
		$query = "
			SELECT DISTINCT o.*
			FROM $table o
			INNER JOIN $enpassantTable e ON o.id = e.rideID
			WHERE o.rides_date = $1 
			AND ST_DWithin(e.coordinates, ST_MakePoint($2::float, $3::float), GREATEST(o.max_detour, 5) * 1000, false)
			AND e.id < (SELECT e2.id
				FROM $enpassantTable e2
				INNER JOIN $table o2 ON e2.rideID = o2.id
				WHERE o2.rides_date = $1
				AND ST_DWithin(e2.coordinates, ST_MakePoint($4::float, $5::float), GREATEST(o2.max_detour, 5) * 1000, false)
				ORDER BY e2.id DESC
				LIMIT 1
			);";
		$result = pg_query_params($connection, $query, array($dateMin, $start[0], $start[1], $end[0], $end[1])) or die('Query failed: ' . pg_last_error());
 	} else {
		$query = "
			SELECT DISTINCT o.*
			FROM $table o
			INNER JOIN $enpassantTable e ON o.id = e.rideID
			WHERE o.rides_date BETWEEN $1 AND $6
			AND ST_DWithin(e.coordinates, ST_MakePoint($2::float, $3::float), GREATEST(o.max_detour, 5) * 1000, false)
			AND e.id < (SELECT e2.id
				FROM $enpassantTable e2
				INNER JOIN $table o2 ON e2.rideID = o2.id
				WHERE o2.rides_date BETWEEN $1 AND $6
				AND ST_DWithin(e2.coordinates, ST_MakePoint($4::float, $5::float), GREATEST(o2.max_detour, 5) * 1000, false)
				ORDER BY e2.id DESC
				LIMIT 1
			);";
		$result = pg_query_params($connection, $query, array($dateMin, $start[0], $start[1], $end[0], $end[1], $dateMax)) or die('Query failed: ' . pg_last_error());
  	}
	$rows = pg_fetch_all($result);

	// Free resultset
	pg_free_result($result);
	pg_close($connection);
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
		<td style="border: none; cursor: help;" class="tooltip"><a href="/profile.php?usr=<?php echo $row['userid']; ?>">contact<span class="tooltiptext left">certains profiles ne sont visibles que par les utilisatrices</span></a></td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php else: ?>
	<p>Aucune offre au babillard. Achete-toi donc un char, esti de pauvre !</p>
<?php endif; ?>
