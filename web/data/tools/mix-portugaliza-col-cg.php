<?php
defined('ANS') or die();

exit;

set_time_limit(0);
ini_set('max_execution_time', 0);
ini_set('memory_limit', '1024M');

$cg = BASE_PATH.'tmp/acceptados-cg.csv';
$col = BASE_PATH.'tmp/IPNI-Portugaliza-COL.txt';

if (!is_file($cg) || !is_file($col)) {
    die('Some file not found');
}

// Output
$new = BASE_PATH.'tmp/IPNI-Portugaliza-COL-CG.txt';

$codes = $lista = array();

$cg = file($cg, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

array_shift($cg);

foreach ($cg as $fila) {
    if (empty($fila)) {
        continue;
    }

    $csv = str_getcsv($fila, ';', '"');
    $code = str_replace('_', '', $csv[0]);
    $code = alphaNumeric($code);

    if (empty($code)) {
        $code = str_replace('_', '', $csv[0]);
        $code = alphaNumeric(utf8_encode($code));
    }

    if (empty($code)) {
        $code = str_replace('_', '', $csv[0]);
        $code = alphaNumeric(utf8_decode($code));
    }

    $codes[$code] = $csv;
}

$col = file($col, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$header = array_shift($col);

$all = array();

foreach ($col as $fila) {
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

    if ($codes[$code]) {
        $csv[] = $codes[$code][0];
    }

    $all[] = str_putcsv($csv, ';', '"');
}

$header = str_getcsv($header, ';', '"');

$header[] = 'Atopado en Portugaliza-COL';

$header = str_putcsv($header, ';', '"');

array_unshift($all, $header);

file_put_contents($new, implode("\n", $all));

die('All done');
