<?php
defined('ANS') or die();

exit;

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

$file1 = BASE_PATH.'tmp/IPNI-catalogo-galego.txt';
$file2 = BASE_PATH.'tmp/IPNI-Portugaliza-COL-CG-Authors.txt';

if (!is_file($file1) || !is_file($file2)) {
    die('Some file not found');
}

$new = BASE_PATH.'tmp/IPNI-Portugaliza-COL-CG-final.txt';

$codes = $all = array();

$file1 = file($file1, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

array_shift($file1);

// USE COLUMN 5 (GENERO) AS CODE

foreach ($file1 as $fila) {
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

$file2 = file($file2, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$header = str_getcsv(array_shift($file2), ';', '"');

foreach ($file2 as $fila) {
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

    if (empty($codes[$code])) {
        $codes[$code] = $csv;
    }
}

define('COLUMNS', count($header));

// FILL FIELDS B (1), C (2), D (3), E (4) AND AJ (35)

foreach ($file2 as $fila) {
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

    $galego = $codes[$code];

    if (empty($galego)) {
        continue;
    }

    $csv[1] = trim($csv[1]) ? $csv[1] : $galego[1];
    $csv[2] = trim($csv[2]) ? $csv[2] : $galego[2];
    $csv[3] = trim($csv[3]) ? $csv[3] : $galego[3];
    $csv[4] = trim($csv[4]) ? $csv[4] : $galego[4];
    $csv[35] = trim($csv[35]) ? $csv[35] : $galego[35];

    $all[] = row($csv);
}

array_unshift($all, str_putcsv($header, ';', '"'));

file_put_contents($new, implode("\n", $all));

die ('<h1>Ended at '.date('Y-m-d H:i:s').'</h1>');
