<?php
require dirname(__DIR__).'/app/util.php';

use Memoia\SpzDmo\Util;

class UtilTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->fooRad = 1;
        $this->fooLat = 10.0;
        $this->fooLon = -20.0;
        $this->fooBox = Util\bounding_box($this->fooRad, $this->fooLat, $this->fooLon);
    }

    public function testBoundingBoxHasExpectedKeys()
    {
        $want_keys = array('max_lat', 'min_lat', 'max_lon', 'min_lon');
        $have_keys = array_keys($this->fooBox);
        $this->assertEmpty(array_diff($want_keys, $have_keys));
    }

    public function testBoundingBoxMaxLatGreaterThanGivenLat()
    {
        $this->assertGreaterThan($this->fooLat, $this->fooBox['max_lat']);
    }

    public function testBoundingBoxMinLatLessThanGivenLat()
    {
        $this->assertLessThan($this->fooLat, $this->fooBox['min_lat']);
    }

    public function testBoundingBoxMaxLonGreaterThanGivenLon()
    {
        $this->assertGreaterThan($this->fooLon, $this->fooBox['max_lon']);
    }

    public function testBoundingBoxMinLonLessThanGivenLon()
    {
        $this->assertLessThan($this->fooLon, $this->fooBox['min_lon']);
    }

    public function testBoundingBoxExpectedValues()
    {
        $this->assertEquals(Util\bounding_box(100, 41.840675, -87.679365), array(
            'max_lat' => 43.286381699688,
            'min_lat' => 40.394968300312,
            'max_lon' => -85.738825950143,
            'min_lon' => -89.619904049857,
        ));
    }
}
