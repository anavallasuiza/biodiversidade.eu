<?php
defined('ANS') or die();

exit;

set_time_limit(0);
ini_set('max_execution_time', 0);
ini_set('memory_limit', '1024M');

// Input
$portugaliza = BASE_PATH.'tmp/species-portugaliza.txt';
$portugal = BASE_PATH.'tmp/taxonomy-search-portugal.txt';
$spain = BASE_PATH.'tmp/taxonomy-search-spain.txt';

// Output
$new = BASE_PATH.'tmp/Taxonomy-Portugaliza.txt';

if (!is_file($portugaliza) || !is_file($portugal) || !is_file($spain)) {
    die('Some file not found');
}

$codes = array();

$portugaliza = file($portugaliza, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

array_shift($portugaliza);

foreach ($portugaliza as $fila) {
    $codes[] = alphaNumeric(explodeTrim("\t", $fila)[0]);
}

$count = 0;
$existing = $lista = array();

foreach (array('spain', 'portugal') as $country) {
    $country = file($$country, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    $header = array_shift($country);

    foreach ($country as $i => $fila) {
        $level = alphaNumeric(explode("\t", $fila)[3]);

        if (empty($level) || !in_array($level, array('species', 'subspecies', 'variety', 'form'))) {
            continue;
        }

        $code = alphaNumeric(explode("\t", $fila)[2]);

        if (empty($code)) {
            continue;
        }

        if (!in_array($code, $existing) && (($key = array_search($code, $codes)) !== false)) {
            $existing[] = $code;
            $lista[] = $fila;

            ++$count;
        }
    }
}

$Debug->e($count, 'Total');

usort($lista, function ($a, $b) {
    $a = alphaNumeric(explode("\t", $a)[2]);
    $b = alphaNumeric(explode("\t", $b)[2]);

    return $a > $b ? 1 : -1;
});

array_unshift($lista, $header);

file_put_contents($new, implode("\n", $lista));

die('All done');
