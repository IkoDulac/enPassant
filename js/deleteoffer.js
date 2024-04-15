const path2delete = 'php/deletesqloffer.php';

function deleteOffer(id) {
	if (confirm("effacer cette offre ?")) {

		fetch(path2delete, {
			method: 'POST',
			body: id 
		})
		.then(response => {
			if (!response.ok) {
				throw new Error('reponse from deletesqloffer script is not ok');
			}
			location.reload();
		})
		.catch(error => {
			console.error('error sending to deletesqloffer script: ', error);
		});
	}
}
