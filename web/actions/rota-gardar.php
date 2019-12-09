<?php
defined('ANS') or die();

if (empty($user)) {
    $Vars->message(__('Sentímolo, pero este contido é só accesible para usuarios rexistrados.'), 'ko');
    referer(path(''));
}

// pre($Vars->var['boxes']); die();

$f = $Vars->var;

include ($Data->file('acl-rota-editar.php'));

if (empty($f['rotas']['titulo']) || empty($f['rotas']['texto']) || empty($f['rotas']['dificultade'])) {
    $Vars->message(__('Os campos de título, texto e dificultade obrigatorios'), 'ko');
    return false;   
}

if ($f['especies']['url']) {
    $especies = $Db->select(array(
        'table' => 'especies',
        'fields' => '*',
        'conditions' => array(
            'url' => explode(',', $f['especies']['url'])
        )
    ));

    $especies = arrayKeyValues($especies, 'id');
} else {
    $especies = array();
}

$action = $rota ? 'update' : 'insert';

$query = array(
    'table' => 'rotas',
    'data' => array(
        'url' => ($rota['url'] ?: $f['rotas']['titulo']),
        'titulo' => $f['rotas']['titulo'],
        'texto' => $f['rotas']['texto'],
        'lugar' => $f['rotas']['lugar'],
        'area' => $f['rotas']['area'],
        'dificultade' => $f['rotas']['dificultade'],
        'distancia' => $f['rotas']['distancia'],
        'duracion' => $f['rotas']['duracion'],
        'data' => ($rota['data'] ?: date('Y-m-d H:i:s')),
        'kml' => $f['rotas']['kml'],
        'idioma' => ($rota['idioma'] ?: LANGUAGE),
        'activo' => 1
    ),
    'limit' => 1,
    'conditions' => array(
        'id' => $rota['id']
    ),
    'relate' => array(
        array(
            'table' => 'usuarios',
            'name' => 'autor',
            'limit' => 1,
            'conditions' => array(
                'id' => ($rota['usuarios_autor']['id'] ?: $user['id'])
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

if ($rota && $Acl->check('action', 'rota-validar')) {
    $query['data']['validado'] = $Vars->var['rotas']['validado'];

    if ($Vars->var['rotas']['validado']) {
        $query['relate'][] = array(
            'table' => 'usuarios',
            'name' => 'validador',
            'limit' => 1,
            'conditions' => array(
                'id' => $user['id']
            )
        );
    }
}

$query = translateQuery($query, $rota['id']);

$id_rota = $Db->$action($query);

if (empty($id_rota)) {
    $Vars->message($Errors->getList(), 'ko');
    return false;
}

if ($action === 'insert') {
    $Data->execute('actions|sub-logs.php', array(
        'table' => 'rotas',
        'id' => $id_rota,
        'action' => 'crear'
    ));
} else {
    $id_rota = $rota['id'];

    $Data->execute('actions|sub-backups.php', array(
        'table' => 'rotas',
        'id' => $rota['id'],
        'action' => 'editar',
        'content' => $rota
    ));
}

$relate = array(
    array(
        'exists' => $especies,
        'table' => 'especies',
        'conditions' => array(
            'id' => $especies
        ),
        'limit' => 1
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
    )
);

foreach ($relate as $relation) {
    $Db->unrelate(array(
        'name' => $relation['name'],
        'tables' => array(
            array(
                'table' => 'rotas',
                'conditions' => array(
                    'id' => $id_rota
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
                'table' => 'rotas',
                'conditions' => array(
                    'id' => $id_rota
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

if (empty($rota)) {
    $id_punto = $Db->insert(array(
        'table' => 'puntos',
        'data' => array(),
        'relate' => array(
            array(
                'table' => 'rotas',
                'conditions' => array(
                    'id' => $id_rota
                )
            )
        )
    ));

    $rota = array(
        'id' => $id_rota,
        'puntos' => array(
            'id' => $id_punto
        )
    );
}

$Data->execute('actions|sub-shapes.php', array(
    'table' => 'rotas',
    'id' => $rota['id'],
    'shapes' => $Vars->var['shapes'],
    'pois' => true
));

$Data->execute('actions|sub-imaxes.php', array(
    'table' => 'rotas',
    'id' => $rota['id'],
    'imaxes' => $f['imaxes']
));

$url = $Db->select(array(
    'table' => 'rotas',
    'fields' => 'url',
    'limit' => 1,
    'conditions' => array(
        'id' => $url['id']
    )
));

if (($action === 'insert') && $especies) {
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
                'table2' => 'rotas',
                'id2' => $rota['id']
            ),
            'vixiantes' => array(
                'especies(vixiar).id' => $especie['id']
            ),
            'text' => array(
                'code' => 'mail-rota-especie',
                'rota' => $f['rotas']['titulo'],
                'especie' => $especie['nome'],
                'link' => absolutePath('rota', $url['url'])
            )
        ));
    }
}

$Db->delete(array(
    'table' => 'puntos',
    'conditions' => array(
        'rotas.id' => $rota['id'],
        'so_altitude' => true
    )
));

if ($Vars->var['elevation']) {
    foreach ($Vars->var['elevation'] as $i => $data) {
        $id_puntos = $Db->insert(array(
            'table' => 'puntos',
            'data' => array(
                'datum' => 'wgs84',
                'latitude' => $data['lat'],
                'lonxitude' => $data['lng'],
                'altitude' => $data['elevation'],
                'tipo' => 'latlong',
                'so_altitude' => true,
                'orde' => $i
            )
        ));

        if (empty($id_puntos)) {
            $Vars->message($Errors->getList(), 'ko');
            return false;
        }

        $Db->relate(array(
            'tables' => array(
                array(
                    'table' => 'rotas',
                    'limit' => 1,
                    'conditions' => array(
                        'id' => $rota['id']
                    )
                ),
                array(
                    'table' => 'puntos',
                    'limit' => 1,
                    'conditions' => array(
                        'id' => $id_puntos
                    )
                )
            )
        ));
    }
}

$Db->unrelate(array(
    'tables' => array(
        array(
            'table' => 'rotas',
            'limit' => 1,
            'conditions' => array(
                'id' => $rota['id']
            )
        ),
        array(
            'table' => 'avistamentos',
            'conditions' => 'all'
        )
    )
));

$Db->unrelate(array(
    'tables' => array(
        array(
            'table' => 'rotas',
            'limit' => 1,
            'conditions' => array(
                'id' => $rota['id']
            )
        ),
        array(
            'table' => 'puntos',
            'conditions' => array(
                'puntos_tipos.numero' => array(1, 2)
            )
        )
    )
));

if ($Vars->var['boxes']) {
    foreach ($Vars->var['boxes'] as $box) {
        $data = array('points' => array());
        $data['points'][0] = array('lat' => $box['ne']['lat'], 'lng' => $box['ne']['lng']);
        $data['points'][1] = array('lat' => $box['sw']['lat'], 'lng' => $box['ne']['lng']);
        $data['points'][2] = array('lat' => $box['sw']['lat'], 'lng' => $box['sw']['lng']);
        $data['points'][3] = array('lat' => $box['ne']['lat'], 'lng' => $box['sw']['lng']);

        $avistamentos = \Eu\Biodiversidade\FerramentaAreas::getAvistamentos($data);
        $ids = arrayKeyValues($avistamentos, 'id');

        $Db->relate(array(
            'tables' => array(
                array(
                    'table' => 'rotas',
                    'limit' => 1,
                    'conditions' => array(
                        'id' => $rota['id']
                    )
                ),
                array(
                    'table' => 'avistamentos',
                    'conditions' => array(
                        'activo' => 1,
                        'id' => $ids
                    )
                )
            )
        ));

        $Db->relate(array(
            'tables' => array(
                array(
                    'table' => 'rotas',
                    'limit' => 1,
                    'conditions' => array(
                        'id' => $rota['id']
                    )
                ),
                array(
                    'table' => 'especies',
                    'conditions' => array(
                        'activo' => 1,
                        'avistamentos.id' => $ids
                    )
                )
            )
        ));

        $xeolocalizacions = \Eu\Biodiversidade\FerramentaAreas::getPuntos($data);

        $idsPuntos = arrayKeyValues($xeolocalizacions['puntos'], 'id');

        $Db->relate(array(
            'tables' => array(
                array(
                    'table' => 'rotas',
                    'limit' => 1,
                    'conditions' => array(
                        'id' => $rota['id']
                    )
                ),
                array(
                    'table' => 'puntos',
                    'conditions' => array(
                        'id' => $idsPuntos
                    )
                )
            )
        ));

        $idsCentroides1 = arrayKeyValues($xeolocalizacions['centroides1'], 'id');

        $Db->relate(array(
            'tables' => array(
                array(
                    'table' => 'rotas',
                    'limit' => 1,
                    'conditions' => array(
                        'id' => $rota['id']
                    )
                ),
                array(
                    'table' => 'puntos',
                    'conditions' => array(
                        'id' => $idsCentroides1
                    )
                )
            )
        ));

        $idsCentroides10 = arrayKeyValues($xeolocalizacions['centroides10'], 'id');

        $Db->relate(array(
            'tables' => array(
                array(
                    'table' => 'rotas',
                    'limit' => 1,
                    'conditions' => array(
                        'id' => $rota['id']
                    )
                ),
                array(
                    'table' => 'puntos',
                    'conditions' => array(
                        'id' => $idsCentroides10
                    )
                )
            )
        ));
    }
}

$rota = $Db->select(array(
    'table' => 'rotas',
    'limit' => 1,
    'conditions' => array(
        'id' => $id_rota
    )
));

redirect(path('rota', $rota['url']));
