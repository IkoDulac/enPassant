/* jquery-ui function call to make the waypoints' table sortable.
 * Use the 'stop' propriety to keep track of the changes in the 'tableDataIDsArray'
 * in order to send the correct request to OSRM server
 */

$(function() {
	$("#coordinatesRow").sortable({
		cursor: "move",
		placeholder: "sortable-placeholder",
		stop: function( event, td) {
			let ID = td.item.attr("id");
			let intID = Number(ID.replace(/^\D+/g, "")); // regex to extract NUMBER from "wp_NUMBER" 
			let oldPosition = tableDataIDsArray.indexOf(intID);
			let newPosition = td.item.index();
			tableDataIDsArray.splice(oldPosition, 1);
			tableDataIDsArray.splice(newPosition, 0, intID);
			//console.log(tableDataIDsArray);
		},
		helper: function(e, td) {
			var $originals = td.children();
			var $helper = td.clone();
			$helper.children().each(function(index) {
				// Set helper cell sizes to match the original sizes
				$(this).width($originals.eq(index).width());
			});
			return $helper;
		}
	}).disableSelection();
});
