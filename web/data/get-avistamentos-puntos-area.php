<?php
defined('ANS') or die();

$avistamentos = \Eu\Biodiversidade\FerramentaAreas::getAvistamentos($Vars->var);

$puntos = \Eu\Biodiversidade\FerramentaAreas::getPuntos($Vars->var);

foreach ($avistamentos as $i => $avistamento) {
    $avistamento['puntos'] = array();
    $avistamento['centroides1'] = array();
    $avistamento['centroides10'] = array();

    foreach ($puntos['puntos'] as $punto) {
        if ($punto['id_avistamentos'] == $avistamento['id']) {
            array_push($avistamento['puntos'], $punto);
        }
    }

    foreach ($puntos['centroides1'] as $centroide1) {
        if ($centroide1['id_avistamentos'] == $avistamento['id']) {
            array_push($avistamento['centroides1'], $centroide1);
        }
    }

    foreach ($puntos['centroides10'] as $centroide10) {
        if ($centroide10['id_avistamentos'] == $avistamento['id']) {
            array_push($avistamento['centroides10'], $centroide10);
        }
    }

    $avistamentos[$i] = $avistamento;
}

$idsAvistamentos = arrayKeyValues($avistamentos, 'id');
$idsPuntos = arrayKeyValues(arrayKeyValues($avistamentos, 'puntos'), 'id');
$idsCentroides1 = arrayKeyValues(arrayKeyValues($avistamentos, 'centroides1'), 'id');
$idsCentroides10 = arrayKeyValues(arrayKeyValues($avistamentos, 'centroides10'), 'id');

$avistamentos = $Db->select(array(
    'table' => 'avistamentos',
    'limit' => 500,
    'conditions' => array(
        'activo' => 1,
        'id' => $idsAvistamentos
    ),
    'add_tables' => array(
        array(
            'table' => 'usuarios',
            'name' => 'autor',
            'fields' => '*',
            'limit' => 1
        ),
        array(
            'table' => 'especies',
            'fields' => '*',
            'limit' => 1
        ),
         array(
            'table' => 'puntos',
            'conditions' => array(
                'puntos_tipos.numero' => 4,
                'id' => $idsPuntos
            ),
            'add_tables' => array(
                array(
                    'table' => 'puntos_tipos',
                    'limit' => 1
                )
            )
        ),
        'centroides1' => array(
            'table' => 'puntos',
            'conditions' => array(
                'puntos_tipos.numero' => 1,
                'id' => $idsCentroides1
            ),
            'add_tables' => array(
                array(
                    'table' => 'puntos_tipos',
                    'limit' => 1
                )
            )
        ),
        'centroides10' => array(
            'table' => 'puntos',
            'conditions' => array(
                'puntos_tipos.numero' => 2,
                'id' => $idsCentroides10
            ),
            'add_tables' => array(
                array(
                    'table' => 'puntos_tipos',
                    'limit' => 1
                )
            )
        ),
        'imaxe' => array(
            'table' => 'imaxes',
            'sort' => 'portada DESC',
            'limit' => 1,
            'conditions' => array(
                'activo' => 1
            ),
            'add_tables' => array(
                array(
                    'table' => 'imaxes_tipos',
                    'fields' => '*',
                    'limit' => 1,
                    'conditions' => array(
                        'activo' => 1
                    )
                )
            )
        )
    )
));
