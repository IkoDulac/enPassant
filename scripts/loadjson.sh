#!/bin/bash

FILES_DIR="/home/becane/www/json/polygons"

for file in "$FILES_DIR"/*.json; do
	content=$(cat "$file")
	filename=$(basename -- "$file")
	city="${filename%.*}"

	sql_tmp="
	CREATE TEMPORARY TABLE temp_json (data jsonb);
	INSERT INTO temp_json VALUES ('$content');
	INSERT INTO polygons (city_name, city_bounds)
	SELECT
		'$city',
		ST_SetSRID(ST_GeomFromGeoJSON(data->>'geojson'), 4326)
	FROM
		temp_json;
	"
	psql test -c "$sql_tmp"
done
