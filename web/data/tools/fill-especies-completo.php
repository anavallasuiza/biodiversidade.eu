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

$Db->query('TRUNCATE TABLE `reinos`');
$Db->query('TRUNCATE TABLE `grupos`');

$Db->query('INSERT INTO `grupos` SET url = "plantas", `nome-gl` = "Plantas";');
$Db->query('INSERT INTO `reinos` SET url = "plantae", `nome` = "Plantae";');

$Db->query('UPDATE grupos SET id_reinos = 1;');
$Db->query('UPDATE filos SET id_grupos = 1, id_reinos = 1;');
$Db->query('UPDATE clases SET id_grupos = 1, id_reinos = 1;');
$Db->query('UPDATE ordes SET id_grupos = 1, id_reinos = 1;');
$Db->query('UPDATE familias SET id_grupos = 1, id_reinos = 1;');
$Db->query('UPDATE xeneros SET id_grupos = 1, id_reinos = 1;');
$Db->query('UPDATE especies SET id_grupos = 1, id_reinos = 1;');

$file = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

array_shift($file);

$total = count($file);

$relations = array(
    'grupos' => array(
        'table' => 'grupos',
        'column' => 1,
        'values' => array(),
        'relate' => array(),
        'insert' => array(
            'nome' => 1
        )
    ),
    'reinos' => array(
        'table' => 'reinos',
        'column' => 2,
        'values' => array(),
        'relate' => array('grupos'),
        'insert' => array(
            'nome' => 2
        )
    ),
    'filos' => array(
        'table' => 'filos',
        'column' => 3,
        'values' => array(),
        'relate' => array('grupos', 'reinos'),
        'insert' => array(
            'nome' => 3
        )
    ),
    'clases' => array(
        'table' => 'clases',
        'column' => 4,
        'values' => array(),
        'relate' => array('grupos', 'reinos', 'filos'),
        'insert' => array(
            'nome' => 4
        )
    ),
    'ordes' => array(
        'table' => 'ordes',
        'column' => 5,
        'values' => array(),
        'relate' => array('grupos', 'reinos', 'filos', 'clases'),
        'insert' => array(
            'nome' => 5
        )
    ),
    'familias' => array(
        'table' => 'familias',
        'column' => 35,
        'values' => array(),
        'relate' => array('grupos', 'reinos', 'filos', 'clases', 'ordes'),
        'insert' => array(
            'nome' => 35
        )
    ),
    'xeneros' => array(
        'table' => 'xeneros',
        'column' => 6,
        'values' => array(),
        'relate' => array('grupos', 'reinos', 'filos', 'clases', 'ordes', 'familias'),
        'insert' => array(
            'nome' => 6
        )
    ),
    'especies' => array(
        'table' => 'especies',
        'column' => 12,
        'values' => array(),
        'relate' => array('grupos', 'reinos', 'filos', 'clases', 'ordes', 'familias', 'xeneros'),
        'insert' => array(
            'nome' => 12,
            'nome_cientifico' => 12,
            'autor' => 73,
            'sinonimos' => 70,
            'lsid_name' => 0,
            'especie' => 8,
            'taxon_id' => 71
        )
    )
);

$exists = array();

foreach ($relations as $table => $relation) {
    $exists[$table] = array();

    $rows = $Db->select(array(
        'table' => $table,
        'fields' => 'nome',
        'field_as_key' => 'id'
    ));

    foreach ($rows as $row) {
        $exists[$table][$row['id']] = strtolower(preg_replace('/\W/', '', $row['nome']));
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

    foreach ($relations as $relation) {
        if (empty($csv[$relation['column']])) {
            continue 2;
        }
    }

    foreach ($relations as $table => $relation) {
        $code = strtolower(preg_replace('/\W/', '', $csv[$relation['column']]));

        if (($id_content = array_search($code, $exists[$table])) === false) {
            $data = array(
                'url' => $csv[$relation['column']]
            );

            foreach ($relation['insert'] as $field => $column) {
                $data[$field] = trim($csv[$column]);
            }

            if ($table === 'especies') {
                if (strstr($data['nome'], '  ')) {
                    list($nome, $autor) = preg_split('/\s{2,}/', $data['nome']);

                    $autor = trim(str_replace(array('(', ')'), '', trim($autor)));
                    $autor = trim(preg_replace('/\s*,\s*[0-9]+/', '', $autor));

                    $data['url'] = $data['nome'] = $data['nome_cientifico'] = $nome;
                    $data['autor'] = $autor;
                }
            }

            $query = array(
                'table' => $table,
                'data' => $data
            );

            $id_content = $Db->insert($query);

            if (empty($id_content)) {
                pre($csv);
                pre($query);
                die();
            }

            $exists[$table][$id_content] = $code;
        }

        foreach ($relation['relate'] as $relate) {
            $relate = $relations[$relate];
            $code_relate = strtolower(preg_replace('/\W/', '', $csv[$relate['column']]));

            if (($id_relate = array_search($code_relate, $exists[$relate['table']])) === false) {
                pre($relate);
                pre($csv);
                die();
            }

            $query = array(
                'tables' => array(
                    array(
                        'table' => $table,
                        'limit' => 1,
                        'conditions' => array(
                            'id' => $id_content
                        )
                    ),
                    array(
                        'table' => $relate['table'],
                        'limit' => 1,
                        'conditions' => array(
                            'id' => $id_relate
                        )
                    )
                )
            );

            $Db->relate($query);
        }
    }
}

$Db->query('UPDATE grupos SET activo = 1;');
$Db->query('UPDATE reinos SET activo = 1;');

$Db->update(array(
    'table' => 'especies',
    'data' => array(
        'data_alta' => date('Y-m-d H:i:s'),
        'validada' => 1,
        'activo' => 1
    ),
    'conditions' => array(
        'id' => array_keys($exists['especies'])
    )
));

die ('<h1>Ended at '.date('Y-m-d H:i:s').'</h1>');
