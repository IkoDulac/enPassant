-- ride offers' table. offer_type "enpassant" depends on homonyme table, same for "polygons"

CREATE TABLE offers (
	offer_id SERIAL NOT NULL PRIMARY KEY,
	user_id INTEGER NOT NULL,
	rides_date DATE NOT NULL,
	time TIME DEFAULT NULL,
	timezone TEXT DEFAULT 'America/Toronto',
	start_ref CITEXT,
	start_coordinates GEOMETRY(Point, 4326),
	end_ref CITEXT,
	end_coordinates GEOMETRY(Point, 4326),
	offer_type TEXT NOT NULL,
	seats SMALLINT NOT NULL,
	max_detour SMALLINT NOT NULL DEFAULT 0,
	description CITEXT
);


-- users' table

CREATE TABLE users (
	user_id SERIAL NOT NULL PRIMARY KEY,
	user_name CITEXT NOT NULL UNIQUE,
	email TEXT NOT NULL UNIQUE,
	contact_info TEXT,
	is_public BOOLEAN NOT NULL DEFAULT FALSE,
	password TEXT NOT NULL,
	img_path TEXT,
	valid BOOLEAN NOT NULL DEFAULT FALSE,
	reviews INT[],
--	trusted BOOLEAN NOT NULL DEFAULT FALSE,
	created_at DATE DEFAULT CURRENT_TIMESTAMP
);


-- contains an offer's complete route, with all intersections as postGIS points

CREATE TABLE enpassant (
	point_id BIGSERIAL NOT NULL PRIMARY KEY,
	ride_id INT NOT NULL,
	ride_date DATE NOT NULL,
	coordinates GEOMETRY(Point, 4326)
);

-- users' reviews

CREATE TABLE reviews (
	review_id SERIAL NOT NULL PRIMARY KEY,
	user_from INT NOT NULL,
	username_from TEXT,
	user_to INT NOT NULL,
	review TEXT NOT NULL,
	date_writen DATE DEFAULT CURRENT_TIMESTAMP
);

-- main cities' area as postGIS polygons

CREATE TABLE polygons (
	city_id SERIAL NOT NULL PRIMARY KEY,
	city_name CITEXT NOT NULL,
	city_bounds GEOMETRY(POLYGON, 4326) NOT NULL
);
