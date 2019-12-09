<?php
defined('ANS') or die();

if (empty($user)) {
    die(json_encode(Array('goto' => path('login'))));
}

if (empty($Vars->var['datos'])) {
    die(1);
}

$notas = $Db->select(array(
    'table' => 'notas',
    'fields' => 'codigo',
    'field_as_key' => 'codigo',
    'conditions' => array(
        'usuarios.id' => $user['id']
    )
));

$datos = json_decode($Vars->var['datos']);

foreach ($datos as $fila) {
    if (empty($fila->data) || empty($fila->id) || empty($fila->type)) {
        continue;
    }

    $action = isset($notas[$fila->id]) ? 'update' : 'insert';

    $query = array(
        'table' => 'notas',
        'data' => array(
            'codigo' => $fila->id,
            'titulo' => $fila->data->title,
            'texto' => $fila->data->text,
            'tipo' => $fila->type,
            'data' => date('Y-m-d H:i:s', round((integer)$fila->id / 1000))
        ),
        'limit' => 1,
        'conditions' => array(
            'codigo' => $fila->id,
            'usuarios.id' => $user['id']
        ),
        'relate' => array(
            array(
                'table' => 'usuarios',
                'conditions' => array(
                    'id' => $user['id']
                )
            )
        )
    );

    $id_nota = $Db->$action($query);

    if (empty($id_nota)) {
        continue;
    }

    if ($action === 'update') {
        $Db->delete(array(
            'table' => 'puntos',
            'conditions' => array(
                'notas.codigo' => $fila->id
            )
        ));
    }

    if (empty($fila->points)) {
        continue;
    }

    foreach ($fila->points as $point) {
        $Db->insert(array(
            'table' => 'puntos',
            'data' => array(
                'titulo' => $point->text,
                'latitude' => $point->{'lonlat-coords'}->latitude,
                'lonxitude' => $point->{'lonlat-coords'}->longitude,
                'mgrs' => $point->{'mgrs-coords'},
                'datum' => ($point->{'mgrs-coords'} ? 'WGS84' : '')
            ),
            'relate' => array(
                array(
                    'table' => 'notas',
                    'limit' => 1,
                    'conditions' => array(
                        'codigo' => $fila->id
                    )
                )
            )
        ));
    }
}

die('2');
