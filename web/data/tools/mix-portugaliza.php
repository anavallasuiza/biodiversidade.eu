<?php
defined('ANS') or die();

exit;

set_time_limit(0);
ini_set('max_execution_time', 0);
ini_set('memory_limit', '1024M');

// Input
$galicia = BASE_PATH.'tmp/Species Galicia.txt';
$portugal = BASE_PATH.'tmp/Species N_Portugal.txt';

// Output
$new = BASE_PATH.'tmp/Species Portugaliza.txt';

if (!is_file($galicia) || !is_file($portugal)) {
    die('Some file not found');
}

$codes = array();

$lista = file($galicia, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$header = array_shift($lista);

foreach ($lista as $fila) {
    $codes[] = alphaNumeric(explodeTrim("\t", $fila)[0]);
}

$portugal = file($portugal, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

array_shift($portugal);

foreach ($portugal as $fila) {
    $code = alphaNumeric(explodeTrim("\t", $fila)[0]);

    if (!in_array($code, $codes)) {
        $lista[] = $fila;
    }
}

sort($lista);

array_unshift($lista, $header);

file_put_contents($new, implode("\n", $lista));

die('All done');
