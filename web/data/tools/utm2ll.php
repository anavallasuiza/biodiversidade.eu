<?php
defined('ANS') or die();

function debug ($message, $title = '', $line = '', $tag = 'h4') {
    echo '<'.$tag.'>'.$line.' - '.$title.'</'.$tag.'>';
    print_r($message);
}

function row ($csv) {
    for ($i = 0; $i <= COLUMNS; $i++) {
        $csv[$i] = $csv[$i] ?: ' ';
    }

    ksort($csv);

    return str_putcsv($csv, ';', '"');
}

set_time_limit(0);
ini_set('max_execution_time', 0);
ini_set('memory_limit', '1024M');

$file1 = BASE_PATH.'tmp/Datos Flora Ameazada Galiza Web.csv';

if (!is_file($file1)) {
    die('Some file not found');
}

$new = BASE_PATH.'tmp/Datos Flora Ameazada Galiza Web 2.csv';

$file1 = file($file1, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$header = str_getcsv(array_shift($file1), ';', '"');

$header[] = 'Latitud';
$header[] = 'Longitud';

define('COLUMNS', count($header));

/*
Array
(
    [0] => ESPECIES
    [1] => Localidade
    [2] => UTM MGRS
    [3] => FUSO
    [4] => UTM_X
    [5] => UTM_Y
    [6] => Lat
    [7] => Long
    [8] => Datum
    [9] => Tipo de Dado
    [10] => Arquivo
    [11] => Tipo de referencia
    [12] => Referencia bibliográfica
    [13] => Fornecedor de Datos
)
*/

$all = array();

foreach ($file1 as $row) {
    $csv = trimArray(str_getcsv($row, ';', '"'));

    if (empty($csv[0])) {
        $all[] = row($csv);
        continue;
    }

    $code = str_replace('_', '', $csv[0]);
    $code = alphaNumeric($code);

    $lat = $long = '';

    if ($csv[6] && $csv[7]) {
        $GP = new \Math\GeographicPoint\LatitudeLongitude($csv[6], $csv[7], $csv[8]);

        $lat = round($GP->getLatitude(), 14);
        $long = round($GP->getLongitude(), 14);
    } else if ($csv[4] && $csv[5]) {
        $utm_x = $csv[4];
        $utm_y = $csv[5];

        if (stristr($csv[8], 'ed50') || strstr($csv[8], '1950')) {
            $utm_x -= 87.987;
            $utm_y -= 108.639;
        }

        $GP = new \Math\GeographicPoint\UTM($utm_x, $utm_y, $csv[3], $csv[8]);

        $GP = $GP->toLatitudeLongitude();

        $lat = round($GP->getLatitude(), 14);
        $long = round($GP->getLongitude(), 14);
    } else if ($csv[2]) {
        $GP = new \Math\GeographicPoint\MGRS($csv[2], $csv[8]);

        $GP = $GP->toTM();
        $GP = $GP->toLatitudeLongitude();

        $lat = round($GP->getLatitude(), 14);
        $long = round($GP->getLongitude(), 14);
    }

    $csv[] = str_replace('.', ',', $lat);
    $csv[] = str_replace('.', ',', $long);

    $all[] = row($csv);
}

array_unshift($all, str_putcsv($header, ';', '"'));

file_put_contents($new, implode("\n", $all));

die ('<h1>Ended at '.date('Y-m-d H:i:s').'</h1>');
