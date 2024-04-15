
<?php
/* 
 * query database for all user's rides
 */

// constants
$dbname = "test";
$table = "offers";

// variables
$userID = isset($_SESSION['userID']) ? $_SESSION['userID'] : null; 

if ($userID !== null) {
	// query
	$connection = pg_connect("dbname=$dbname")
		or die("connection failed: " . pg_last_error());
	$query = "SELECT * FROM $table o WHERE o.userID = $1";
	$result = pg_query_params($connection, $query, array($userID)) or die("Query failed: " . pg_last_error());
	$rows = pg_fetch_all($result);

	// Free resultset
	pg_free_result($result);
	pg_close($connection);
} else {
	$rows = null;
}
?>

<?php if ($rows): ?>
	<form id="editridesform">
	<table id="myrides">
		<tr>
			<th>sieges</th>
			<th>trajet</th>
			<th>date</th>
			<th>heure</th>
			<!--<th>fuseau horaire</th>-->
			<th>enpassant</th>
			<th>description</th>
		</tr>
	<?php foreach ($rows as $row): ?>
		<tr>
		<td><input name="seats" type="number" size="3" min="1" value="<?php echo (int)$row['seats']; ?>" data-id="<?php echo $row['id'] ?>" /></td>
			<td><?php echo $row['start_ref'] . " => " . $row['end_ref']; ?></td>
			<td><input name="date" type="date" value="<?php echo $row['rides_date']; ?>" data-id="<?php echo $row['id'] ?>" /></td>
			<td><input name="time" type="time" value="<?php echo substr($row['time'], 0, 5); ?>" data-id="<?php echo $row['id'] ?>" /></td>
			<?php /*<td><?php echo $row['timezone']; ?></td>*/ ?>
			<td><?php echo $row['offer_type'] == "enpassant" ? "oui" : "non"; ?></td>
			<td><textarea name="description" data-id="<?php echo $row['id'] ?>"><?php echo $row['description']; ?></textarea></td>
			<td class="td-noborder tooltip"><button type="button" class="td-button" onclick="editOffer(<?php echo $row['id']; ?>)">&#x270F</button><span class="tooltiptext left">enregistrer les modifications</span></td>
			<td class="td-noborder tooltip"><button type="button" class="td-button" onclick="deleteOffer(<?php echo $row['id']; ?>)">&#x1F5D1</button><span class="tooltiptext left">annuler le voyage</span></td>
		</tr>
	<?php endforeach; ?>
	</table>
	</form>
<?php else: ?>
	<p>aucune offre au babillard. J'imagine que t'es un·e esti de pauvre avec pas de char ?!</p>
<?php endif; ?>
