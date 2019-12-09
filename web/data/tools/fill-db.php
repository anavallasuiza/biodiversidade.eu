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

echo '<pre>';
echo '<h1>Started at '.date('Y-m-d H:i:s').'</h1>';

set_time_limit(0);
ini_set('max_execution_time', 0);
ini_set('memory_limit', '1024M');

$file1 = BASE_PATH.'tmp/IPNI-Portugaliza-COL-CG-final.txt';

if (!is_file($file1)) {
    die('File not found');
}

$Db->query('TRUNCATE TABLE reinos;');
$Db->query('TRUNCATE TABLE clases;');
$Db->query('TRUNCATE TABLE ordes;');
$Db->query('TRUNCATE TABLE familias;');
$Db->query('TRUNCATE TABLE xeneros;');
$Db->query('TRUNCATE TABLE especies;');

$relations = array(
    'reinos' => array(
        'column' => 1,
        'values' => array(),
        'relate' => array('especies')
    ),
    'clases' => array(
        'column' => 3,
        'values' => array(),
        'relate' => array('reinos', 'especies')
    ),
    'ordes' => array(
        'column' => 4,
        'values' => array(),
        'relate' => array('reinos', 'clases', 'especies')
    ),
    'familias' => array(
        'column' => 34,
        'values' => array(),
        'relate' => array('reinos', 'ordes', 'especies')
    ),
    'xeneros' => array(
        'column' => 5,
        'values' => array(),
        'relate' => array('reinos', 'familias', 'especies')
    )
);

$empty = array();

$file1 = file($file1, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

array_shift($file1);

$total = count($file1);

foreach ($file1 as $i => $fila) {
    echo '<h3>Processing row '.$i.' of '.$total.'</h3>';

    if (empty($fila)) {
        continue;
    }

    ob_flush();
    flush();

    $csv = array_map(function ($value) {
        return str_replace('_', '', trim($value));
    }, str_getcsv($fila, ';', '"'));

    if (empty($csv[71])) {
        continue;
    }

    if (strstr($csv[70], '|')) {
        list($csv[70]) = explode('|', $csv[70]);
    }

    $ids = array();
    $data = array(
        'url' => $csv[71],
        'nome' => $csv[71],
        'lsid_name' => $csv[0],
        'especie' => $csv[7],
        'autor' => $csv[72] ?: $csv[8],
        'subespecie' => $csv[9],
        'variedade' => $csv[10],
        'sinonimos' => $csv[69],
        'taxon_id' => $csv[70],
        'activo' => 1
    );

    $ids['especies'] = $Db->insert(array(
        'table' => 'especies',
        'data' => $data
    ));

    if (empty($ids['especies'])) {
        $Debug->e($data);
        $Debug->e($Errors->getList());
        exit;
    }

    foreach ($relations as $table => &$relation) {
        $value = $csv[$relation['column']];

        if (empty($value)) {
            $Debug->e($csv, 'Empty '.$table);
            $Debug->e($relation);
            $Debug->e($value);
            continue;
        }

        if (empty($relation['values'][$value])) {
            $relation['values'][$value] = $Db->insert(array(
                'table' => $table,
                'data' => array(
                    'url' => $value,
                    'nome' => $value
                )
            ));

            if (empty($relation['values'][$value])) {
                $Debug->e($Errors->getList());
                exit;
            }
        }

        $ids[$table] = $relation['values'][$value];

        foreach ($relation['relate'] as $relate) {
            $ok = $Db->relate(array(
                'tables' => array(
                    array(
                        'table' => $table,
                        'conditions' => array(
                            'id' => $ids[$table]
                        )
                    ),
                    array(
                        'table' => $relate,
                        'conditions' => array(
                            'id' => $ids[$relate]
                        )
                    )
                )
            ));
        }
    }
}

die ('<h1>Ended at '.date('Y-m-d H:i:s').'</h1>');
