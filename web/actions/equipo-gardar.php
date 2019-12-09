<?php
defined('ANS') or die();

if (empty($user)) {
    return false;
}

include ($Data->file('acl-equipo-editar.php'));

$action = $equipo ? 'update' : 'insert';

$query = array(
    'table' => 'equipos',
    'data' => array(
        'url' => $Vars->var['equipos']['titulo'],
        'titulo' => $Vars->var['equipos']['titulo'],
        'texto' => $Vars->var['equipos']['texto'],
        'imaxe' => $Vars->var['equipos']['imaxe'],
        'data' => ($equipo['data_alta'] ?: date('Y-m-d H:i:s')),
        'activo' => 1
    ),
    'limit' => 1,
    'conditions' => array(
        'id' => $equipo['id']
    ),
    'relate' => array(
        array(
            'table' => 'usuarios',
            'limit' => 1,
            'conditions' => array(
                'id' => $user['id']
            )
        ),
        array(
            'table' => 'usuarios',
            'name' => 'autor',
            'limit' => 1,
            'conditions' => array(
                'id' => ($equipo['usuarios_autor']['id'] ?: $user['id'])
            )
        )
    )
);

$id_equipo = $Db->$action($query);

if (empty($id_equipo)) {
    $Vars->message($Errors->getList(), 'ko');
    return false;
}

if ($action === 'insert') {
    $Data->execute('actions|sub-logs.php', array(
        'table' => 'equipos',
        'id' => $id_equipo,
        'action' => 'crear'
    ));

    $Vars->message(__('O equipo foi creado correctamente'), 'ok');
} else {
    $Data->execute('actions|sub-backups.php', array(
        'table' => 'equipos',
        'id' => $equipo['id'],
        'action' => 'editar',
        'content' => $equipo
    ));

    $Vars->message(__('O equipo foi actualizado correctamente'), 'ok');
}

$equipo = $Db->select(array(
    'table' => 'equipos',
    'fields' => 'url',
    'limit' => 1,
    'conditions' => array(
        'id' => ($equipo['id'] ?: $id_equipo)
    )
));

redirect(path('equipo', $equipo['url']));
