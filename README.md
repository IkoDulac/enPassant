# ébauche de projet de site de covoiturage
amigo express et cie se limite au partage d'une base de donnée avec les voyages (date, point d'arrivé, point de départ) Le site projeté ici est doté d'un engin de routage live pour l'utilisateur. ce qui fait que le voyage peut contenir en plus toutes les intersections sur son trajet. Ce qui permet d'ajouter des fonctions comme : chercher des passagers qu'on peut prendre en passant, où à < de x km de son trajet.
Aussi, idéalement, il y aurait un geocodeur intégré pour que l'utilisatrice puisse tapper son lieu d'arrivée/départ textuellement, plutôt que d'entrer les points GPS.
## les pièces détachées :
- carte intéractive => [leaflet](https://leafletjs.com/)
- engin de routing => [OSRM](http://project-osrm.org/)
- geocodeur => [gisgraphy](https://www.gisgraphy.com/) 
- sytème de login des utilisatrices => ?
- base de donnée => postgres (comme le géocodeur l'utilise déjà)
## commentaires
le fichier json contient une réponse type du routing engine (obtenue depuis l'instance installée sur mon ordinateur personnel) avec une seule route. éventuellement, il faut modifier les codes pour travailler avec une ou deux routes alternatives que l'utilisatrice peut sélectionner en cliquant sur la carte.
