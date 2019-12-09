<?php
defined('ANS') or die();

$puntos = $Db->select(array(
    'table' => 'puntos',
    'fields' => '*',
    'fields_commands' => 'id_puntos_tipos',
    'conditions' => array(
        'tipo' => 'mgrs'
    )
));

$updated = 0;

foreach ($puntos as $punto) {
    $tipoPunto = getMGRSCentroidType($punto['mgrs']);

    if ($tipoPunto != $punto['id_puntos_tipos']) {
        $Db->update(array(
            'table' => 'puntos',
            'conditions' => array(
                'id' => $punto['id']
            ),
            'data' => array(
                'id_puntos_tipos' => $tipoPunto
            )
        ));

        $updated++;
    }
}

die($updated . " puntos actualizados");
