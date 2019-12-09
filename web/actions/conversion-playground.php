<?php
defined('ANS') or die();

// WGS84
// -----------------

$datum = 'ED50';

$mgrs = '29NNH1234512345';

$utm = '29N 512345 712345';
$utm_fuso = '29N';
$utm_x = '512345';
$utm_y = '712345';

$latitude = 6.444524;
$longitude = -8.888358;

// -----------------


var_dump(Coordinates::utmToLatLong($utm, $datum));
var_dump(Coordinates::utmToLatLong($utm_x, $utm_y, $utm_fuso));

var_dump(Coordinates::mgrsToLatLong($mgrs));

var_dump(Coordinates::latLongToMGRS($latitude, $longitude));
var_dump(Coordinates::latLongToUTM($latitude, $longitude));

die();