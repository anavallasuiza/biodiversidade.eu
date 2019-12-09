<?php
defined('ANS') or die();

if (empty($user)) {
    $Vars->message(__('Sentímolo, pero este contido é só accesible para usuarios rexistrados.'), 'ko');
    referer(path(''));
}

$f = $Vars->var;

include ($Data->file('acl-proxecto-editar.php'));

if (empty($f['proxectos']['titulo']) || empty($f['proxectos']['intro']) || empty($f['proxectos']['texto'])) {
    $Vars->message(__('Os campos de título, introdución e texto son obrigatorios'), 'ko');
    return false;   
}

$action = $proxecto ? 'update' : 'insert';

$query = array(
    'table' => 'proxectos',
    'data' => array(
        'url' => $f['proxectos']['titulo'],
        'titulo' => $f['proxectos']['titulo'],
        'intro' => $f['proxectos']['intro'],
        'texto' => $f['proxectos']['texto'],
        'aberto' => $f['proxectos']['aberto'],
        'data' => ($proxecto['data'] ?: date('Y-m-d H:i:s')),
        'activo' => 1
    ),
    'limit' => 1,
    'conditions' => array(
        'id' => $proxecto['id']
    ),
    'relate' => array(
        array(
            'table' => 'usuarios',
            'name' => 'autor',
            'conditions' => array(
                'id' => ($proxecto['usuarios_autor']['id'] ?: $user['id'])
            ),
            'limit' => 1
        ),
        array(
            'table' => 'usuarios',
            'conditions' => array(
                'id' => $user['id']
            ),
            'limit' => 1
        )
    )
);

$id_proxecto = $Db->$action($query);

if (empty($id_proxecto)) {
    $Vars->message($Errors->getList(), 'ko');
    return false;
}

if ($action === 'insert') {
    $proxecto = array('id' => $id_proxecto);

    $Data->execute('actions|sub-logs.php', array(
        'table' => 'proxectos',
        'id' => $proxecto['id'],
        'action' => 'crear'
    ));
} else {
    $Data->execute('actions|sub-backups.php', array(
        'table' => 'proxectos',
        'id' => $proxecto['id'],
        'action' => 'editar',
        'content' => $proxecto
    ));
}

$relate = array(
    array(
        'exists' => $f['ameazas']['url'],
        'table' => 'ameazas',
        'conditions' => array(
            'url' => explode(',', $f['ameazas']['url'])
        )
    ),
    array(
        'exists' => $f['espazos']['url'],
        'table' => 'espazos',
        'conditions' => array(
            'url' => explode(',', $f['espazos']['url'])
        )
    ),
    array(
        'exists' => $f['iniciativas']['url'],
        'table' => 'iniciativas',
        'conditions' => array(
            'url' => explode(',', $f['iniciativas']['url'])
        )
    ),
    array(
        'exists' => $f['especies']['url'],
        'table' => 'especies',
        'conditions' => array(
            'url' => explode(',', $f['especies']['url'])
        )
    )
);

foreach ($relate as $relation) {
    $Db->unrelate(array(
        'name' => $relation['name'],
        'tables' => array(
            array(
                'table' => 'proxectos',
                'conditions' => array(
                    'id' => $id_proxecto
                )
            ),
            array(
                'table' => $relation['table'],
                'conditions' => 'all'
            )
        )
    ));

    if (empty($relation['exists'])) {
        continue;
    }

    $Db->relate(array(
        'name' => $relation['name'],
        'tables' => array(
            array(
                'table' => 'proxectos',
                'conditions' => array(
                    'id' => $id_proxecto
                ),
                'limit' => 1
            ),
            array(
                'table' => $relation['table'],
                'conditions' => $relation['conditions'],
                'limit' => $relation['limit']
            )
        )
    ));
}

$proxecto = $Db->select(array(
    'table' => 'proxectos',
    'fields' => '*',
    'limit' => 1,
    'conditions' => array(
        'id' => $proxecto['id']
    )
));

$Data->execute('actions|sub-adxuntos.php', array(
    'table' => 'proxectos',
    'id' => $proxecto['id'],
    'adxuntos' => $Vars->var['adxuntos']
));

$Data->execute('actions|sub-imaxes.php', array(
    'table' => 'proxectos',
    'id' => $proxecto['id'],
    'imaxes' => $Vars->var['imaxes']
));

redirect(path('proxecto', $proxecto['url']));
