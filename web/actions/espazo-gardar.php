<?php
defined('ANS') or die();

if (empty($user)) {
    return false;
}

$especies = explode(",", $Vars->var['especies']['url']);

include ($Data->file('acl-espazo-editar.php'));

$f = $Vars->var['espazo'];

$action = $espazo ? 'update' : 'insert';

$query = array(
    'table' => 'espazos',
    'data' => array(
        'url' => ($espazo['url'] ?: $f['titulo']),
        'titulo' => $f['titulo'],
        'texto' => $f['texto'],
        'lugar' => $f['lugar'],
        'data' => ($espazo['data'] ?: date('Y-m-d H:i:s')),
        'idioma' => ($espazo['idioma'] ?: LANGUAGE),
        'kml' => $f['kml'],
        'activo' => 1
    ),
    'limit' => 1,
    'conditions' => array(
        'id' => $espazo['id']
    ),
    'relate' => array(
        array(
            'table' => 'espazos_tipos',
            'limit' => 1,
            'conditions' => array(
                'id' => $Vars->var['espazos_tipos']
            )
        ),
        array(
            'table' => 'espazos_figuras',
            'limit' => 1,
            'conditions' => array(
                'id' => $Vars->var['espazos_figuras']
            )
        ),
        array(
            'table' => 'territorios',
            'limit' => 1,
            'conditions' => array(
                'id' => $Vars->var['territorios']
            )
        ),
        array(
            'table' => 'usuarios',
            'name' => 'autor',
            'limit' => 1,
            'conditions' => array(
                'id' => ($espazo['usuarios_autor']['id'] ?: $user['id'])
            )
        ),
        array(
            'table' => 'usuarios',
            'name' => 'vixiar',
            'limit' => 1,
            'conditions' => array(
                'id' => $user['id']
            )
        )
    )
);

if ($espazo && $Acl->check('action', 'espazo-validar')) {
    $query['data']['validado'] = $f['validado'];
}

$query = translateQuery($query, $espazo['id']);

$id_espazo = $Db->$action($query);

if (empty($id_espazo)) {
    $Vars->message($Errors->getList(), 'ko');
    return false;
}

if ($action === 'insert') {
    $Data->execute('actions|sub-logs.php', array(
        'table' => 'espazos',
        'id' => $id_espazo,
        'action' => 'crear'
    ));

    $Vars->message(__('O espazo foi creado correctamente'), 'ok');
} else {
    $id_espazo = $espazo['id'];

    $Data->execute('actions|sub-backups.php', array(
        'table' => 'espazos',
        'id' => $espazo['id'],
        'action' => 'editar',
        'content' => $espazo
    ));

    $Vars->message(__('O espazo foi actualizado correctamente'), 'ok');
    
    $Db->unrelate(array(
        'tables' => array(
            array(
                'table' => 'espazos',
                'limit' => 1,
                'conditions' => array(
                    'id' => $espazo['id']
                )
            ),
            array(
                'table' => 'especies',
                'conditions' => array(
                    'url !=' => $especies
                )
            )
        )
    ));
}

if ($especies) {
    $Db->relate(array(
        'tables' => array(
            array(
                'table' => 'espazos',
                'limit' => 1,
                'conditions' => array(
                    'id' => ($espazo['id'] ?: $id_espazo)
                )
            ),
            array(
                'table' => 'especies',
                'conditions' => array(
                    'url' => $especies
                )
            )
        )
    ));
}

$espazo = $Db->select(array(
    'table' => 'espazos',
    'fields' => 'url',
    'limit' => 1,
    'conditions' => array(
        'id' => ($espazo['id'] ?: $id_espazo)
    )
));
    
$Data->execute('actions|sub-shapes.php', array(
    'table' => 'espazos',
    'id' => $espazo['id'],
    'shapes' => $Vars->var['shapes'],
    'pois' => true
));

$Data->execute('actions|sub-imaxes.php', array(
    'table' => 'espazos',
    'id' => $espazo['id'],
    'imaxes' => $Vars->var['imaxes']
));

redirect(path('espazo', $espazo['url']));
