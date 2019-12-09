<?php
defined('ANS') or die();

function debug ($message, $title = '', $line = '', $tag = 'h4') {
    echo '<'.$tag.'>'.$line.' - '.$title.'</'.$tag.'>';
    print_r($message);
}

echo '<pre>';
echo '<h1>Started at '.date('Y-m-d H:i:s').'</h1>';

set_time_limit(0);
ini_set('max_execution_time', 0);
ini_set('memory_limit', '1024M');

$Db->query('TRUNCATE paises;');
$Db->query('TRUNCATE provincias;');
$Db->query('TRUNCATE concellos;');

$DB4G = new \ANS\PHPCan\Data\Db('Db');
$DB4G->setConnection('4gotas');

$paises = $provincias = $concellos;

$mapas = $DB4G->queryResult('SELECT * FROM mapas ORDER BY codigo ASC;');

foreach ($mapas as $mapa) {
    if (!preg_match('/^EURO\-(ES|PT)/', $mapa['codigo'])) {
        continue;
    }

    if (preg_match('/^EURO\-(ES|PT)$/', $mapa['codigo'])) {
        $paises[$mapa['codigo']] = $Db->insert(array(
            'table' => 'paises',
            'data' => array(
                'url' => trim($mapa['nome-gl']),
                'nome-gl' => trim($mapa['nome-gl']),
                'nome-es' => trim($mapa['nome-es']),
                'nome-en' => trim($mapa['nome-es']),
                'nome-pt' => trim($mapa['nome-gl'])
            )
        ));

        continue;
    }

    if (preg_match('/^(EURO\-PT)\-[A-Z]+$/', $mapa['codigo'], $pais)) {
        if (empty($paises[$pais[1]])) {
            debug($pais[1]);
            debug($paises);
            die('No existe pais!!');
        }

        $provincias[$mapa['codigo']] = $Db->insert(array(
            'table' => 'provincias',
            'data' => array(
                'nome' => trim($mapa['nome-gl'])
            ),
            'relate' => array(
                array(
                    'table' => 'paises',
                    'conditions' => array(
                        'id' => $paises[$pais[1]]
                    )
                )
            )
        ));

        continue;
    }

    if (preg_match('/^(EURO\-ES)\-[A-Z]+\-[A-Z]+$/', $mapa['codigo'], $pais)) {
        if (empty($paises[$pais[1]])) {
            debug($pais[1]);
            debug($paises);
            die('No existe pais!!');
        }

        $provincias[$mapa['codigo']] = $Db->insert(array(
            'table' => 'provincias',
            'data' => array(
                'nome' => trim($mapa['nome-gl'])
            ),
            'relate' => array(
                array(
                    'table' => 'paises',
                    'conditions' => array(
                        'id' => $paises[$pais[1]]
                    )
                )
            )
        ));

        continue;
    }

    if (preg_match('/^(EURO\-(ES|PT).*)\-[0-9]+$/', $mapa['codigo'], $provincia)) {
        if (empty($provincias[$provincia[1]])) {
            debug($provincia[1]);
            debug($provincias);
            die('No existe provincia!!');
        }

        $concellos[$mapa['codigo']] = $Db->insert(array(
            'table' => 'concellos',
            'data' => array(
                'nome' => trim($mapa['nome-gl'])
            ),
            'relate' => array(
                array(
                    'table' => 'provincias',
                    'conditions' => array(
                        'id' => $provincias[$provincia[1]]
                    )
                )
            )
        ));
    }
}

die ('<h1>Ended at '.date('Y-m-d H:i:s').'</h1>');
