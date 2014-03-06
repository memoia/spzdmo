<?php
namespace Memoia\SpzDmo;

//\ORM::configure('sqlite::memory:')
\ORM::configure('sqlite:'.dirname(__DIR__).'/data/store.sqlite3');
\ORM::configure('logging', false);
\ORM::configure('return_result_sets', true);
\ORM::configure('error_mode', \PDO::ERRMODE_WARNING);
