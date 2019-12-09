<?php
defined('ANS') or die();

$url = $Vars->var['url'] ?: $user['nome']['url'];

$usuario = $Db->select(array(
    'table' => 'usuarios',
    'fields' => '*',
    'limit' => 1,
    'conditions' => array(
        'nome-url' => $url,
        'activo' => 1
    ),
    'add_tables' => array(
        array(
            'table' => 'roles',
            'fields' => '*',
            'conditions' => array(
                'enabled' => 1
            )
        )
    )
));

if (empty($usuario)) {
    $Vars->message(__('Sentímolo, pero parece que este contido xa non é accesible.'), 'ko');
    referer(path('perfil'));
}

$pagination = array();

$query = array(
    'limit' => 10,
    'page' => ($Vars->int('p-logs') ?: 1),
    'conditions' => array(
        'id_usuarios_autor' => $usuario['id']
    )
);

if ($usuario['id'] !== $user['id']) {
    $query['conditions']['public'] = 1;
}

$logs = $Data->execute('get-logs.php', $query);

$pagination['logs'] = $Data->pagination;

if ($usuario['id'] === $user['id']) {
    $actividade = $Data->execute('get-logs-vixiados.php', array(
        'limit' => 5,
        'page' => ($Vars->var['p-actividade'] ?: 0)
    ));

    $pagination['actividade'] = $Data->pagination;
}

$equipos = $Db->select(array(
    'table' => 'equipos',
    'fields' => '*',
    'sort' => 'titulo ASC',
    'limit' => 10,
    'conditions' => array(
        'usuarios.id' => $usuario['id'],
        'activo' => 1
    ),
    'pagination' => array(
        'page' => ($Vars->int('p-equipos') ?: 1),
        'map' => 10
    )
));

$pagination['equipos'] = $Data->pagination;

$proxectos = $Db->select(array(
    'table' => 'proxectos',
    'fields' => '*',
    'sort' => 'data DESC',
    'limit' => 10,
    'conditions' => array(
        'usuarios.id' => $usuario['id'],
        'activo' => 1
    ),
    'add_tables' => array(
        array(
            'table' => 'logs',
            'fields' => '*',
            'sort' => 'id DESC',
            'limit' => 1,
            'conditions' => array(
                'public' => 1
            ),
            'add_tables' => array(
                array(
                    'table' => 'usuarios',
                    'name' => 'autor',
                    'fields' => '*',
                    'limit' => 1
                )
            )
        )
    ),
    'pagination' => array(
        'page' => ($Vars->int('p-proxectos') ?: 1),
        'map' => 10
    )
));

$pagination['proxectos'] = $Data->pagination;

$avistamentos = $Db->select(array(
    'table' => 'avistamentos',
    'fields' => '*',
    'sort' => 'data_observacion DESC',
    'limit' => 10,
    'conditions' => array(
        'id_usuarios_autor' => $usuario['id'],
        'activo' => 1
    ),
    'add_tables' => array(
        array(
            'table' => 'especies',
            'fields' => array('url', 'nome'),
            'limit' => 1
        ),
        'imaxe' => array(
            'table' => 'imaxes',
            'fields' => '*',
            'limit' => 1,
            'sort' => 'portada DESC',
            'conditions' => array(
                'portada' => 1,
                'activo' => 1
            )
        )
    ),
    'pagination' => array(
        'page' => ($Vars->int('p-avistamentos') ?: 1),
        'map' => 10
    )
));

$pagination['avistamentos'] = $Data->pagination;

$rotas = $Db->select(array(
    'table' => 'rotas',
    'fields' => '*',
    'sort' => 'data DESC',
    'limit' => 10,
    'conditions' => array(
        'id_usuarios_autor' => $usuario['id'],
        'activo' => 1
    ),
    'add_tables' => array(
        'imaxe' => array(
            'table' => 'imaxes',
            'fields' => '*',
            'limit' => 1,
            'sort' => 'portada DESC',
            'conditions' => array(
                'portada' => 1,
                'activo' => 1
            )
        )
    ),
    'pagination' => array(
        'page' => ($Vars->int('p-rotas') ?: 1),
        'map' => 10
    )
));

$pagination['rotas'] = $Data->pagination;

$notas = $Db->select(array(
    'table' => 'notas',
    'fields' => '*',
    'sort' => 'data DESC',
    'limit' => 10,
    'conditions' => array(
        'usuarios.id' => $usuario['id']
    ),
    'add_tables' => array(
        array(
            'table' => 'puntos',
            'fields' => 'id'
        )
    ),
    'pagination' => array(
        'page' => ($Vars->int('p-notas') ?: 1),
        'map' => 10
    )
));

$pagination['notas'] = $Data->pagination;

if ($usuario['id'] === $user['id']) {
    if ($Acl->check('action', 'especie-validar')) {
        $especies_validar = $Db->select(array(
            'table' => 'especies',
            'fields' => '*',
            'sort' => 'data_alta DESC',
            'limit' => 21,
            'group' => 'especies.id',
            'conditions' => array(
                'validada' => 0,
                'activo' => 1,
                'id_usuarios_autor !=' => $user['id']
            ),
            'add_tables' => array(
                'imaxe' => array(
                    'table' => 'imaxes',
                    'fields' => '*',
                    'limit' => 1,
                    'sort' => 'portada DESC',
                    'conditions' => array(
                        'portada' => 1,
                        'activo' => 1
                    )
                )
            ),
            'pagination' => array(
                'page' => ($Vars->int('p-especies_validar') ?: 1),
                'map' => 10
            )
        ));

        $pagination['especies_validar'] = $Data->pagination;
    } else {
        $especies_validar = array();
    }

    if ($Acl->check('action', 'avistamento-validar')) {
        $observadores = $Db->select(array(
            'table' => 'usuarios',
            'fields' => 'nome',
            'sort' => 'nome ASC',
            'group' => 'id',
            'conditions' => array(
                'activo' => 1,
                'avistamentos(autor).id >' => 0,
                'avistamentos(autor).validado' => 0
            )
        ));

        $query = array(
            'table' => 'avistamentos',
            'fields' => '*',
            'sort' => 'data_observacion DESC',
            'limit' => 10,
            'group' => 'avistamentos.id',
            'conditions' => array(
                'validado' => 0,
                'activo' => 1,
                'id_usuarios_autor !=' => $user['id']
            ),
            'add_tables' => array(
                array(
                    'table' => 'especies',
                    'fields' => array('url', 'nome'),
                    'limit' => 1
                ),
                'imaxe' => array(
                    'table' => 'imaxes',
                    'fields' => '*',
                    'limit' => 1,
                    'sort' => 'portada DESC',
                    'conditions' => array(
                        'portada' => 1,
                        'activo' => 1
                    )
                ),
                array(
                    'table' => 'usuarios',
                    'name' => 'autor',
                    'fields' => 'nome',
                    'limit' => 1
                )
            ),
            'pagination' => array(
                'page' => ($Vars->int('p-avistamentos_validar') ?: 1),
                'map' => 10
            )
        );

        if ($Vars->var['observador']) {
            $query['conditions']['id_usuarios_autor'] = (int)$Vars->var['observador'];
        }

        $avistamentos_validar = $Db->select($query);

        $pagination['avistamentos_validar'] = $Data->pagination;
    } else {
        $avistamentos_validar = array();
    }

    if ($Acl->check('action', 'rota-validar')) {
        $rotas_validar = $Db->select(array(
            'table' => 'rotas',
            'fields' => '*',
            'sort' => 'data DESC',
            'limit' => 10,
            'group' => 'rotas.id',
            'conditions' => array(
                'validado' => 0,
                'activo' => 1,
                'id_usuarios_autor !=' => $user['id']
            ),
            'add_tables' => array(
                array(
                    'table' => 'especies',
                    'fields' => array('url', 'nome')
                ),
                'imaxe' => array(
                    'table' => 'imaxes',
                    'fields' => '*',
                    'limit' => 1,
                    'sort' => 'portada DESC',
                    'conditions' => array(
                        'portada' => 1,
                        'activo' => 1
                    )
                )
            ),
            'pagination' => array(
                'page' => ($Vars->int('p-rotas_validar') ?: 1),
                'map' => 10
            )
        ));

        $pagination['rotas_validar'] = $Data->pagination;
    } else {
        $rotas_validar = array();
    }
} else {
    $Acl->setPermission('action', 'especie-validar', false);
    $Acl->setPermission('action', 'avistamento-validar', false);
    $Acl->setPermission('action', 'rota-validar', false);

    $especies_validar = $avistamentos_validar = $rotas_validar = array();
}

/*if ($Vars->action['perfil-gardar-nivel'] === null) {
    $Vars->var['role'] = $rol['id'];
}*/

$Html->meta('title', $usuario['nome']['title']);
