<?php
defined('ANS') or die();

if (empty($user)) {
    $Vars->message(__('Sentímolo, pero este contido é só accesible para usuarios rexistrados.'), 'ko');
    referer(path(''));
}

$f = $Vars->var;

include ($Data->file('acl-iniciativa-editar.php'));

if (empty($f['iniciativas']['titulo']) || empty($f['iniciativas']['texto']) || empty($f['iniciativas_tipos']['id'])) {
    $Vars->message(__('Os campos de título, texto e tipo da iniciativa son obrigatorios'), 'ko');
    return false;   
}

$action = $iniciativa ? 'update' : 'insert';

$query = array(
    'table' => 'iniciativas',
    'data' => array(
        'url' => ($iniciativa['url'] ?: $f['iniciativas']['titulo']),
        'titulo' => $f['iniciativas']['titulo'],
        'texto' => $f['iniciativas']['texto'],
        'lugar' => $f['iniciativas']['lugar'],
        'data' => ($iniciativa['data'] ?: date('Y-m-d H:i:s')),
        'idioma' => ($iniciativa['idioma'] ?: LANGUAGE),
        'kml' => $f['iniciativas']['kml'],
        'estado' => $f['iniciativas']['estado'],
        'activo' => 1
    ),
    'limit' => 1,
    'conditions' => array(
        'id' => $iniciativa['id']
    ),
    'relate' => array(
        array(
            'table' => 'usuarios',
            'name' => 'autor',
            'limit' => 1,
            'conditions' => array(
                'id' => ($iniciativa['usuarios_autor']['id'] ?: $user['id'])
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

$query = translateQuery($query, $iniciativa['id']);

$id_iniciativa = $Db->$action($query);

if (empty($id_iniciativa)) {
    $Vars->message($Errors->getList(), 'ko');
    return false;
}

if ($action === 'insert') {
    $Data->execute('actions|sub-logs.php', array(
        'table' => 'iniciativas',
        'id' => $id_iniciativa,
        'action' => 'crear'
    ));
} else {
    $Data->execute('actions|sub-backups.php', array(
        'table' => 'iniciativas',
        'id' => $iniciativa['id'],
        'action' => 'editar',
        'content' => $iniciativa
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
        'exists' => $f['iniciativas_tipos']['id'],
        'table' => 'iniciativas_tipos',
        'conditions' => array(
            'id' => $f['iniciativas_tipos']['id']
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
                'table' => 'iniciativas',
                'conditions' => array(
                    'id' => $id_iniciativa
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
                'table' => 'iniciativas',
                'conditions' => array(
                    'id' => $id_iniciativa
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

if (empty($iniciativa)) {
    $id_punto = $Db->insert(array(
        'table' => 'puntos',
        'data' => array(),
        'relate' => array(
            array(
                'table' => 'iniciativas',
                'conditions' => array(
                    'id' => $id_iniciativa
                )
            )
        )
    ));

    $iniciativa = array(
        'id' => $id_iniciativa,
        'puntos' => array(
            'id' => $id_punto
        )
    );
}

$Data->execute('actions|sub-shapes.php', array(
    'table' => 'iniciativas',
    'id' => $iniciativa['id'],
    'shapes' => $f['shapes']
));

$Data->execute('actions|sub-imaxes.php', array(
    'table' => 'iniciativas',
    'id' => $iniciativa['id'],
    'imaxes' => $f['imaxes']
));

$url = $Db->select(array(
    'table' => 'iniciativas',
    'fields' => 'url',
    'limit' => 1,
    'conditions' => array(
        'id' => $iniciativa['id']
    )
));

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
                'table2' => 'iniciativas',
                'id2' => $iniciativa['id']
            ),
            'vixiantes' => array(
                'especies(vixiar).id' => $especie['id']
            ),
            'text' => array(
                'code' => 'mail-iniciativa-especie',
                'iniciativa' => $f['iniciativas']['titulo'],
                'especie' => $especie['nome'],
                'link' => absolutePath('iniciativa', $url['url'])
            )
        ));
    }
}

redirect(path('iniciativa', $url['url']));
