<?php
require dirname(__DIR__).'/vendor/autoload.php';

class NothingTest extends PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        require dirname(__DIR__).'/app/db.php';
        \ORM::configure('sqlite:'.dirname(__DIR__).'/data/test.sqlite3');
    }

    public function testNothing()
    {
        $this->assertTrue(true);
    }
}
