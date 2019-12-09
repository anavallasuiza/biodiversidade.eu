<?php
defined('ANS') or die();

set_time_limit(0);

ini_set('max_execution_time', 0);
ini_set('memory_limit', -1);

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

$file = BASE_PATH.'tmp/especies-completo.csv';

if (!is_file($file)) {
    die('File not found');
}

$file = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

array_shift($file);

$total = count($file);

$exists = $Db->select(array(
    'table' => 'especies',
    'data' => 'nome',
    'field_as_key' => 'nome'
));

$simples = array();

foreach ($exists as $row) {
    if (!strstr($row['nome'], ' subsp. ') && !strstr($row['nome'], ' var. ')) {
        $simples[preg_replace('/\s?\(.*/', '', $row['nome'])] = $row['id'];
    }
}

foreach ($file as $i => $row) {
    echo '<h3>Processing row '.$i.' of '.$total.'</h3>';

    if (empty($row)) {
        continue;
    }

    ob_flush();
    flush();

    $csv = array_map(function ($value) {
        return str_replace('_', '', trim($value));
    }, str_getcsv($row, ';', '"'));

    if (strstr($csv[12], ' subsp. ') || strstr($csv[12], ' var. ')) {
        $simple = preg_replace('/ (subsp|var)\..*/', '', $csv[12]);
    } else if (strstr($csv[12], '  ')) {
        $simple = preg_replace('/\s{2,}.*/', '', $csv[12]);
    }

    if (strstr($csv[12], '(')) {
        $nome = preg_replace('/\s{2,}/', ' ', $csv[12]);
    } else {
        $nome = preg_replace('/\s{2,}/', ' (', $csv[12]).')';
    }

    if (empty($exists[$nome])) {
        echo '<h1>NOT FOUND '.$nome.'</h1>';
        continue;
    }

    $update = array();

    if (strstr($nome, ')') && !strstr($nome, '(')) {
        $update['nome'] = str_replace(')', '', $nome);
        $update['nome_cientifico'] = str_replace(')', '', $nome);
    }

    if ($csv[11]) {
        if (strstr($nome, ' subsp. ')) {
            $update['subespecie'] = $csv[11];
            $update['subespecie_autor'] = $csv[13];
        } else if (strstr($nome, ' var. ')) {
            $update['variedade'] = $csv[11];
            $update['variedade_autor'] = $csv[13];
        }
    }

    if (strstr($nome, ' subsp. ') || strstr($nome, ' var. ')) {
        if ($simples[$simple]) {
            echo '<h1>RELATE '.$nome.' > '.$simple.'</h1>';

            $Db->relate(array(
                'tables' => array(
                    array(
                        'table' => 'especies',
                        'limit' => 1,
                        'conditions' => array(
                            'id' => $exists[$nome]['id']
                        )
                    ),
                    array(
                        'table' => 'especies',
                        'limit' => 1,
                        'conditions' => array(
                            'id' => $simples[$simple]
                        )
                    )
                )
            ));
        } else {
            echo '<h1>INSERT + RELATE '.$nome.' > '.$simple.'</h1>';

            $simples[$simple] = $Db->insert(array(
                'table' => 'especies',
                'data' => array(
                    'url' => $simple,
                    'nome' => $simple,
                    'nome_cientifico' => $simple,
                ),
                'relate' => array(
                    array(
                        'table' => 'especies',
                        'limit' => 1,
                        'conditions' => array(
                            'id' => $exists[$nome]['id']
                        )
                    ),
                )
            ));
        }
    }

    if ($update) {
        echo '<h1>UPDATE '.$nome.'</h1>';

        $Db->update(array(
            'table' => 'especies',
            'data' => $update,
            'limit' => 1,
            'conditions' => array(
                'id' => $exists[$nome]['id']
            )
        ));
    }
}

die ('<h1>Ended at '.date('Y-m-d H:i:s').'</h1>');
