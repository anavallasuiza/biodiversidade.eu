<?php
defined('ANS') or die();

if (empty($user)) {
    return false;
}

include ($Data->file('acl-nova-editar.php'));

$f = $Vars->var['novas'];

$action = $nova ? 'update' : 'insert';

$query = array(
    'table' => 'novas',
    'data' => array(
        'url' => ($nova['url'] ?: $f['titulo']),
        'titulo' => $f['titulo'],
        'texto' => $f['texto'],
        'data' => ($nova['data'] ?: date('Y-m-d H:i:s')),
        'idioma' => ($nova['idioma'] ?: LANGUAGE),
        'activo' => 1
    ),
    'limit' => 1,
    'conditions' => array(
        'id' => $nova['id']
    ),
    'relate' => array(
        array(
            'table' => 'usuarios',
            'name' => 'autor',
            'limit' => 1,
            'conditions' => array(
                'id' => ($nova['usuarios_autor']['id'] ?: $user['id'])
            )
        )
    )
);

$query = translateQuery($query, $nova['id']);

$id_nova = $Db->$action($query);

if (empty($id_nova)) {
    $Vars->message($Errors->getList(), 'ko');
    return false;
}

if ($action === 'insert') {
    $Data->execute('actions|sub-logs.php', array(
        'table' => 'novas',
        'id' => $id_nova,
        'action' => 'crear'
    ));

    $Vars->message(__('A nova foi creada correctamente'), 'ok');
} else {
    $Data->execute('actions|sub-backups.php', array(
        'table' => 'novas',
        'id' => $nova['id'],
        'action' => 'editar',
        'content' => $nova
    ));

    $Vars->message(__('A nova foi actualizada correctamente'), 'ok');
}

$nova = $Db->select(array(
    'table' => 'novas',
    'fields' => 'url',
    'limit' => 1,
    'conditions' => array(
        'id' => ($nova['id'] ?: $id_nova)
    )
));

$Data->execute('actions|sub-imaxes.php', array(
    'table' => 'novas',
    'id' => $nova['id'],
    'imaxes' => $Vars->var['imaxes']
));

redirect(path('nova', $nova['url']));
