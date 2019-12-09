<?php
defined('ANS') or die();

exit;

set_time_limit(0);
ini_set('max_execution_time', 0);
ini_set('memory_limit', '1024M');

$col = BASE_PATH.'tmp/IPNI-Portugaliza-COL-CG.txt';

if (!is_file($col)) {
    die('Some file not found');
}

// Output
$new = BASE_PATH.'tmp/IPNI-Portugaliza-COL-CG-duplicates.txt';

$codes = $lista = array();

$col = file($col, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$header = array_shift($col);

// Col 70 (BS) = taxon_id

foreach ($col as $fila) {
    if (empty($fila)) {
        continue;
    }

    $csv = str_getcsv($fila, ';', '"');
    $code = trim($csv[70]);

    if ($code) {
        if (strstr($code, '|') === false) {
            $code = array($code);
        } else {
            $codes[$code][] = $csv;
            $code = explodeTrim('|', $code);
        }

        foreach ($code as $each) {
            $codes[$each][] = $csv;
        }
    }
}

$all = array();

echo '<pre>';

foreach ($col as $fila) {
    if (empty($fila)) {
        continue;
    }

    $csv = str_getcsv($fila, ';', '"');
    $code = trim($csv[70]);

    if (empty($code)) {
        $all[] = str_putcsv($csv, ';', '"');
        continue;
    }

    if (!isset($codes[$code])) {
        continue;
    }

    if (strstr($code, '|') === false) {
        $code = array($code);
    } else {
        $code = explodeTrim('|', $code);
    }

    foreach ($code as $each) {
        if (!isset($codes[$each])) {
            continue;
        }

        if (count($codes[$each]) < 2) {
            $all[] = str_putcsv($csv, ';', '"');
            continue;
        }

        $valid = array();

        foreach ($codes[$each] as $duplicate) {
            if (trim($duplicate[71])) {
                $valid = $duplicate;
            }
        }

        $valid = $valid ?: $codes[$each][0];

        $all[] = str_putcsv($valid, ';', '"');

        unset($codes[$each]);
    }

    unset($codes[implode('|', $code)]);
}

array_unshift($all, $header);

file_put_contents($new, implode("\n", $all));

die('All done');
