// toggle password visibility
function showPassword() {
  var x = document.getElementById("password");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}


// check database to see is username is available
const path2phpUniqueScript = '../php/uniqueusername.php';
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
	xhttp.open("GET", path2phpUniqueScript + "?" + field + "=" + value);
	xhttp.send();
}

// in search forms, adjust dateMax.min to be equal to dateMin
function setNewMin(minID, maxID) {
	let dateMin = document.getElementById(minID).value;
	document.getElementById(maxID).min = dateMin;
}

// switch between visible tabs (<div>)
function switchTab(evt, tabName) {
	var i, x, tablinks;
	x = document.getElementsByClassName("switchtab");
	for (i = 0; i < x.length; i++) {
		x[i].style.display = "none";
	}
	tablinks = document.getElementsByClassName("tablink");
	for (i = 0; i < x.length; i++) {
		tablinks[i].className = tablinks[i].className.replace(" activeTab", " hiddenTab");
	}
	document.getElementById(tabName).style.display = "block";
	evt.currentTarget.firstElementChild.className = evt.currentTarget.firstElementChild.className.replace(" hiddenTab", " activeTab");
}
