// check database to see is username is available

const path2php = '../php/uniqueusername.php';

function checkUniq(field, value) {

	let notUnique = document.getElementById("notUnique");
	let submitButton = document.getElementById("submitButton");
	if (value.length == 0) {
		notUnique.innerText = "";
		return;
	}
	let xhttp = new XMLHttpRequest();
	xhttp.onload = function() {	
		notUnique.innerText = this.responseText;
		// php will return a warning message if username is already taken, else it will print an empty string
		if (this.responseText) {
			submitButton.style.display = "none";
		} else {
			submitButton.style.display = "block";
		}
	}
	xhttp.open("GET", path2php + "?" + field + "=" + value);
	xhttp.send();
}
