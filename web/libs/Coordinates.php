<?php
defined('ANS') or die();


class Coordinates {

	public static $datums = array(
		'WGS84' => array(
			'eqRadius' => 6378137.0,
			'flattening' => 298.2572236
		),
		'ED50' => array(
			'eqRadius' => 6378388,
			'flattening' => 297.00000000
		),
		'NAD83' => array(
			'eqRadius' => 6378137.0,
			'flattening' => 298.2572236
		),
		'GRS80' => array(
			'eqRadius' => 6378137.0,
			'flattening' => 298.2572215
		),
		'ETRS89' => array(
			'eqRadius' => 6378137,
			'flattening' => 298.25723267
		),
		'LISBOA' => array(
			'eqRadius' => 6378388,
			'flattering' => 297.00000000
		)
	);

    public static function getCentroidCorners($lat, $lng, $size) {

        $utm = self::latLongToUTM($lat, $lng);

        $sw = array(
            'easting' => floor($utm['easting'] / $size) * $size,
            'northing' => floor($utm['northing'] / $size) * $size,
            'zone' => $utm['zone'],
            'hemisphere' => $utm['hemisphere']
        );

        $latLngSW = self::utmToLatLong($sw, 'WGS84');

		$ne = array(
			'easting' => $sw['easting'] + $size,
			'northing' => $sw['northing'] + $size,
			'zone' => $sw['zone'],
			'hemisphere' => $sw['hemisphere']
        );

        $latLngNE = self::utmToLatLong($ne, 'WGS84');

        $result = array(
            array('latitude' => $latLngSW['lat'], 'longitude' => $latLngSW['lng']),
            array('latitude' => $latLngNE['lat'], 'longitude' => $latLngSW['lng']),
            array('latitude' => $latLngNE['lat'], 'longitude' => $latLngNE['lng']),
            array('latitude' => $latLngSW['lat'], 'longitude' => $latLngNE['lng'])
        );

        return $result;
    }

	public static function latLongToUTM($latitude, $longitude, $datumCode = 'ED50') {


		$datum = self::$datums[$datumCode];

		if (!$datum) {
			$datum = self::$datums['ED50'];
		}

		// Vars
		// -----------------------------

		$k0 = 0.9996;								// Scale on central meridian
		$a = $datum['eqRadius'];					// Equatorial radius, meters.
		$f = 1 / $datum['flattening'];				// Polar flattening.
		$b = $a * (1 - $f);						// Polar axis.
		$e = sqrt(1 - ($b / $a) * ($b / $a)); 	// Eccentricity
		$k = 1;									//local scale

		//Calculate Intermediate Terms
		$e0 = $e / sqrt(1 - $e * $e);		// Called e prime in reference
		$esq = (1 - ($b / $a) * ($b / $a));		// e squared for use in expansions
		$e0sq = $e * $e / (1 - $e * $e);			// e0 squared - always even powers

		$drad = pi() / 180; //Convert degrees to radians

		$latd = floatval($latitude);
		$lngd = floatval($longitude);

		// Convert latitude and longitude to radian
		$phi = $latd * $drad;
		$lng = $lngd * $drad;

		// Calculate utm zone
		$utmz = 1 + floor(($lngd + 180) / 6);

		//Latitude zone: A-B S of -80, C-W -80 to +72, X 72-84, Y,Z N of 84
		$latz = 0;

		if ($latd > -80 && $latd < 72) {
			$latz = floor(($latd + 80) / 8) + 2;
		} else if ($latd > 72 && $latd < 84) {
			$latz = 21;
		} else if ($latd > 84) {
			$latz = 23;
		}

		//Central meridian of zone
		$zcm = 3 + 6 * ($utmz - 1) - 180;

		$N = $a / sqrt(1 - pow($e * sin($phi), 2));

		$T = pow(tan($phi), 2);

		$C = $e0sq * pow(cos($phi), 2);

		$A = ($lngd - $zcm) * $drad * cos($phi);

		//Calculate M
		$M = $phi * (1 - $esq * (1 / 4 + $esq * (3 / 64 + 5 * $esq / 256)));
		$M = $M - sin(2 * $phi) * ($esq * (3 / 8 + $esq * (3 / 32 + 45 * $esq / 1024)));
		$M = $M + sin(4 * $phi) * ($esq * $esq * (15 / 256 + $esq * 45 / 1024));
		$M = $M - sin(6 * $phi) * ($esq * $esq * $esq * (35 / 3072));
		$M = $M * $a; 	//Arc length along standard meridian

		$M0 = 0;	//M0 is M for some origin latitude other than zero. Not needed for standard UTM

		// Calculate UTM Values
		$x = $k0 * $N * $A * (1 + $A * $A * ((1 - $T + $C) / 6 + $A * $A * (5 - 18 * $T + $T * $T + 72 * $C - 58 * $e0sq) / 120));	//Easting relative to CM

		$x = $x + 500000;	//Easting standard

		$y = $k0 * ($M - $M0 + $N * tan($phi) * ($A * $A * (1 / 2 + $A * $A * ((5 - $T + 9 * $C + 4 * $C * $C) / 24 + $A * $A * (61 - 58 * $T + $T * $T + 600 * $C - 330 * $e0sq) / 720))));	//Northing from equator
		$yg = $y + 10000000;	//yg = y global, from S. Pole

		if ($y < 0) {
			$y = 10000000 + $y;
		}

		$hem = $phi < 0 ? 'S' : 'N';

		$result = array(
			'easting' => round($x),
			'northing' => round($y),
			'zone' => $utmz,
			'hemisphere' => $hem
		);

		return $result;
	}

	public static function utmToLatLong($easting, $northing, $zone = null, $hemisphere = 'N', $datumCode = 'ED50') {

		if (func_num_args() <= 2) {

			$utmData = $easting;
			$datumCode = $northing;

			if (is_array($utmData)) {

				$zone = $utmData['zone'];
				$hemisphere = $utmData['hemisphere'] ?: 'N';
				$easting = $utmData['easting'];
				$northing = $utmData['northing'];

			} else {

				$utmData = strtoupper($utmData);
				$expr = '/^([0-9]{1,2})([A-Z])?\s([0-9]{1,6})\s([0-9]{1,6})?$/';

				if (!preg_match($expr, $utmData, $matches)) {
					throw new Exception('UTM coordinates are not in correct format ' . $utmData);
				}

				$zone = $matches[1];
				$hemisphere = $matches[2] ?: 'N';
				$easting = $matches[3];
				$northing = $matches[4];
			}
		}

		// Adjust utm coordinates with grid zone letter designated to match the correct hemisphere
		// Watchout with S grid coordinates as they are going to appear as south hemisphere ones
		if ($hemisphere !== 'N' && $hemisphere !== 'S') {
			$hemisphere = $hemisphere >= 'N' ? 'N' : 'S';
		}

		// Datum
		$datum = self::$datums[$datumCode];

		if (!$datum) {
			$datum = self::$datums['ED50'];
		}

		// Vars
		// -----------------------------

		$k0 = 0.9996;							// Scale on central meridian
		$a = $datum['eqRadius'];						// Equatorial radius, meters.
		$f = 1 / $datum['flattening'];				// Polar flattening.
		$b = $a * (1 - $f);						// Polar axis.
		$e = sqrt(1 - ($b / $a) * ($b / $a)); 	// Eccentricity
		$k = 1;									//local scale

		//Calculate Intermediate Terms
		$e0 = $e / sqrt(1 - $e * $e);			// Called e prime in reference
		$esq = (1 - ($b / $a) * ($b / $a));			// e squared for use in expansions
		$e0sq = $e * $e / (1 - $e * $e);				// e0 squared - always even powers

		$drad = pi() / 180; 					//Convert degrees to radians

		$x = intval($easting);
		$y = intval($northing);

		// Easting checks
		if ($x < 160000 || $x > 840000) {
			//throw new Exception('Outside permissible range of easting values. Results may be unreliable. Use with caution!');
		}

		// Northing checks
		if ($y < 0) {

			//throw new Exception('Negative values not allowed. Results may be unreliable. Use with caution!');

		} else if ($y > 10000000) {

			//throw new Exception('Northing may not exceed 10,000,000. Results may be unreliable. Use with caution!');
		}

		$utmz = floatval($zone);

		// Central meridian of zone
		$zcm = 3 + 6 * ($utmz - 1) - 180;

		// Called e1 in USGS PP 1395 also
		$e1 = (1 - sqrt(1 - $e * $e)) / (1 + sqrt(1 - $e * $e));

		// In case origin other than zero lat - not needed for standard UTM
		$M0 = 0;

		// Arc length along standard meridian.
		$M = $M0 + $y / $k0;

		if ($hemisphere === 'S') {
			$M = $M0 + ($y - 10000000) / $k;
		}

		$mu = $M / ($a * (1 - $esq * (1 / 4 + $esq * (3 / 64 + 5 * $esq / 256))));

		//Footprint Latitude
		$phi1 = $mu + $e1 * (3 / 2 - 27 * $e1 * $e1 / 32) * sin(2 * $mu) + $e1 * $e1 * (21 / 16 - 55 * $e1 * $e1 / 32) * sin(4 * $mu);
		$phi1 = $phi1 + $e1 * $e1 * $e1 * (sin(6 * $mu) * 151 / 96 + e1 * sin(8 * $mu) * 1097 / 512);

		$C1 = $e0sq * pow(cos($phi1), 2);
		$T1 = pow(tan($phi1), 2);
		$N1 = $a / sqrt(1 - pow($e * sin($phi1), 2));
		$R1 = $N1 * (1 - $e * $e) / (1 - pow($e * sin($phi1), 2));
		$D = ($x - 500000) / ($N1 * $k0);

		$phi = ($D * $D) * (1 / 2 - $D * $D * (5 + 3 * $T1 + 10 * $C1 - 4 * $C1 * $C1 - 9 * $e0sq) / 24);
		$phi = $phi + pow($D, 6) * (61 + 90 * $T1 + 298 * $C1 + 45 * $T1 * $T1 - 252 * $e0sq - 3 * $C1 * $C1) / 720;
		$phi = $phi1 - ($N1 * tan($phi1) / $R1) * $phi;

		$lng = $D * (1 + $D * $D * ((-1 - 2 * $T1 - $C1) / 6 + $D * $D * (5 - 2 * $C1 + 28 * $T1 - 3 * $C1 * $C1 + 8 * $e0sq + 24 * $T1 * $T1) / 120)) / cos($phi1);

		$latitude = floor(1000000 * $phi / $drad) / 1000000;
		$longitude = $zcm + $lng / $drad;

		$result = array(
			'lat' => $latitude,
			'lng' => $longitude
		);

		return $result;
	}

	public static function mgrsToUTM($mgrs) {

		$expr = '/^([0-9]{1,2})([A-Z])([A-Z])([A-Z])([0-9]{2,})([A-Z])?$/';

		$letters = "ABCDEFGHJKLMNPQRSTUVABCDEFGHJKLMNPQRSTUVABCDEFGHJKLMNPQRSTUVABCDEFGHJKLMNPQRSTUVABCDEFGHJKLMNPQRSTUVABCDEFGHJKLMNPQRSTUVABCDEFGHJKLMNPQRSTUVABCDEFGHJKLMNPQRSTUVABCDEFGHJKLMNPQRSTUVABCDEFGHJKLMNPQRSTUV";

		$lettersEasting = "ABCDEFGHJKLMNPQRSTUVWXYZ";

		$lettersNorthing = "ABCDEFGHJKLMNPQRSTUV";

		// Parse the mgrs
		// -----------------------

		$mgrs = strtoupper($mgrs); // Always uppercase

		// If it doesnt match regexp we throw an error
		if (!preg_match($expr, $mgrs, $matches)) {
			throw new Exception('MGRS format incorrect. ' . $mgrs);
		}

		$gridNumber = $matches[1]; // 29
		$gridLetter = $matches[2]; // T
		$eastingLetter = $matches[3]; // N
		$northingLetter = $matches[4]; //H
		$numbers = $matches[5]; //1234512345

		// If there are not even we throw an error
		if (strlen($numbers) % 2 !== 0) {
			throw new Exception('MGRS format incorrect. Numbers are not even. ' . $mgrs);
		}

		$easting = intval(substr($numbers, 0, strlen($numbers) / 2));
		$northing = intval(substr($numbers, strlen($numbers) / 2));

		// -----------------------

		// Extra validations
		// -------------------------

		// Check grid zone number range
		if ($gridNumber < 1 || $gridNumber > 60) {
			throw new Exception('MGRS format incorrect. Grid number should be between 1 and 60. ' . $mgrs);
		}

		// Check grid zone letter
		if ($gridLetter === 'I' || $gridLetter === 'O') {
			throw new Exception('MGRS format incorrect. I and O grid zones not allowed. ' . $mgrs);
		}

		// Check easting letter
		if (strpos($lettersEasting, $eastingLetter) < 0){
			throw new Exception('MGRS format incorrect. Easting letter not in ' . $lettersEasting . '. ' . $mgrs);
		}

		// Check northing letter
		if (strpos($lettersNorthing, $northingLetter) < 0) {
			throw new Exception('MGRS format incorrect. Northing letter not in ' . $lettersNorthing + '. ' . $mgrs);
		}

		// Check easting and northing range
		if ($easting < 0 || $easting > 100000 || $northing < 0 || $northing > 100000) {
			throw new Exception('MGRS format incorrect. Northing and easting should be between 0 and 100000. ' . $mgrs);
		}

		$eastingIndex = strpos($lettersEasting, $eastingLetter);
		$northingIndex = strpos($lettersNorthing, $northingLetter);

		// Correction for even zones
		if ($gridNumber / 2 == floor($gridNumber / 2)) {
			$northingIndex = $northingIndex - 5;
		}

		//Check Compatibility of Zones and Digraph (Northing and Easting letters)
		//Check Long Zone
		//Zone 1: 1-8; Zone 2: 9-16; Zone 3: 17-24

		if ((floor(($eastingIndex) / 8)) != (($gridNumber - 1) - 3 * floor(($gridNumber - 1) / 3))) {
			throw new Exception('MGRS format incorrect. Longitude zone and easting letter are inconsistent. ' . $mgrs);
		}

		$eastingBase = 100000 * (1 + strpos($lettersEasting, $eastingLetter) - 8 * floor(strpos($lettersEasting, $eastingLetter) / 8));

		//Now Latitude Zones
		//N Lat: 100km band = 8.88(northingIndex-12) to 8.88(northingIndex-11)
		$latitudeBand = strpos($lettersEasting, $gridLetter); // Digraph letters E use same set as Lat zone designations

		$latitudeBandBottom = 8 * $latitudeBand - 96;
		$latitudeBandTop = 8 * $latitudeBand - 88;

		//Lat Band C starts at -80 but is index 2 in the letters list, hence -80-16 = -96, etc.

		if ($latitudeBand < 2) {
			$latitudeBandBottom = -90;
			$latitudeBandTop = -80;
		}

		if ($latitudeBand == 21) {
			$latitudeBandBottom = 72;
			$latitudeBandTop = 84;
		}

		if ($latitudeBand > 21) {
			$latitudeBandBottom = 84;
			$latitudeBandTop = 90;
		}


		//One degree = 10000km/90, lat band = 8 degrees = 80000/90 = 889km
		$bottomLetter = floor(100 + 1.11 * $latitudeBandBottom);
		$topLetter = round(100 + 1.11 * $latitudeBandTop);

		//Adjust for even zones
		$latitudeBandLetters = substr($letters, $bottomLetter, (($topLetter - $bottomLetter) + 1));

		//Deal with even zones
		if ($gridNumber / 2 == floor($gridNumber / 2)) {
            $substrLength = ($topLetter + 6) - ($bottomLetter + 5);

			$latitudeBandLetters = substr($letters, $bottomLetter + 5, $substrLength);
		}

		if(strpos($latitudeBandLetters, $northingLetter) < 0) {
			throw new Exception('MGRS format incorrect. Latitude zone and northing letter are inconsistent. ' . $mgrs);
		}

		$northingBase = 100000 * ($bottomLetter + strpos($latitudeBandLetters, $northingLetter));

		// Results
		// ---------------------

		$x = intval($eastingBase) + intval($easting);
		$y = intval($northingBase) + intval($northing);

		if ($y > 10000000) {
			$y = $y - 10000000;
		}

		if ($northingBase >= 1e+7){
			$y = $northingBase + $northing - 1e+7;
		}

		$hemisphere = 'N';

		//Southern Hemisphere
		if ($northingBase < 1e+7) {
			$hemisphere = 'S';
		}

		$result = array(
			'easting' => $x,
			'northing' => $y,
			'zone' => $gridNumber,
			'hemisphere' => $hemisphere
		);

		return $result;
	}

	public static function mgrsToLatLong($mgrs, $datum = 'ED50') {

		$utm = self::mgrsToUTM($mgrs);

		return self::utmToLatLong($utm, $datum);
	}

	public static function latLongToMGRS($latitude, $longitude, $datum = 'ED50') {

		$lettersEasting = "ABCDEFGHJKLMNPQRSTUVWXYZ";
		$utm = self::latLongToUTM($latitude, $longitude, $datum);

		$latitudeZone = 0;

		if ($latitude > -80 && $latitude < 72) {
			$latitudeZone = floor(($latitude + 80) / 8) + 2;
		} else if ($latitude > 72 && $latitude < 84) {
			$latitudeZone = 21;
		} else if ($latitude > 84) {
			$latitudeZone = 23;
		}

		$gridNumber = $utm['zone'];
		$gridLetter =  substr($lettersEasting, $latitudeZone, 1);
		$easting = round($utm['easting'] - 100000 * floor($utm['easting'] / 100000));
		$northing = round($utm['northing'] - 100000 * floor($utm['northing'] / 100000));


		$letter = floor(($gridNumber - 1) * 8 + ($utm['easting']) / 100000);
		$letter = $letter - 24 * floor($letter / 24) - 1;

		$digraph = substr($lettersEasting, $letter, 1);

		//First (Easting) Character Found
		$letter = floor($utm['northing'] / 100000);

		//Odd zones start with A at equator, even zones with F
		if ($gridNumber / 2 == floor($gridNumber / 2)) {
			$letter = $letter + 5;
		}

		$letter = $letter - 20 * floor($letter / 20);
		$digraph = $digraph . substr($lettersEasting, $letter, 1);

		$result = $utm['zone'] . substr($lettersEasting, $latitudeZone, 1) .
			$digraph .
			round($utm['easting'] - 100000 * floor($utm['easting'] / 100000)) .
			round($utm['northing'] - 100000 * floor($utm['northing'] / 100000));

		return $result;
	}
}