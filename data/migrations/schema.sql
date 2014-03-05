-- Generated Propel SQL; saves time redoing the initial
-- migration in "pure" Phinx. While that'd be nice to do some day,
-- the clock's ticking.

-----------------------------------------------------------------------
-- cities
-----------------------------------------------------------------------

DROP TABLE IF EXISTS cities;

CREATE TABLE cities
(
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(255) NOT NULL,
    state VARCHAR(2) NOT NULL,
    status VARCHAR(255) NOT NULL,
    latitude DECIMAL NOT NULL,
    longitude DECIMAL NOT NULL
);

CREATE INDEX cities_I_1 ON cities (latitude,longitude);

-----------------------------------------------------------------------
-- users
-----------------------------------------------------------------------

DROP TABLE IF EXISTS users;

CREATE TABLE users
(
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL
);

-----------------------------------------------------------------------
-- visits
-----------------------------------------------------------------------

DROP TABLE IF EXISTS visits;

CREATE TABLE visits
(
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    modified TIMESTAMP NOT NULL,
    users_id INTEGER NOT NULL,
    cities_id INTEGER NOT NULL,
    UNIQUE (users_id,cities_id),
    FOREIGN KEY (users_id) REFERENCES users (id),
    FOREIGN KEY (cities_id) REFERENCES cities (id)
);
