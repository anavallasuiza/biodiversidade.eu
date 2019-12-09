<?php
defined('ANS') or die();

if (empty($user)) {
    $Vars->message(__('Sentímolo, pero este contido é só accesible para usuarios rexistrados.'), 'ko');
    referer(path(''));
}

include ($Data->file('acl-ameaza-editar.php'));

$f = $Vars->var;

if (empty($f['ameazas']['titulo']) || empty($f['ameazas']['texto']) || empty($f['ameazas_tipos']['id'])) {
    $Vars->message(__('Os campos de título, texto e tipo de ameaza son obrigatorios'), 'ko');
    return false;
}

$action = $ameaza ? 'update' : 'insert';

$query = array(
    'table' => 'ameazas',
    'data' => array(
        'url' => ($ameaza['url'] ?: $f['ameazas']['titulo']),
        'titulo' => $f['ameazas']['titulo'],
        'texto' => $f['ameazas']['texto'],
        'lugar' => $f['ameazas']['lugar'],
        'nivel' => $f['ameazas']['nivel'],
        'data' => date('Y-m-d', strtotime($f['ameazas']['data'])),
        'data_alta' => ($ameaza['data_alta'] ?: date('Y-m-d H:i:s')),
        'idioma' => ($ameaza['idioma'] ?: LANGUAGE),
        'kml' => $f['ameazas']['kml'],
        'estado' => $f['ameazas']['estado'],
        'activo' => 1
    ),
    'limit' => 1,
    'conditions' => array(
        'id' => $ameaza['id']
    ),
    'relate' => array(
        array(
            'table' => 'usuarios',
            'name' => 'autor',
            'limit' => 1,
            'conditions' => array(
                'id' => ($ameaza['usuarios_autor']['id'] ?: $user['id'])
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

$query = translateQuery($query, $ameaza['id']);

$id_ameaza = $Db->$action($query);

if (empty($id_ameaza)) {
    $Vars->message($Errors->getList(), 'ko');
    return false;
}

if ($action === 'insert') {
    $Data->execute('actions|sub-logs.php', array(
        'table' => 'ameazas',
        'id' => $id_ameaza,
        'action' => 'crear'
    ));
} else {
    $id_ameaza = $ameaza['id'];

    $Data->execute('actions|sub-backups.php', array(
        'table' => 'ameazas',
        'id' => $ameaza['id'],
        'action' => 'editar',
        'content' => $ameaza
    ));
}

$relate = array(
    array(
        'exists' => $f['especies']['url'],
        'table' => 'especies',
        'conditions' => array(
            'url' => explode(',', $f['especies']['url'])
        )
    ),
    array(
        'exists' => $f['territorio'],
        'table' => 'paises',
        'conditions' => array(
            'territorios.url' => $f['territorio']
        ),
        'limit' => 1
    ),
    array(
        'exists' => $f['territorio'],
        'table' => 'territorios',
        'conditions' => array(
            'url' => $f['territorio']
        ),
        'limit' => 1
    ),
    array(
        'exists' => $f['provincia'],
        'table' => 'provincias',
        'conditions' => array(
            'nome-url' => $f['provincia']
        ),
        'limit' => 1
    ),
    array(
        'exists' => $f['concello'],
        'table' => 'concellos',
        'conditions' => array(
            'nome-url' => $f['concello']
        ),
        'limit' => 1
    ),
    array(
        'exists' => $f['ameazas_tipos']['id'],
        'table' => 'ameazas_tipos',
        'conditions' => array(
            'id' => $f['ameazas_tipos']['id']
        )
    ),
    array(
        'exists' => $f['proxectos']['id'],
        'table' => 'proxectos',
        'conditions' => array(
            'id' => $f['proxectos']['id'],
            'usuarios.id' => $user['id']
        )
    )
);

foreach ($relate as $relation) {
    $Db->unrelate(array(
        'name' => $relation['name'],
        'tables' => array(
            array(
                'table' => 'ameazas',
                'conditions' => array(
                    'id' => $id_ameaza
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
                'table' => 'ameazas',
                'conditions' => array(
                    'id' => $id_ameaza
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

if (empty($ameaza)) {
    $id_punto = $Db->insert(array(
        'table' => 'puntos',
        'data' => array(),
        'relate' => array(
            array(
                'table' => 'ameazas',
                'conditions' => array(
                    'id' => $id_ameaza
                )
            )
        )
    ));

    $ameaza = array(
        'id' => $id_ameaza,
        'puntos' => array(
            'id' => $id_punto
        )
    );
}

$Data->execute('actions|sub-shapes.php', array(
    'table' => 'ameazas',
    'id' => $ameaza['id'],
    'shapes' => $f['shapes']
));

$Data->execute('actions|sub-imaxes.php', array(
    'table' => 'ameazas',
    'id' => $ameaza['id'],
    'imaxes' => $f['imaxes']
));

$url = $Db->select(array(
    'table' => 'ameazas',
    'fields' => 'url',
    'limit' => 1,
    'conditions' => array(
        'id' => $ameaza['id']
    )
));

if (($action === 'update') && ($ameaza['estado'] !== $f['ameazas']['estado'])) {
    $Data->execute('actions|mail.php', array(
        'vixiantes' => array(
            'ameazas(vixiar).id' => $ameaza['id']
        ),
        'text' => array(
            'code' => 'mail-ameaza-estado',
            'ameaza' => $f['ameaza']['titulo'],
            'estado-antes' => ($f['ameazas']['estado'] ? __('desactiva') : __('activa')),
            'estado-agora' => ($f['ameazas']['estado'] ? __('activa') : __('desactiva'))
        )
    ));
}

if ($action === 'insert') {
    $especies = explode(',', $f['especies']['url']);

    foreach ($especies as $especie) {
        $especie = $Db->select(array(
            'table' => 'especies',
            'fields' => 'nome',
            'limit' => 1,
            'conditions' => array(
                'url' => $especie
            )
        ));

        if (empty($especie)) {
            continue;
        }

        $Data->execute('actions|mail.php', array(
            'log' => array(
                'action' => 'nova-relacion',
                'table' => 'especies',
                'id' => $especie['id'],
                'table2' => 'ameazas',
                'id2' => $ameaza['id']
            ),
            'vixiantes' => array(
                'especies(vixiar).id' => $especie['id']
            ),
            'text' => array(
                'code' => 'mail-ameaza-especie',
                'ameaza' => $f['ameazas']['titulo'],
                'especie' => $especie['nome'],
                'link' => absolutePath('ameaza', $url['url'])
            )
        ));
    }
}

redirect(path('ameaza', $url['url']));
