<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
	$requestData = file_get_contents('php://input');
	if (!empty($requestData)) {
		// constants
		$dbname = "test";
		$table = "offers";
		
		// variables
		$rideID = $requestData;

		$connection = pg_connect("dbname=$dbname") or die('Connection failed: ' . pg_last_error());
		$query = "DELETE FROM $table WHERE id = $1";
		$result = pg_query_params($connection, $query, array($rideID));

		if ($result) {
			echo json_encode(array("success" => true)); // Send success response
		} else {
			// Request body is empty
			http_response_code(400); // Bad Request
			echo json_encode(array("error" => "No data received"));
		}
		pg_close($connection);
	}
} else {
	// Invalid request method
	http_response_code(405); // Method Not Allowed
	echo json_encode(array("error" => "Only POST requests are allowed"));
}
?>

