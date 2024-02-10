function onMapClick(e) {
 var  marker = new L.marker(e.latlng, {
	  draggable: true;
	  autoPan: true;
  });
  map.addLayer(marker);
};

map.on('click', onMapClick);
