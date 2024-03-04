<!DOCTYPE html>
<html>
<head>
	<title>covoiturage québec et maritimes</title>
	<?php include 'header.html'; ?>

<style>

table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
  overflow: auto;
}

td, th {
  border: 1px solid #dddddd;
  padding: 4px;
  height: 1.2rem; 
}
th { text-align: right; }
 
.waypointTable {
	float: left;
	padding: 5px;
}

</style>
</head>


<body>
<div class="waypointTable">
<table id="headers">
<tr>
<th>start</th>
</tr><tr>
<th>waypoint</th>
</tr><tr>
<th>finish</th>
</tr>
</table>
</div>

<div class="waypointTable" style="padding-left: 0px;">
<table id="waypoints">
<tr>
<td>12.234543</td><td><i class="fa-bars"></i></td>
</tr><tr>
<td></td><td><i class="fa-bars"></i></td>
</tr><tr>
<td>1.123415</td><td><i class="fa-bars"></i></td>
</tr>
</table>
</div>

</body>
</html>
