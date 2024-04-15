<?php
/* 
 * query database for a user's profile
 */

// constants
$dbname = "test";
$table = "users";

// variable
$userID = (int)$_SESSION['userID'];

$connection = pg_connect("dbname=$dbname")
	or die("connection failed: " . pg_last_error());

// query
$query = "SELECT * FROM $table WHERE id = $1";
$result = pg_query_params($connection, $query, array($userID)) or die("Query failed: " . pg_last_error());
$row = pg_fetch_assoc($result);

// Free resultset
pg_free_result($result);
pg_close($connection);
?>

<!-- html output for profile tab -->
<b>Mon avatar</b>
<div class="avatar"><img id="avatar" src="<?php echo $row['img_path']; ?>" alt="avatar"></div>
<form id="uploadform" action="javascript:void(0)" onsubmit="uploadFile()" method="POST" enctype="multipart/form-data">
	<input type="file" name="file" id="fileupload"><br>
	<input type="submit" name="submit" value="upload">
</form>
<br>
<form id="editcontactform" action="javascript:void(0)" onsubmit="editContact()"  method="POST">
	<label for="contact"><b>Comment me contacter (en ordre de préférence) :</b></label><br>
	<textarea id="contact" name="contact" rows="4" cols="50"><?php echo $row['contact_info']; ?></textarea><br>
	<label for="public">Afficher mes coordonées aux non-membres ?</label>
	<input id="public" type="checkbox" name="public" <?php echo $row['is_public'] == "t" ? "checked" : ""; ?> ><br>
	<input type="submit" value="mettre à jour"><br>
</form>


<!-- post functions for both forms -->
<script>
const path2upload = '/php/uploadavatar.php';
const path2editContact = '/php/updatesqlcontact.php';
function editContact() {
	let form = document.getElementById("editcontactform");
	let formData = new FormData(form);

	fetch(path2editContact, {
		method: 'POST',
		body: formData
	}).then(response => {
		if (!response.ok) {
                        throw new Error('reponse from updatesqlcontact.php is not ok');
                }
        }).catch(error => {
                console.error('error sending to updatesqlcontact.php : ', error);
        });

	return false; // prevent page reload on sumbit
}

function uploadFile() {
	let fileInput = document.querySelector("#fileupload");
	let formData = new FormData();
	formData.append('file', fileInput.files[0]);

	fetch(path2upload, {
		method: 'POST',
		body: formData
	}).then(response => {
		if (!response.ok) {
                        throw new Error('reponse from uploadavatar.php is not ok');
                }
		return response.json();
	}).then(data => {
		if (data && data.success) {
			document.getElementById("avatar").src = data.newpath;
		} else if (data && data.error) {
			alert(data.error);
		}
	})
	.catch(error => {
                console.error('error sending to uplaodavatar.php : ', error);
        });
	return false; // prevent page reload on sumbit
}
</script>
