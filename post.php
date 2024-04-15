<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<title>publier</title>
	<?php include 'html/header.html'; ?>
	<script>isLogged("<?php echo htmlentities($_SESSION['username']); ?>");</script>

	<link rel="stylesheet" href="js/leaflet/leaflet.css">
	<!--  <link rel="stylesheet" href="css/jquery-ui.css"> -->
	<script src="js/leaflet/leaflet.js"></script>
	<script src="js/jquery-3.7.1.min.js"></script>
	<script src="js/jquery-ui-1.13.2.sortable/jquery-ui.min.js"></script>
	<script src="js/polyline.js"></script>
	<script src="js/enpassant.js"></script>
</head>

<body>
	<?php include 'html/navbar.html'; ?>
<div>
	<div class="tabnavbar">
		<a id="defaultTab" href="javascript:void(0)" onclick="switchTab(event, 'wpmap');">
		<div class="tablink hiddenTab">en passant</div>
		</a>
		<a href="javascript:void(0)" onclick="switchTab(event, 'quickpost');">
			<div class="tablink hiddenTab">trajets courants</div>
		</a>
        <!--            <a href="javascript:void(0)" onclick="switchTab(event, '');">
                                <div class="tablink"></div>
                        </a> -->
	</div>

	<div id="wpmap" class="switchtab">
		<?php include 'html/waypointmap.html'; ?>
	</div>

	<div id="quickpost" class="switchtab">
		<?php include 'html/polygoneform.html'; ?>
	</div>
</div>

<script> document.getElementById("defaultTab").click(); </script>
<!--	<script src="js/showwaypoints.js"></script>
	<script src="js/getroute.js"></script>
	<button onclick="getRoute()">calculer l'itinéraire</button>
	<script src="js/postroute.js"></script>
	<button onclick="confirmRoute('enpassant')">publier ce voyage</button>
-->

</body>
<!-- footer with email -->
</html>
