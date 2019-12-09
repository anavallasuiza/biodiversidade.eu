<?php
defined('ANS') or die();

exit;

set_time_limit(0);
ini_set('max_execution_time', 0);
ini_set('memory_limit', '1024M');

$ipni = BASE_PATH.'tmp/ipni.txt';
$species = BASE_PATH.'tmp/Listado_especies_vs_IPNI.csv';

if (!is_file($ipni) || !is_file($species)) {
    die('Some file not found');
}

$codes = $lista = array();

$ipni = file($ipni, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$header = str_getcsv(array_shift($ipni), ';', '"');

foreach ($ipni as $fila) {
    if (empty($fila)) {
        continue;
    }

    $csv = str_getcsv($fila, ';', '"');
    $code = str_replace('_', '', $csv[5]);
    $code = alphaNumeric($code);

    if (empty($code)) {
        $code = str_replace('_', '', $csv[5]);
        $code = alphaNumeric(utf8_encode($code));
    }

    if (empty($code)) {
        $code = str_replace('_', '', $csv[5]);
        $code = alphaNumeric(utf8_decode($code));
    }

    $codes[$code] = $csv;
}

$species = file($species, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

array_shift($species);

$fp = fopen(BASE_PATH.'tmp/Species-IPNI.txt', 'w');

fputcsv($fp, $header, ';', '"');

foreach ($species as $fila) {
    if (empty($fila)) {
        continue;
    }

    $csv = str_getcsv($fila, ';', '"');
    $code = str_replace('_', '', $csv[5]);
    $code = alphaNumeric($code);

    if (empty($code)) {
        $code = str_replace('_', '', $csv[5]);
        $code = alphaNumeric(utf8_encode($code));
    }

    if (empty($code)) {
        $code = str_replace('_', '', $csv[5]);
        $code = alphaNumeric(utf8_decode($code));
    }

    $ipni = $codes[$code];

    if (empty($ipni)) {
        continue;
    }

    $csv[1] = $ipni[1];
    $csv[2] = $ipni[2];
    $csv[3] = $ipni[3];
    $csv[4] = $ipni[4];

    fputcsv($fp, $csv, ';', '"');
}

fclose($fp);

die('All done');
