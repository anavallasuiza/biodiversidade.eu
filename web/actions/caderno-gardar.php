<?php
defined('ANS') or die();

if (empty($user)) {
    return false;
}

include ($Data->file('acl-proxecto-editar.php'));
include ($Data->file('acl-caderno-editar.php'));

$action = $caderno ? 'update' : 'insert';

$query = array(
    'table' => 'cadernos',
    'data' => array(
        'url' => $Vars->var['cadernos']['titulo'],
        'titulo' => $Vars->var['cadernos']['titulo'],
        'texto' => $Vars->var['cadernos']['texto'],
        'data_alta' => ($caderno['data_alta'] ?: date('Y-m-d H:i:s')),
        'data_actualizado' => date('Y-m-d H:i:s'),
        'activo' => 1
    ),
    'limit' => 1,
    'conditions' => array(
        'id' => $caderno['id']
    ),
    'relate' => array(
        array(
            'table' => 'proxectos',
            'limit' => 1,
            'conditions' => array(
                'id' => $proxecto['id']
            )
        ),
        array(
            'table' => 'usuarios',
            'name' => 'autor',
            'limit' => 1,
            'conditions' => array(
                'id' => ($caderno['usuarios_autor']['id'] ?: $user['id'])
            )
        )
    )
);

$id_caderno = $Db->$action($query);

if (empty($id_caderno)) {
    $Vars->message($Errors->getList(), 'ko');
    return false;
}

if ($action === 'insert') {
    $Data->execute('actions|sub-logs.php', array(
        'table' => 'cadernos',
        'id' => $id_caderno,
        'action' => 'crear'
    ));

    $Vars->message(__('O caderno foi creado correctamente'), 'ok');
} else {
    $Data->execute('actions|sub-backups.php', array(
        'table' => 'cadernos',
        'id' => $caderno['id'],
        'action' => 'editar',
        'content' => $caderno
    ));

    $Vars->message(__('O caderno foi actualizado correctamente'), 'ok');
}

$caderno = $Db->select(array(
    'table' => 'cadernos',
    'fields' => 'url',
    'limit' => 1,
    'conditions' => array(
        'id' => ($caderno['id'] ?: $id_caderno)
    )
));

$Data->execute('actions|sub-imaxes.php', array(
    'table' => 'cadernos',
    'id' => $caderno['id'],
    'imaxes' => $Vars->var['imaxes']
));

if ($action === 'insert') {
    $Data->execute('actions|mail.php', array(
        'log' => array(
            'action' => 'crear',
            'table' => 'cadernos',
            'id' => $id_caderno
        ),
        'vixiantes' => array(
            'proxectos.id' => $proxecto['id']
        ),
        'text' => array(
            'code' => 'mail-caderno-novo',
            'proxecto' => $proxecto['titulo'],
            'caderno' => $Vars->var['cadernos']['titulo'],
            'link' => absolutePath('caderno', $proxecto['url'], $caderno['url'])
        )
    ));
}

redirect(path('caderno', $proxecto['url'], $caderno['url']));
