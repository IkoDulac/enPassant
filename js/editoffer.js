const path2editOffer = 'php/editsqloffer.php'
function editOffer(rideID) {

	let formData = new FormData();

	let seats = document.querySelector(`input[name="seats"][data-id="${rideID}"]`);	
	let date = document.querySelector(`input[name="date"][data-id="${rideID}"]`);	
	let time = document.querySelector(`input[name="time"][data-id="${rideID}"]`);
	let description = document.querySelector(`textarea[data-id="${rideID}"]`);

	formData.append('id', rideID);
	formData.append('seats', seats.value);
	formData.append('date', date.value);
	formData.append('time', time.value);
	formData.append('description', description.value);

	fetch(path2editOffer, {
		method: 'POST',
		body: formData
	})
	.then(response => {
		if (!response.ok) {
                        throw new Error('reponse from editoffer.php is not ok');
                }
                return response.text();
        })
        .catch(error => {
                console.error('error sending to editoffer.php : ', error);
        });
}
