<?php
/*
 * query offers' table and display button group with all hashtags
 */

// psql constants
$dbname = "test";
$table = "offers";
$connection = pg_connect("dbname=$dbname")
	or die('connection failed: ' . pg_last_error());

$query = "
	SELECT DISTINCT * FROM (
		SELECT UNNEST(arr) AS unnest
		FROM (
			SELECT regexp_split_to_array(description, '\s+') AS arr
			FROM offers
		) AS element
	) AS words
	WHERE words.unnest ~ '^#';
	";
$result = pg_query($connection, $query);

if (!$result) {
	echo "<p>aucun hashtags dans les offres en ce moment !</p>";
	exit;
}

echo "<div class='button-group'>";

while ($hash = pg_fetch_row($result)) {
	$tag = ltrim($hash[0], '#');
	$html = <<<EOD
		<button onclick="queryHash('$tag')">#$tag</button>
		EOD;
	echo $html;
}

echo "</div>";

pg_free_result($result);
pg_close($connection);
?>
