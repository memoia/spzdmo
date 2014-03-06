<?php
namespace Memoia\SpzDmo\Util;

/**
 * Return array(max_latitude, min_latitude, max_longitude, min_longitude)
 * extending $radius miles from $center_lat, $center_lon.
 *
 * Where's PostGIS when you need it?
 * Fortunately there's this:
 *   http://www.movable-type.co.uk/scripts/latlong-db.html
 */
function bounding_box($radius, $center_lat, $center_lon)
{
    $RADIUS_EARTH = 3963.1676;

    $offset_lat = rad2deg($radius/$RADIUS_EARTH);
    $offset_lon = rad2deg($radius/$RADIUS_EARTH/cos(deg2rad($center_lat)));
    return array(
        'max_lat' => $center_lat + $offset_lat,
        'min_lat' => $center_lat - $offset_lat,
        'max_lon' => $center_lon + $offset_lon,
        'min_lon' => $center_lon - $offset_lon,
    );
}
