<?php
/**
* phpCan - http://idc.anavallasuiza.com/
*
* phpCan is released under the GNU Affero GPL version 3
*
* More information at license.txt
*/

defined('ANS') or die();

/**
* function calendario (string $ym, [array $eventos])
*
* Función que constrúe un táboa de calendario engadindo eventos con link nas datas indicadas
*
* return string
*/

function calendario ($ym, $eventos = array())
{
	global $Html;

    $Datetime = getDatetimeObject();

	list($ano, $mes) = explode('-', $ym);

	$dia = 1;
	$mes = intval($mes);
	$ano = intval($ano);

	if (!checkdate($mes, $dia, $ano)) {
		$mes = date('m');
		$ano = date('Y');
	}

	$mes = sprintf('%02d', $mes);

	$tempo = mktime(0, 0, 0, $mes, $dia, $ano);
	$semana = date('N', $tempo);
	$dias = date('t', $tempo);

	$anterior = date('Y-m', strtotime('-1 month', $tempo));
	$seguinte = date('Y-m', strtotime('+1 month', $tempo));

	$anterior = path('eventos', $anterior);
	$seguinte = path('eventos', $seguinte);

	$calendario = '<table>';
	$calendario .= '<caption>';
    $calendario .= '<a class="image anterior" href="'.$anterior.'">'.__('Mes anterior').'</a>';
    $calendario .= '<a class="image seguinte" href="'.$seguinte.'">'.__('Mes seguinte').'</a>';
    $calendario .= '<h1>'.$Datetime->getMonth($mes).' '.date('Y', $tempo).'</h1>';
	$calendario .= '</caption>';
	$calendario .= '<thead>';
	$calendario .= '<tr>';

	$calendario .= '<th>'.__('L').'</th>';
	$calendario .= '<th>'.__('M').'</th>';
	$calendario .= '<th>'.__('MM').'</th>';
	$calendario .= '<th>'.__('X').'</th>';
	$calendario .= '<th>'.__('V').'</th>';
	$calendario .= '<th>'.__('S').'</th>';
	$calendario .= '<th>'.__('D').'</th>';

	$calendario .= '</tr>';
	$calendario .= '</thead>';
	$calendario .= '<tbody>';
	$calendario .= '<tr>';

	if ($semana > 1) {
		$calendario .= '<td colspan="'.($semana - 1).'">&nbsp;</td>';
	}

	while ($dia <= $dias) {
		if ($semana > 7) {
			$semana = 1;
			$calendario .= '</tr><tr>';
		}

		$data = $ano.'-'.$mes.'-'.sprintf('%02d', $dia);

		$calendario .= '<td rel="'.$data.'">';
        $calendario .= '<span class="dia">'.$dia.'</span>';

		if ($eventos[$data]) {
			$calendario .= implode('', $eventos[$data]);
		}

		$calendario .= '</td>';

		$semana++;
		$dia++;
	}

	if ($semana != 8) {
		$calendario .= '<td colspan="'.(8 - $semana).'">&nbsp;</td>';
	}

	$calendario .= '</tr>';
	$calendario .= '</tbody>';
	$calendario .= '</table>';

	return $calendario;
}

function str_putcsv ($input, $delimiter = ',', $enclosure = '"')
{
    // Open a memory "file" for read/write...
    $fp = fopen('php://temp', 'r+');
    // ... write the $input array to the "file" using fputcsv()...
    fputcsv($fp, $input, $delimiter, $enclosure);
    // ... rewind the "file" so we can read what we just wrote...
    rewind($fp);
    // ... read the entire line into a variable...
    $data = fread($fp, 1048576);
    // ... close the "file"...
    fclose($fp);
    // ... and return the $data to the caller, with the trailing newline from fgets() removed.
    return rtrim($data, "\n");
}

function encode2utf ($string)
{
    if ((mb_detect_encoding($string) === 'UTF-8') && mb_check_encoding($string, 'UTF-8')) {
        return $string;
    } else {
        return utf8_encode($string);
    }
}

function encode2iso ($string)
{
    if ((mb_detect_encoding($string) === 'ISO-8859-1') && mb_check_encoding($string, 'ISO-8859-1')) {
        return $string;
    } else {
        return @mb_convert_encoding($string, 'ISO-8859-1', 'auto');
    }
}

function getMGRSCentroid($mgrs) {

	$coordsLength = 5;

    preg_match_all('/\D/', $mgrs, $matches, PREG_OFFSET_CAPTURE);
    $lastStr = $matches[0][count($matches[0]) - 1][1];

    $grid = substr($mgrs, 0, $lastStr + 1);
    $location = substr($mgrs, $lastStr + 1);

    if (strlen($location) === ($coordsLength * 2)) {
        $centroide = $mgrs;
    } else {
        $coords = str_split($location, strlen($location) / 2);

        $middle = 5;
        if (strlen($coords[0]) < 4) {
            $middle = (pow(10, ($coordsLength - strlen($coords[0])) - 1) * 5);
        }

        $easting = $coords[0] . $middle;
        $northing = $coords[1] . $middle;

        $centroide = $grid . $easting . $northing;
    }

    return $centroide;
}

function getMGRSCentroidType($mgrs) {

	$result = 2; // Precision de 10km2

	if (strlen($mgrs) >= 8) { // Precision de 1km2
        $result = 1;
    }

    return $result;
}

function getDatumCode($datum) {

	$datum = strtoupper($datum);

	if (strpos($datum, 'ED50') === 0 || strpos($datum, 'ED_1950') === 0) {
		$result = 'ED50';
	} else if (strpos($datum, 'ETRS89') === 0) {
		$result = 'ETRS89';
	} else if (strpos($datum, 'Lisboa') > 0) {
		$result = 'LISBOA';
	} else {
		$result = 'WGS84';
	}

	return $result;
}

function isBot ($fields = array())
{
    $bots = array('ask jeeves','baiduspider','butterfly','fast','feedfetcher-google','firefly','gigabot','googlebot','infoseek','me.dium','mediapartners-google','nationaldirectory','rankivabot','scooter','slurp','sogou web spider','spade','tecnoseek','technoratisnoop','teoma','tweetmemebot','twiceler','twitturls','url_spider_sql','webalta crawler','webbug','webfindbot','zyborg','alexa','appie','crawler','froogle','girafabot','inktomi','looksmart','msnbot','rabaz','www.galaxy.com');
    $agent = strtolower($_SERVER['HTTP_USER_AGENT']);

    foreach ($bots as $bot) {
        if (strstr($agent, $bot) !== false) {
            return true;
        }
    }

    return $fields ? checkTags($fields) : false;
}

function checkTags ($fields)
{
    global $Vars;

    foreach ((array)$fields as $field) {
        if (strstr($Vars->var[$field], '<')) {
            return true;
        }
    }

    return false;
}


function getPuntosAmeaza($ameaza, $puntos = array(), $poligonos = array(), $lineas = array()) {

    $result = array();
    $markers = array();
    $polygons = array();
    $lines = array();
    $icono = fileWeb('templates|img/ico-ameaza1.png', false, true);
    $color = '#608000';

    if ($ameaza['nivel'] >= 3) {
        $icono = fileWeb('templates|img/ico-ameaza3.png', false, true);
        $color = '#911834';
    } else if ($ameaza['nivel'] >= 2) {
        $icono = fileWeb('templates|img/ico-ameaza2.png', false, true);
        $color = '#AD6A05';
    }

    return getPuntosFormas('ameaza', $ameaza, $icono, $color, $puntos, $poligonos, $lineas);
}

function getZonaAmeaza($ameaza) {

    $nomes = array();
    $url = array();

    if ($ameaza['concellos']) {
        $nomes[] = $ameaza['concellos']['nome']['title'];
        $url['concello'] = $ameaza['concellos']['nome']['url'];
    }

    if ($ameaza['provincias']) {
        $nomes[] = $ameaza['provincias']['nome']['title'];
        $url['provincia'] = $ameaza['provincias']['nome']['url'];
    }

    if ($ameaza['territorios']) {
        $nomes[] = $ameaza['territorios']['nome'];
        $url['territorio'] = $ameaza['territorios']['url'];
    }

    return array('nome' => join(', ', $nomes), 'url' => $url);
}

function getPuntosFormas($taboa, $entidade, $icono, $color, $puntos = array(), $poligonos = array(), $lineas = array()) {

    $result = array();
    $markers = array();
    $polygons = array();
    $lines = array();

    foreach($puntos as $punto) {
        $markers[] = array(
            'id' => 'marker-' . $punto['id'],
            'code' => $punto['id'],
            $taboa => $entidade['url'],
            'nome' => $punto['nome'],
            'texto' => $punto['texto'],
            'icon' => $punto['pois_tipos'] ? fileWeb('uploads|' . $punto['pois_tipos']['imaxe'], false, true) : $icono,
            'type' => 'marker',
            'tipo' => $punto['pois_tipos'] ? $punto['pois_tipos']['url']: '',
            'points' => array(
                array('latitude' => $punto['latitude'], 'longitude' => $punto['lonxitude']),
            )
        );
    }

    foreach($poligonos as $poligono) {

        $polygon = array(
            'id' => 'polygon-' .$poligono['url'],
            'code' => $poligono['id'],
            $taboa => $entidade['url'],
            'color' => $color,
            'type' => 'polygon',
            'points' => array()
        );

        foreach($poligono['puntos'] as $punto) {
            $polygon['points'][] = array('latitude' => $punto['latitude'], 'longitude' => $punto['lonxitude']);
        }

        $polygons[] = $polygon;
    }

    foreach($lineas as $linea) {

        $line = array(
            'id' => 'polygon-' . $linea['url'],
            'code' => $linea['id'],
            $taboa => $entidade['url'],
            'color' => $color,
            'type' => 'polyline',
            'points' => array()
        );

        foreach($linea['puntos'] as $punto) {
            $line['points'][] = array('latitude' => $punto['latitude'], 'longitude' => $punto['lonxitude']);
        }

        $lines[] = $line;
    }

    $result = array(
        'markers' => str_replace('"', '\'', json_encode($markers)),
        'polygons' => str_replace('"', '\'', json_encode($polygons)),
        'polylines' => str_replace('"', '\'', json_encode($lines))
    );

    return $result;
}

function fixED50 ($lat, $lon)
{
    //Position, decimal degrees
    $lat = (float)$lat;
    $lon = (float)$lon;

    //Earth’s radius, sphere
    $R = 6378137;

    //offsets in meters
    $dn = -31;
    $de = 7;

    //Coordinate offsets in radians
    $dLat = $dn / $R;
    $dLon = $de / ($R * cos(M_PI * $lat / 180));

    //OffsetPosition, decimal degrees
    return array($lat + ($dLat * 180 / M_PI), $lon + ($dLon * 180 / M_PI));
}

function translateQuery ($query, $id) {
    $Opentrad = new \Imaxin\Opentrad\Opentrad();
    return $Opentrad->translateQuery($query, $id);
}

function translateRow ($row, $table) {
    if (class_exists('\\Imaxin\\Opentrad\\Opentrad') !== true) {
        return setRowLanguage($row, $table);
    }

    global $Vars;

    if (empty($Vars->var['translate']) && $row['idioma']) {
        $translation = array();

        foreach ($row as $field => $value) {
            if (is_array($value) && isset($value[$row['idioma']])) {
                $translation[$field] = $value[$row['idioma']];
            } else {
                $translation[$field] = $value;
            }
        }

        return $translation;
    }

    $Opentrad = new \Imaxin\Opentrad\Opentrad();

    return $Opentrad->translateRow($row, $table);
}

function setRowLanguage ($row, $table)
{
    global $Config;

    $fields = $Config->tables[getDatabaseConnection()][$table];

    if (empty($fields)) {
        return $row;
    }

    foreach ($fields as $field => $settings) {
        if (is_string($settings)
        || empty($settings['languages'])
        || !in_array($settings['format'], array('varchar', 'text', 'html'))
        || !array_key_exists(LANGUAGE, $row[$field])) {
            continue;
        }

        $row[$field] = $row[$field][LANGUAGE];
    }

    return $row;
}

function setRowsLanguage ($query)
{
    global $Config, $Db;

    if (empty($query['table'])) {
        return array();
    }

    $fields = $Config->tables[getDatabaseConnection()][$query['table']];

    $query['language'] = 'all';

    $rows = $Db->select($query);

    if (empty($fields) || empty($rows) || empty($rows)) {
        return $rows;
    }

    if ($query['limit'] === 1) {
        $rows = array($rows);
    }

    $base = $rows[0];

    foreach ($fields as $field => $settings) {
        if (is_string($settings)
        || empty($settings['languages'])
        || !in_array($settings['format'], array('varchar', 'text', 'html'))
        || !is_array($base[$field])) {
            continue;
        }

        foreach ($rows as &$row) {
            $row[$field] = $row[$field][$row['idioma']];
        }

        unset($row);
    }

    return ($query['limit'] === 1) ? $rows[0] : $rows;
}

function cookie ($key, $value = null, $duration = null)
{
    global $Vars;

    $cookie = $Vars->getCookie('data');

    if (is_array($key)) {
        $contents = arrayMergeReplaceRecursive($cookie, $key);

        $Vars->setCookie('data', $contents);

        return true;
    }

    $isset = isset($cookie[$key]);
    $contents = $isset ? $cookie[$key] : null;

    if ($value === null) {
        return $contents;
    }

    if ($isset && ($value !== false) && is_array($contents)) {
        if (is_array($value)) {
            $contents = arrayMergeReplaceRecursive($contents, $value);
        } else if (!in_array($value, $contents)) {
            $contents[] = $value;
        }
    } else {
        $contents = $value;
    }

    $cookie[$key] = $contents;

    $Vars->setCookie('data', $cookie, $duration);

    return true;
}

function getNiveisAmeazas ($tipo)
{
    $tipo = strtoupper($tipo);

    if (!defined('NIVEIS_AMEZA_'.$tipo)) {
        return array();
    }

    $niveis = constant('NIVEIS_AMEZA_'.$tipo);
    $tipo = strtolower($tipo);
    $lista = array();

    for ($i = 1; $i <= $niveis; $i++) {
        $lista[$i] = __('nivel-ameaza-'.$tipo.'-'.$i);
    }

    return $lista;
}

function arrayColumns ($array, $columns)
{
    return array_chunk($array, ceil(count($array) / $columns));
}

function shareTwitter ($text)
{
    return 'https://twitter.com/intent/tweet?url='.urlencode(here()).'&amp;text='.urlencode($text.__(' vía @biodiv_gnp'));
}

function shareFacebook ()
{
    return 'https://www.facebook.com/sharer/sharer.php?u='.urlencode(here());
}

function here()
{
    $here = scheme().SERVER_NAME.getenv('REQUEST_URI');

    if (strpos($here, 'lang=')) {
        return $here;
    }

    return $here.(strpos($here, '?') ? '&' : '?').'lang='.LANGUAGE;
}