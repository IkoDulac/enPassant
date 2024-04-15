<?php
session_start();

ini_set('display_errors',1);
error_reporting(E_ALL);
// constants
$dbname = "test";
$table = "users";
$target_dir = "/img/uploads/";
$maxsize = 500000;
$filetype = array("jpg", "jpeg", "png", "gif");

$response = array();
if(isset($_FILES['file']) && isset($_SESSION['userID'])) {

	if ($_FILES['file']['error'] !== UPLOAD_ERR_OK) {
		$response['error'] = "Erreur lors du téléversement du fichier.";
	} else {

		// extract file info, set name
		$ext = strtolower(pathinfo($_FILES['file']['name'],PATHINFO_EXTENSION));
		$name = uniqid() . "." . $ext ;
		$targetFile = $target_dir . $name;
		$userID = (int)$_SESSION['userID'];

		// Check if image file is an actual image or fake image
		$check = getimagesize($_FILES['file']['tmp_name']);
		if($check == false) {
			$response['error'] = "le fichier n'est pas une image.";
		} elseif ($_FILES['file']['size'] > $maxsize) {
			$response['error'] = "max 500kb !";
		} elseif (!in_array($ext, $filetype)) {
			$response['error'] = "JPG, JPEG, PNG & GIF seulement !";
		} else {
			if (move_uploaded_file($_FILES['file']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $targetFile)) {
				$connection = pg_connect("dbname=$dbname")
					or die("connection failed: " . pg_last_error());
				
				// delete previous file
				$query = "SELECT img_path FROM $table WHERE id = $1";
				$result = pg_query_params($connection, $query, array($userID)) or die("Query failed: " . pg_last_error());
				$imgPath = pg_fetch_result($result, 0, 0);
				if ($imgPath) {
					unlink($_SERVER['DOCUMENT_ROOT'] . $imgPath);
				}
				pg_free_result($result);

				//database update
				$updateQuery = "UPDATE $table SET img_path = $1 WHERE id = $2;";
				$updateResult = pg_query_params($connection, $updateQuery, array($targetFile, $userID)) or die("Query failed: " . pg_last_error());

				if ($updateResult) {
					$response['success'] = true;
					$response['newpath'] = $targetFile;
				} else {
					$response['error'] = "erreur lors de la mise à jour de la base de donnée";
				}
				pg_close($connection);
			} else {
				$response['error'] = "erreur avec le téléversement du fichier";
			}
		}
	}
	header('Content-Type: application/json');
	echo json_encode($response);
}
?>
