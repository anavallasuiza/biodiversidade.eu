<?php
defined('ANS') or die();

$novas = setRowsLanguage(array(
    'table' => 'novas',
    'fields' => '*',
    'limit' => 2,
    'sort' => 'data DESC',
    'conditions' => array(
        'data <=' => date('Y-m-d H:i:s'),
        'activo' => 1
    )
));

$eventos = setRowsLanguage(array(
    'table' => 'eventos',
    'fields' => '*',
    'limit' => 3,
    'sort' => 'data ASC',
    'conditions' => array(
        'data >=' => date('Y-m-d'),
        'activo' => 1
    ),
    'add_tables' => array(
        array(
            'table' => 'comentarios',
            'fields' => 'id'
        )
    )
));

$ids = $Db->queryResult(
    'SELECT id, id_especies FROM (
        SELECT id, id_especies
        FROM avistamentos
        ORDER BY data_alta DESC) ordered
    GROUP BY id_especies
    ORDER BY id DESC
    LIMIT 100'
);

$avistamentos = $Db->select(array(
    'table' => 'avistamentos',
    'fields' => '*',
    'limit' => 2,
    'sort' => 'data_alta DESC',
    'conditions' => array(
        'id' => simpleArrayColumn($ids, 'id'),
        'id_especies' => 0,
        'activo' => 1
    ),
    'add_tables' => array(
        array(
            'table' => 'usuarios',
            'name' => 'autor',
            'fields' => '*',
            'limit' => 1
        ),
        array(
            'table' => 'usuarios',
            'name' => 'validador',
            'fields' => '*',
            'limit' => 1
        ),
        'imaxe' => array(
            'table' => 'imaxes',
            'fields' => '*',
            'sort' => 'portada DESC',
            'limit' => 1,
            'conditions' => array(
                'activo' => 1
            )
        ),
        array(
            'table' => 'puntos',
            'conditions' => array(
                'puntos_tipos.numero' => 4,
            ),
            'add_tables' => array(
                array(
                    'table' => 'puntos_tipos',
                    'limit' => 1
                )
            )
        ),
        'centroides1' => array(
            'table' => 'puntos',
            'conditions' => array(
                'puntos_tipos.numero' => 1,
            ),
            'add_tables' => array(
                array(
                    'table' => 'puntos_tipos',
                    'limit' => 1
                )
            )
        ),
        'centroides10' => array(
            'table' => 'puntos',
            'conditions' => array(
                'puntos_tipos.numero' => 2,
            ),
            'add_tables' => array(
                array(
                    'table' => 'puntos_tipos',
                    'limit' => 1
                )
            )
        ),
        array(
            'table' => 'comentarios',
            'conditions' => array(
                'activo' => 1
            )
        )
    )
));

$avistamentos = array_merge($avistamentos, $Db->select(array(
    'table' => 'avistamentos',
    'fields' => '*',
    'limit' => (6 - count($avistamentos)),
    'sort' => 'data_alta DESC',
    'conditions' => array(
        'id' => simpleArrayColumn($ids, 'id'),
        'id !=' => simpleArraycolumn($avistamentos, 'id'),
        'activo' => 1
    ),
    'add_tables' => array(
        array(
            'table' => 'especies',
            'fields' => '*',
            'limit' => 1,
            'add_tables' => array(
                array(
                    'table' => 'grupos',
                    'limit' => 1,
                ),
                array(
                    'table' => 'reinos',
                    'limit' => 1,
                ),
                array(
                    'table' => 'xeneros',
                    'limit' => 1,
                ),
                array(
                    'table' => 'familias',
                    'limit' => 1,
                ),
                array(
                    'table' => 'ordes',
                    'limit' => 1,
                ),
                array(
                    'table' => 'clases',
                    'limit' => 1,
                )
            )
        ),
        array(
            'table' => 'usuarios',
            'name' => 'autor',
            'fields' => '*',
            'limit' => 1
        ),
        array(
            'table' => 'usuarios',
            'name' => 'validador',
            'fields' => '*',
            'limit' => 1
        ),
        'imaxe' => array(
            'table' => 'imaxes',
            'fields' => '*',
            'sort' => 'portada DESC',
            'limit' => 1,
            'conditions' => array(
                'activo' => 1
            )
        ),
        array(
            'table' => 'puntos',
            'conditions' => array(
                'puntos_tipos.numero' => 4,
            ),
            'add_tables' => array(
                array(
                    'table' => 'puntos_tipos',
                    'limit' => 1
                )
            )
        ),
        'centroides1' => array(
            'table' => 'puntos',
            'conditions' => array(
                'puntos_tipos.numero' => 1,
            ),
            'add_tables' => array(
                array(
                    'table' => 'puntos_tipos',
                    'limit' => 1
                )
            )
        ),
        'centroides10' => array(
            'table' => 'puntos',
            'conditions' => array(
                'puntos_tipos.numero' => 2,
            ),
            'add_tables' => array(
                array(
                    'table' => 'puntos_tipos',
                    'limit' => 1
                )
            )
        ),
        array(
            'table' => 'comentarios',
            'conditions' => array(
                'activo' => 1
            )
        )
    )
)));

shuffle($avistamentos);

$ameazas = setRowsLanguage(array(
    'table' => 'ameazas',
    'fields' => '*',
    'limit' => 2,
    'sort' => 'data DESC',
    'group' => 'ameazas.id',
    'conditions' => array(
        'activo' => 1,
        'estado' => 1
    ),
    'add_tables' => array(
        array(
            'table' => 'ameazas_tipos',
            'fields' => '*'
        ),
        array(
            'table' => 'especies',
            'fields' => '*'
        ),
        array(
            'table' => 'concellos',
            'fields' => '*',
            'limit' =>1
        ),
        array(
            'table' => 'provincias',
            'fields' => '*',
            'limit' =>1
        ),
        array(
            'table' => 'territorios',
            'fields' => '*',
            'limit' =>1
        ),
        array(
            'table' => 'comentarios',
            'fields' => 'id'
        ),
        array(
            'table' => 'puntos',
            'fields' => '*'
        ),
        array(
            'table' => 'usuarios',
            'name' => 'autor',
            'limit' => 1
        ),
        'lineas' => array(
            'table' => 'formas',
            'fields' => '*',
            'conditions' => array(
                'tipo' => 'polyline'
            ),
            'add_tables' => array(
                array(
                    'table' => 'puntos',
                    'fields' => '*'
                )
            )
        ),
        'poligonos' => array(
            'table' => 'formas',
            'fields' => '*',
            'conditions' => array(
                'tipo' => 'polygon'
            ),
            'add_tables' => array(
                array(
                    'table' => 'puntos',
                    'fields' => '*'
                )
            )
        )
    )
));

foreach ($ameazas as &$ameaza) {
    $ameaza['zonas'] = getZonaAmeaza($ameaza);
    $ameaza['shapes'] = getPuntosAmeaza($ameaza, $ameaza['puntos'], $ameaza['poligonos'], $ameaza['lineas']);
}

unset($ameaza);

$iniciativas = setRowsLanguage(array(
    'table' => 'iniciativas',
    'fields' => '*',
    'limit' => 2,
    'sort' => 'data DESC',
    'group' => 'iniciativas.id',
    'conditions' => array(
        'activo' => 1,
        'estado' => 1
    ),
    'add_tables' => array(
        array(
            'table' => 'iniciativas_tipos',
            'fields' => '*'
        ),
        array(
            'table' => 'especies',
            'fields' => '*'
        ),
        array(
            'table' => 'concellos',
            'fields' => '*',
            'limit' =>1
        ),
        array(
            'table' => 'provincias',
            'fields' => '*',
            'limit' =>1
        ),
        array(
            'table' => 'territorios',
            'fields' => '*',
            'limit' =>1
        ),
        array(
            'table' => 'comentarios',
            'fields' => 'id'
        ),
        array(
            'table' => 'puntos',
            'fields' => '*'
        ),
        array(
            'table' => 'usuarios',
            'name' => 'autor',
            'limit' => 1
        ),
        'lineas' => array(
            'table' => 'formas',
            'fields' => '*',
            'conditions' => array(
                'tipo' => 'polyline'
            ),
            'add_tables' => array(
                array(
                    'table' => 'puntos',
                    'fields' => '*'
                )
            )
        ),
        'poligonos' => array(
            'table' => 'formas',
            'fields' => '*',
            'conditions' => array(
                'tipo' => 'polygon'
            ),
            'add_tables' => array(
                array(
                    'table' => 'puntos',
                    'fields' => '*'
                )
            )
        )
    )
));

foreach ($iniciativas as &$iniciativa) {
    $iniciativa['zonas'] = getZonaAmeaza($iniciativa);
    $iniciativa['shapes'] = getPuntosAmeaza($iniciativa, $iniciativa['puntos'], $iniciativa['poligonos'], $iniciativa['lineas']);
}

unset($iniciativa);

$rota = setRowsLanguage(array(
    'table' => 'rotas',
    'fields' => '*',
    'limit' => 1,
    'sort_commands' => 'RAND()',
    'conditions' => array(
        'activo' => 1
    ),
    'add_tables' => array(
        'imaxe' => array(
            'table' => 'imaxes',
            'fields' => '*',
            'sort' => 'portada DESC',
            'limit' => 1,
            'conditions' => array(
                'activo' => 1
            )
        ),
        array(
            'table' => 'especies',
            'fields' => '*'
        ),
        array(
            'table' => 'territorios',
            'fields' => '*',
            'limit' => 1
        ),
        array(
            'table' => 'comentarios',
            'fields' => 'id'
        ),
        'autor' => array(
            'table' => 'usuarios',
            'name' => 'autor',
            'fields' => '*',
            'limit' => 1
        ),
        array(
            'table' => 'usuarios',
            'name' => 'validador',
            'fields' => '*',
            'limit' => 1
        )
    )
));

$espazo = setRowsLanguage(array(
    'table' => 'espazos',
    'fields' => '*',
    'limit' => 1,
    'sort_commands' => 'RAND()',
    'conditions' => array(
        'activo' => 1
    ),
    'add_tables' => array(
        'imaxe' => array(
            'table' => 'imaxes',
            'fields' => '*',
            'sort' => 'portada DESC',
            'limit' => 1,
            'conditions' => array(
                'activo' => 1
            )
        ),
        array(
            'table' => 'usuarios',
            'name' => 'autor',
            'fields' => '*',
            'limit' => 1
        ),
        array(
            'table' => 'espazos_tipos',
            'limit' => 1
        ),
        array(
            'table' => 'territorios',
            'limit' => 1
        ),
        array(
            'table' => 'comentarios',
            'fields' => 'id'
        )
    )
));

$validar = $actividade = array();

if ($user) {
    $actividade = $Data->execute('get-logs-vixiados.php', array(
        'limit' => 5
    ));

    if ($Acl->check('action', 'especie-validar')) {
        $validar['especies'] = $Db->selectCount(array(
            'table' => 'especies',
            'conditions' => array(
                'validada' => 0,
                'activo' => 1,
                'id_usuarios_autor !=' => $user['id']
            )
        ));

        if (empty($validar['especies'])) {
            unset($validar['especies']);
        }
    }

    if ($Acl->check('action', 'avistamento-validar')) {
        $validar['avistamentos'] = $Db->selectCount(array(
            'table' => 'avistamentos',
            'conditions' => array(
                'validado' => 0,
                'activo' => 1,
                'id_usuarios_autor !=' => $user['id']
            )
        ));

        if (empty($validar['avistamentos'])) {
            unset($validar['avistamentos']);
        }
    }

    if ($Acl->check('action', 'rota-validar')) {
        $validar['rotas'] = $Db->selectCount(array(
            'table' => 'rotas',
            'conditions' => array(
                'validado' => 0,
                'activo' => 1,
                'id_usuarios_autor !=' => $user['id']
            )
        ));

        if (empty($validar['rotas'])) {
            unset($validar['rotas']);
        }
    }
}

$logs = $Db->select(array(
    'table' => 'logs',
    'fields' => '*',
    'limit' => 5,
    'sort' => 'id DESC',
    'conditions' => array(
        'public' => 1
    ),
    'add_tables' => array(
        array(
            'table' => 'usuarios',
            'name' => 'autor',
            'fields' => '*',
            'limit' => 1
        ),
        array(
            'table' => 'avistamentos'
        ),
        array(
            'table' => 'ameazas'
        ),
        array(
            'table' => 'blogs'
        ),
        array(
            'table' => 'blogs_posts'
        ),
        array(
            'table' => 'cadernos'
        ),
        array(
            'table' => 'comentarios'
        ),
        array(
            'table' => 'comunidade'
        ),
        array(
            'table' => 'didacticas'
        ),
        array(
            'table' => 'espazos'
        ),
        array(
            'table' => 'especies'
        ),
        array(
            'table' => 'eventos'
        ),
        array(
            'table' => 'equipos'
        ),
        array(
            'table' => 'iniciativas'
        ),
        array(
            'table' => 'novas'
        ),
        array(
            'table' => 'proxectos'
        ),
        array(
            'table' => 'rotas'
        ),
    )
));

foreach ($logs as $i => $log) {
    if ($log['novas']) {
        unset($logs[$i]);
    }
}

$Html->meta('title', __('Biodiversidade Ameazada de Galiza e norte de Portugal'));
