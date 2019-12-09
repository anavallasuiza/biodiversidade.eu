<?php
defined('ANS') or die();

exit;

set_time_limit(0);
ini_set('max_execution_time', 0);
ini_set('memory_limit', '1024M');

$ipni = BASE_PATH.'tmp/IPNI-Portugaliza.txt';
$species = BASE_PATH.'tmp/Listado_especies_vs_IPNI.txt';

// Output
$new = BASE_PATH.'tmp/IPNI-Portugaliza-Combined.txt';

if (!is_file($ipni) || !is_file($species)) {
    die('Some file not found');
}

$all = array();

$ipni = file($ipni, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$header = array_shift($ipni);

foreach ($ipni as $fila) {
    if (empty($fila)) {
        continue;
    }

    $csv = str_getcsv($fila, ';', '"');
    $code = str_replace('_', '', $csv[11]);
    $code = alphaNumeric($code);

    if (empty($code)) {
        $code = str_replace('_', '', $csv[11]);
        $code = alphaNumeric(utf8_encode($code));
    }

    if (empty($code)) {
        $code = str_replace('_', '', $csv[11]);
        $code = alphaNumeric(utf8_decode($code));
    }

    if (preg_match('/subsp\.|var\.|f\./i', $csv[10], $infra)) {
        $csv[10] = trim(str_replace($infra[0], '', $csv[10]));
        $csv[9] = $infra[0];

        $fila = str_putcsv($csv, ';', '"');
    }

    $all[$code] = $fila;
}

$species = file($species, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

array_shift($species);

foreach ($species as $fila) {
    if (empty($fila)) {
        continue;
    }

    $csv = str_getcsv($fila, ';', '"');
    $code = str_replace('_', '', $csv[11]);
    $code = alphaNumeric($code);

    if (empty($code)) {
        $code = str_replace('_', '', $csv[11]);
        $code = alphaNumeric(utf8_encode($code));
    }

    if (empty($code)) {
        $code = str_replace('_', '', $csv[11]);
        $code = alphaNumeric(utf8_decode($code));
    }

    if (empty($all[$code])) {
        if (preg_match('/subsp\.|var\.|f\./i', $csv[10], $infra)) {
            $csv[10] = trim(str_replace($infra[0], '', $csv[10]));
            $csv[9] = $infra[0];

            $fila = str_putcsv($csv, ';', '"');
        }

        $all[$code] = $fila;
    }
}

usort($all, function ($a, $b) {
    $a = str_getcsv($a, ';', '"');
    $b = str_getcsv($b, ';', '"');

    return alphaNumeric($csv[5]) > alphaNumeric($csv[5]) ? 1 : -1;
});

array_unshift($all, $header);

file_put_contents($new, implode("\n", $all));

die('All done');
