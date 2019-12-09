<?php
defined('ANS') or die();

if (empty($table) || empty($id) || empty($adxuntos) || empty($adxuntos['arquivo'])) {
    return false;
}

foreach ($adxuntos['arquivo'] as $i => $arquivo) {
    $titulo = $adxuntos['titulo'][$i];

    if (empty($titulo)) {
        continue;
    }

    $query = array(
        'table' => 'adxuntos',
        'data' => array(
            'arquivo' => $arquivo,
            'titulo' => $titulo
        ),
        'limit' => 1,
        'conditions' => array(
            'id' => $adxuntos['id'][$i],
            $table.'.id' => $id
        ),
        'relate' => array(
            array(
                'table' => $table,
                'conditions' => array(
                    'id' => $id
                )
            )
        )
    );

    if (empty($adxuntos['id'][$i])) {
        if ($arquivo['error'] === 4) {
            continue;
        }

        $action = 'insert';
    } else {
        if ($arquivo['error'] === 4) {
            unset($data['arquivo']);
        }

        $action = 'update';
    }

    $Db->$action($query);
}

$adxuntos['borrar'] = array_filter((array)$adxuntos['borrar']);

if ($adxuntos['borrar']) {
    $Db->delete(array(
        'table' => 'adxuntos',
        'conditions' => array(
            'id' => $adxuntos['borrar'],
            $table.'.id' => $id
        )
    ));
}

return true;
