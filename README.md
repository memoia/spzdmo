spzdmo
======

This is a sample. Assignment and thoughts follow.


## Get up and running

Everything is self-contained to avoid contamination.

Theoretically, you should just be able to type ``make`` to
download and compile all dependencies, run tests, and start
a development server on port [8123](http://localhost:8123).

In reality, that probably won't work. I've only tried this
thoroughly on OS X 10.9 (with XCode/tools), where both phpenv
and php-build were very uncooperative. I also felt that
creating a self-contained vagrant instance with a local puppet
installation was a little beyond what was wanted from this exercise.

However, speaking of vagrant, I did get the project to build
on an Ubuntu "Precise" box after installing make, curl,
libxml2-dev, libssl-dev, pkg-config, gcc, etc. It might
have been better to just go with build-essential.

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
5. Use SQLite as the data-store to avoid any additional
   required configuration; there's not enough data to warrant
   a "big" database or a separate caching layer.


## More thoughts

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
5. I'm not sure I like the namespacing scheme I chose for the
   API versions. It's not really in line with common practice.
   That should probably be fixed/changed some time.

### Interpretations of the spec

The spec is a little unclear about user visits. Do we want
to keep track of every time a user has visited a location,
or just that a user has been to a location? I'm interpreting
it as the latter; maybe we want a column indicating when the
user was last at a location such that a POST makes more
sense---i.e., upsert; log when the user was last at a location.
If it were a new record for every visit, I'd expect a PUT to
have been used instead.

It is also unclear about the radius option; what happens
if it's not provided? I'll assume radius is zero if not given.

The style in general is a little odd; why do we want to get
most things by name, but some things (user visits) by ID?
What about cities with spaces in their names? A client application
would have to properly format such whitespace in the request.

Case sensitivity is also a concern. Nothing is mentioned about this,
so I'll keep it simple and have it do a strict match.

And there's the file-like component to it, too. I wonder why
we are adding a ".json" extension to the routes, when it's
simple enough to just include the appropriate content-type
header in the response. Excluding a pseudo file extension would
also allow us to sensibly return in different formats from
the same endpoint, depending on the requested content-type.

Strangely, some routes have the filename extension, and some do not.

The filename extension suggests output format, but not structure.
This leads me to assume a structure that directly represents the
underlying table.

Also, why can you only submit a single visit record at a time,
when all other requests return (potentially) multiple records?

When adding a new visit, what happens if the city/state combination
isn't found in the cities table? Insert a new record into that table
first and mark it 'unverified', as we don't have coordinates? Or just
prevent the visit from being inserted? I'll go with the latter since
that's simpler.

The final wording is also somewhat confusing. "Bad requests", in
this case, would likely include:

1. Route that does not exist
2. Malformed and/or malicious URL
3. Lookups of data that do not exist
4. Payloads that do not match expected input format

The first should 404, the second, hopefully, would get caught by
the web framework and 500, the third should also 404---or maybe 500,
if the lookup contains a value that cannot be bound in PDO properly,
and the fourth maybe should 400. I'm unsure what other kind of
"bad request" there is for this application.

Lastly, the large dataset clause is confusing. There isn't a lot
of data here---approx 10K records, which SQLite is plenty fast
at reading and joining, especially with indices. I wonder how
it would handle a bunch of trig functions in the radius query,
but I wouldn't keep that logic in the database layer anyway, unless
the database actually provided an optimized function for that
purpose. For this problem and the selected combination of tools,
it's probably best to use simple conditionals that can hit
indices, and filter the result set on a more granular level within
the application code.

If we were concerned about query performance, we could stick a caching
layer in front of it, like Memcache or Redis, or even simpler, an in-memory
SQLite instance. But, if we needed that, we probably would also need
to move to load-balanced web servers with proxies to PHP-FPM, and a
bunch of other infrastructural things that are out of scope for this
exercise. In other words, expected load isn't given, so it's hard to
know what would be too-little and what would be overkill.
