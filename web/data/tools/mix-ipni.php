<?php
defined('ANS') or die();

exit;

set_time_limit(0);
ini_set('max_execution_time', 0);
ini_set('memory_limit', '1024M');

// Input
$portugaliza = BASE_PATH.'tmp/Listado_especies_vs_IPNI.txt';
$ipni = BASE_PATH.'tmp/ipni.txt';

// Output
$new = BASE_PATH.'tmp/IPNI-Portugaliza.txt';

if (!is_file($portugaliza) || !is_file($ipni)) {
    die('Some file not found');
}

$codes = $lista = array();

$portugaliza = file($portugaliza, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

array_shift($portugaliza);

// Listado_especies_vs_IPNI.txt: against 5 F (GENERO) in ipni.txt and fill 1 B (Kingdom), 2 C (Phylum), 3 D (Class), 4 E (Order) e 34 AI (FAMILIA)
foreach ($portugaliza as $num => $fila) {
    $fila = trim($fila);

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

    if (empty($code)) {
        continue;
    }

    if (isset($codes[$code])) {
        $codes[$code][] = $csv;
    } else {
        $codes[$code] = array($csv);
    }
}

$ipni = file($ipni, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$header = array_shift($ipni);

// ipni.txt: against 5 F (GENERO) in Listado_especies_vs_IPNI.txt and fill 1 B (Kingdom), 2 C (Phylum), 3 D (Class), 4 E (Order) e 34 AI (FAMILIA)
foreach ($ipni as $fila) {
    $fila = trim($fila);

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

    if (empty($code)) {
        echo '<pre>';
        var_dump($csv[5]);
        var_dump(utf8_encode($csv[5]));
        var_dump(utf8_decode($csv[5]));
        var_dump(alphaNumeric(utf8_encode($csv[5])));
        var_dump(alphaNumeric(utf8_decode($csv[5])));
        exit;
    }

    if (isset($codes[$code])) {
        foreach ($codes[$code] as $original) {
            $original[1] = $csv[1];
            $original[2] = $csv[2];
            $original[3] = $csv[3];
            $original[4] = $csv[4];
            $original[34] = $csv[34];

            $lista[] = str_putcsv($original, ';', '"');
        }

        unset($codes[$code]);

        if (empty($codes)) {
            break;
        }
    }
}

usort($lista, function ($a, $b) {
    $a = str_getcsv($a, ';', '"');
    $a = alphaNumeric($csv[5]);

    $b = str_getcsv($b, ';', '"');
    $b = alphaNumeric($csv[5]);

    return $a > $b ? 1 : -1;
});

if ($codes) {
    $Debug->e($codes, 'Not Found ('.count($codes).'). Added at file end.');

    foreach ($codes as $rows) {
        foreach ($rows as $original) {
            $lista[] = str_putcsv($original, ';', '"');
        }
    }
}

array_unshift($lista, $header);

file_put_contents($new, implode("\n", $lista));

die('All done');
