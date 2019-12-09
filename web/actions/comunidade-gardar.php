<?php
defined('ANS') or die();

if (empty($user)) {
    return false;
}

include ($Data->file('acl-comunidade-editar.php'));

$f = $Vars->var['comunidade'];

$action = $comunidade ? 'update' : 'insert';

$query = array(
    'table' => 'comunidade',
    'data' => array(
        'url' => ($comunidade['url'] ?: $f['nome']),
        'nome' => $f['nome'],
        'texto' => $f['texto'],
        'web' => $f['web'],
        'correo' => $f['correo'],
        'telefono' => $f['telefono'],
        'logo' => $f['logo'],
        'data_alta' => ($comunidade['data_alta'] ?: date('Y-m-d H:i:s')),
        'idioma' => ($comunidade['idioma'] ?: LANGUAGE),
        'activo' => 1
    ),
    'limit' => 1,
    'conditions' => array(
        'id' => $comunidade['id']
    ),
    'relate' => array(
        array(
            'table' => 'usuarios',
            'name' => 'autor',
            'limit' => 1,
            'conditions' => array(
                'id' => ($comunidade['usuarios_autor']['id'] ?: $user['id'])
            )
        )
    )
);

$query = translateQuery($query, $comunidade['id']);

$id_comunidade = $Db->$action($query);

if (empty($id_comunidade)) {
    $Vars->message($Errors->getList(), 'ko');
    return false;
}

if ($action === 'insert') {
    $Data->execute('actions|sub-logs.php', array(
        'table' => 'comunidade',
        'id' => $id_comunidade,
        'action' => 'crear'
    ));

    $Vars->message(__('A ficha foi creada correctamente'), 'ok');
} else {
    $Data->execute('actions|sub-backups.php', array(
        'table' => 'comunidade',
        'id' => $comunidade['id'],
        'action' => 'editar',
        'content' => $comunidade
    ));

    $Vars->message(__('A ficha foi actualizada correctamente'), 'ok');
}

$comunidade = $Db->select(array(
    'table' => 'comunidade',
    'fields' => 'url',
    'limit' => 1,
    'conditions' => array(
        'id' => ($comunidade['id'] ?: $id_comunidade)
    )
));

redirect(path('comunidade', $comunidade['url']));
