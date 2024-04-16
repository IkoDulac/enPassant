<?php
/*
 * functions for profile page
 * query psql 'users' table and output html with infos
 */

// database connection constants
$dbname = "test";
$usersTable = "users";
$offersTable = "offers";
$reviewsTable = "reviews";
$username = "";

/*
 * query avatar and contact info
 */
function queryProfile($userID) {
	global $dbname, $usersTable, $connection, $username;

	$query = "SELECT * FROM $usersTable WHERE id = $1;";
	$result = pg_query_params($connection, $query, array($userID));
	$row = pg_fetch_assoc($result);
	pg_free_result($result);

	if ($row) {
		$contactWithBr = nl2br($row['contact_info']);
		$username = $row['username'];
		include 'navbar.html';
		$html = <<<EOD
			<h1 class="contactinfo">$username</h1>
			<div class="avatar"><img src="{$row['img_path']}" alt="avatar"></div>
			<h1 class="contactinfo">contacts (en ordre de préférence)</h1>
			<p class="contactinfo"><span>$contactWithBr</span></p>
		EOD;
		echo $html;

	} else {
		errorPage($userID);
	}
}

/*
 * query rides from offers table
 */
function queryRides($userID) {
	global $dbname, $offersTable, $connection;

	$query = "SELECT * FROM $offersTable WHERE userID = $1;";
	$result = pg_query_params($connection, $query, array($userID)) or die("Query failed: " . pg_last_error());
	$rides = pg_fetch_all($result);
	pg_free_result($result);
	
	if ($rides) {
		$html = <<<EOD
			<!-- output user's rides to html table -->
			<table id="usersrides">
				<tr>
					<th>sieges</th>
					<th>trajet</th>
					<th>date</th>
					<th>heure</th>
					<!--<th>fuseau horaire</th>-->
					<th>enpassant</th>
					<th>description</th>
				</tr>
		EOD;
		echo $html;
		foreach ($rides as $ride) {
			$time = substr($ride['time'], 0, 5);
			$ep = $ride['offer_type'] == "enpassant" ? "oui" : "non";
			$html = <<<EOD
			<tr>
			<td>{$ride['seats']}</td>
			<td>{$ride['start_ref']} => {$ride['end_ref']}</td>
			<td>{$ride['rides_date']}</td>
			<td>$time</td>
			<td>$ep</td>
			<td>{$ride['description']}</td>
			</tr>
			EOD;
			echo $html;
		}
		echo "</table></body></html>";
	} else {
		echo "<p>aucune offres enregistrées</p>";
	}
}

/*
 * query reviews
 */
function queryReviews($userID) {
	global $dbname, $reviewsTable, $connection;
		$query = "SELECT * FROM $reviewsTable WHERE user_to = $1 OR user_from = $1;";
		$result = pg_query_params($connection, $query, array($userID)) or die("Query failed: " . pg_last_error());
		$reviews = pg_fetch_all($result);
		pg_free_result($result);

		if ($reviews) {
			$html = <<<EOD
			<table>
				<tr>
					<th>de qui</th>
					<th>à qui</th>
					<th>l'avis</th>
					<th>date</th>
				<tr>
			EOD;
			echo $html;
		foreach ($reviews as $review) {
			$brReview = nl2br($review['review']);
			$html = <<<EOD
			<tr>
			<td><a href="profile.php?usr={$review['user_from']}">{$review['username_from']}</a></td>
			<td><a href="profile.php?usr={$review['user_to']}">{$review['username_to']}</a></td>
			<td>$brReview</td>
			<td>{$review['date_writen']}</td>
			</tr>
			EOD;
			echo $html;

		}
			echo "</table>";
		} else {
			echo "<p>aucun avis</p>";
		}
}

/*
 * if wrong userID is provided, print error page
 */
function errorPage($usr) {
	$html = <<<EOD
	<div> 
	<h1> 404 </h1> 
	<p>il n'y a pas d'entrée dans la base de donnée pour usr=$usr</p> 
	<a href="searchuser.php">nouvelle recherche</a> 
	</div> 
	</body> 
	</html>
	EOD;
	exit($html);
}
?>
