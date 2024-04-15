
<?php
/* 
 * update one row in the offers table with user's input 
 */
if ($_SERVER["REQUEST_METHOD"] === "POST") {
	// constants
	$dbname = "test";
	$table = "offers";

	// variables
	$id = $_POST['id'];
	$seats = (int)$_POST['seats'];
	$date = $_POST['date'];
	$time = !empty($_POST['time']) ? $_POST['time'] : null;
	$description = htmlspecialchars($_POST['description']);

	// query
	$connection = pg_connect("dbname=$dbname")
		or die("connection failed: " . pg_last_error());
	$query = "UPDATE $table SET seats = $2, rides_date = $3, time = $4, description = $5 WHERE id = $1;";
	$result = pg_query_params($connection, $query, array($id, $seats, $date, $time, $description)) or die("Query failed: " . pg_last_error());

	if ($result) {
		echo json_encode(array("success" => true));
	} else {
		http_response_code(400);
		echo json_encode(array("error" => "No data received"));
	}
	pg_close($connection);
} else {
	http_response_code(405);
	echo json_encode(array("error" => "Only POST requests are allowed"));
}
?>
