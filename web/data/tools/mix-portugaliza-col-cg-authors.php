<?php
defined('ANS') or die();

exit;

function debug ($message, $title, $line, $tag = 'h4') {
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

echo '<pre>';
echo '<h1>Started at '.date('Y-m-d H:i:s').'</h1>';

set_time_limit(0);
ini_set('max_execution_time', 0);
ini_set('memory_limit', '1024M');

$ipni = BASE_PATH.'tmp/IPNI-Portugaliza-COL-CG-duplicates.txt';

// Output
$new = BASE_PATH.'tmp/IPNI-Portugaliza-COL-CG-Authors.txt';

if (!is_file($ipni)) {
    die('Some file not found');
}

$COL = new \ANS\PHPCan\Data\Db('Db');
$COL->setConnection('col2011ac');

$all = array();

$ipni = file($ipni, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$header = str_getcsv(array_shift($ipni), ';', '"');

$header = array_merge($header, array(
    'COL AUTHOR'
));

define('COLUMNS', count($header));

$total = count($ipni);

// Col 70 (BS) = taxon_id

foreach ($ipni as $i => $fila) {
    echo '<h3>Processing row '.$i.' of '.$total.'</h3>';

    if (empty($fila)) {
        continue;
    }

    ob_flush();
    flush();

    $csv = array_map('trim', str_getcsv($fila, ';', '"'));

    if (empty($csv[70])) {
        $all[] = row($csv);

        debug($csv, 'Empty id_taxon', __LINE__);
        continue;
    }

    if (strstr($csv[70], '|')) {
        $code = explode('|', $csv[70])[0];
    } else {
        $code = $csv[70];
    }

    $query = 'SELECT author FROM _species_details'
        .' WHERE taxon_id = "'.$code.'"'
        .' LIMIT 1;';

    $author = $COL->queryResult($query)[0];

    if (empty($author)) {
        debug($query, 'Empty author', __LINE__);
    }

    $csv[72] = $author['author'];
    $all[] = row($csv);
}

$header = str_putcsv($header, ';', '"');

array_unshift($all, $header);

file_put_contents($new, implode("\n", $all));

die ('<h1>Ended at '.date('Y-m-d H:i:s').'</h1>');
