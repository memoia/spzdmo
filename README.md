spzdmo
======

This is a sample. Assignment and thoughts follow.


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

Assuming you do (maybe, say, through a phpenv setup),
you can use ``composer update`` to pull down all
dependencies, ``make database`` to initialize a local
SQLite database for use with the application,
``make test`` to run tests, and ``make run`` to run the
local server.


## Exercise

Create a REST Controller to handle the following HTTP
requests based on the information included in cities.csv
and users.csv.

Provide a code sample as well as the database
structure you use to implement your solution.

Please consider how to deal with bad requests, how to respond
to requests with large datasets, and what additional structures
may be needed to track user visits.

### API Interface

List all cities in a state:    
``GET /v1/states/<STATE>/cities.json``

List cities within a 100 mile radius of a city:   
``GET /v1/states/<STATE>/cities/<CITY>.json?radius=100``

Allow a user to update a row of data to indicate they have
visited a particular city:    
``POST /v1/users/<USER_ID>/visits`` with payload: ``{ "city" : <CITY>, "state" : <STATE> }``

Return a list of cities the user has visited:    
``GET /v1/users/<USER_ID>/visits``


## Game plan

1. Add a Makefile that allows for self-contained installation,
   but permits running the service from a phpenv or other
   installation. (Consider using [phing](http://www.phing.info)
   in a later iteration.)
2. Try using [propel](http://propelorm.org),
   [doctrine](http://www.doctrine-project.org) or a combination
   of [idiorm](http://idiorm.readthedocs.org) and
   [phinx](http://phinx.org) for database access and
   schema migrations, and see which is easiest to apply
   to this project.
3. Use [slim](http://www.slimframework.com) to serve the API
   endpoints, because all the cool kids are raving about it.
4. Include some [phpunit](http://phpunit.de) units, because units.
5. Avoid additional caching layers like Memcache or Redis; there's
   not enough data here and it just further complicates a demo.
6. Use SQLite as the data-store to avoid any additional
   required configuration.


## Some thoughts

1. Slim has a funny interaction with the PHP dev server;
   setting ``SCRIPT_NAME`` to ``null`` is the only way to get
   it to obey routes that resemble file names. This is probably
   more on the PHP dev server than it is on Slim.
2. Propel really does not make it straightforward to use sqlite.
   Doctrine's cli won't start despite following directions.
   Phinx and Idiorm just work. Awesome.
3. Will pass along state names and cities as-is to keep things
   simple. More rigorous validation would be nice, but assuming
   for now that the web framework and the PDO layer will at least
   prevent nastiness.
4. It would be nice to have a more accurate radius lookup, going
   beyond a bounding box, but in the interest of time, let's keep
   it simple. A good algorithm for a secondary pass is referenced
   in source.
