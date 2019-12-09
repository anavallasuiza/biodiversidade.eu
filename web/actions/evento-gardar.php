<?php
defined('ANS') or die();

if (empty($user)) {
    return false;
}

include ($Data->file('acl-evento-editar.php'));

$f = $Vars->var['eventos'];

$action = $evento ? 'update' : 'insert';

$query = array(
    'table' => 'eventos',
    'data' => array(
        'url' => ($evento['url'] ?: $f['titulo']),
        'titulo' => $f['titulo'],
        'texto' => $f['texto'],
        'lugar' => $f['lugar'],
        'data_alta' => ($evento['data_alta'] ?: date('Y-m-d H:i:s')),
        'data' => date('Y-m-d H:i:s', strtotime($f['data'])),
        'idioma' => ($evento['idioma'] ?: LANGUAGE),
        'activo' => 1
    ),
    'limit' => 1,
    'conditions' => array(
        'id' => $evento['id']
    ),
    'relate' => array(
        array(
            'table' => 'usuarios',
            'name' => 'autor',
            'limit' => 1,
            'conditions' => array(
                'id' => ($evento['usuarios_autor']['id'] ?: $user['id'])
            )
        )
    )
);

$query = translateQuery($query, $evento['id']);

$id_evento = $Db->$action($query);

if (empty($id_evento)) {
    $Vars->message($Errors->getList(), 'ko');
    return false;
}

if ($action === 'insert') {
    $Data->execute('actions|sub-logs.php', array(
        'table' => 'eventos',
        'id' => $id_evento,
        'action' => 'crear'
    ));

    $Vars->message(__('O evento foi creado correctamente'), 'ok');
} else {
    $Data->execute('actions|sub-backups.php', array(
        'table' => 'eventos',
        'id' => $evento['id'],
        'action' => 'editar',
        'content' => $evento
    ));

    $Vars->message(__('O evento foi actualizado correctamente'), 'ok');
}

$evento = $Db->select(array(
    'table' => 'eventos',
    'fields' => 'url',
    'limit' => 1,
    'conditions' => array(
        'id' => ($evento['id'] ?: $id_evento)
    )
));

$Data->execute('actions|sub-adxuntos.php', array(
    'table' => 'eventos',
    'id' => $evento['id'],
    'adxuntos' => $Vars->var['adxuntos']
));

$Data->execute('actions|sub-imaxes.php', array(
    'table' => 'eventos',
    'id' => $evento['id'],
    'imaxes' => $Vars->var['imaxes']
));

redirect(path('evento', $evento['url']));
