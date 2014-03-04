spzdmo
======

This is a sample.

## Requirements

> Create a REST Controller to handle the following HTTP
> requests based on the information included in cities.csv
> and users.csv.
>
> Provide a code sample as well as the database
> structure you use to implement your solution.
>
> Please consider how to deal with bad requests, how to respond
> to requests with large datasets, and what additional structures
> may be needed to track user visits.

* List all cities in a state:
  - ``GET /v1/states/<STATE>/cities.json``
* List cities within a 100 mile radius of a city:
  - ``GET /v1/states/<STATE>/cities/<CITY>.json?radius=100``
* Allow a user to update a row of data to indicate they have
  visited a particular city:
  - ``POST /v1/users/<USER_ID>/visits
      {
        "city" : <CITY>,
        "state" : <STATE>
      }``
* Return a list of cities the user has visited:
  - ``GET /v1/users/<USER_ID>/visits``

## Game plan

1. Add a Makefile that allows for self-contained installation,
   but permits running the service from a phpenv or other
   installation. (Consider using [phing](http://www.phing.info)
   in a later iteration.)
2. Use [propel](http://propelorm.org) for database access and
   schema migrations, because I haven't heard of it and now
   I can develop an opinion about it.
3. Use [slim](http://www.slimframework.com) to serve the API
   endpoints, because all the cool kids are raving about it.
4. Include some [phpunit](http://phpunit.de) units, because units.
5. Memcache or Redis as a cache layer? No. That's more for a reviewer
   to have to install and run. Along those lines, let's keep the
   database in SQLite. It's built-in, so no configuration on
   a reviewer's workstation is required.

## Get up and running

Everything is self-contained to avoid contamination.

Theoretically, you should just be able to type ``make`` to
download and compile all dependencies, run tests, and start
a development server on port [8123](http://localhost:8123).

In reality, that probably won't work. I've only tried this
on OS X 10.9, where both phpenv and php-build were very
uncooperative. I also felt that creating a self-contained
vagrant instance with a local puppet installation was a
little beyond what was wanted from this exercise.

Maybe you'll be lucky and have an existing PHP &gt;= 5.5
installation, and you already have composer installed.

Assuming you do, you can use ``composer update`` to pull
down all dependencies, ``make test`` to run tests, and
``make run`` to run the local server.
