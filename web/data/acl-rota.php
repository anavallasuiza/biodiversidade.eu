<?php
defined('ANS') or die();

$rota = $Db->select(array(
    'table' => 'rotas',
    'fields' => '*',
    'limit' => 1,
    'language' => 'all',
    'conditions' => array(
        'url' => $Vars->var['url'],
        'activo' => 1
    ),
    'add_tables' => array(
        array(
            'table' => 'territorios',
            'fields' => array('url', 'nome'),
            'limit' => 1
        ),
        array(
            'table' => 'paises',
            'fields' => 'nome',
            'limit' => 1
        ),
        array(
            'table' => 'provincias',
            'fields' => 'nome',
            'limit' => 1
        ),
        array(
            'table' => 'concellos',
            'fields' => 'nome',
            'limit' => 1
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
        'vixiar' => array(
            'table' => 'usuarios',
            'name' => 'vixiar',
            'fields' => 'id',
            'limit' => 1,
            'conditions' => array(
                'id' => $user['id']
            )
        ),
        array(
            'table' => 'especies',
            'fields' => '*',
            'group' => 'url',
            'add_tables' => array(
                array(
                    'table' => 'grupos',
                    'limit' => 1
                ),
                array(
                    'table' => 'familias',
                    'limit' => 1
                )
            )
        ),
        'puntos' => array(
            'table' => 'pois',
            'fields' => '*',
            'add_tables' => array(
                array(
                    'table' => 'pois_tipos',
                    'limit' => 1
                )
            )
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
                    'sort' => 'orde desc',
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
                    'sort' => 'orde desc',
                    'fields' => '*'
                )
            )
        ),
        'imaxe' => array(
            'table' => 'imaxes',
            'fields' => '*',
            'sort' => 'portada DESC',
            'limit' => 1,
            'conditions' => array(
                'activo' => 1
            )
        )
    )
));

if (empty($rota)) {
    $Vars->message(__('Sentímolo, pero parece que este contido xa non é accesible.'), 'ko');
    referer(path('rotas'));
}

$rota = translateRow($rota, 'rotas');

define('MEU', ($user && ($rota['usuarios_autor']['id'] == $user['id'])));

if (MEU || ($Acl->check('action', 'rota-editar-all'))) {
    $Acl->setPermission('action', 'rota-editar', true);
} else {
    $Acl->setPermission('action', 'rota-editar', false);
}

if (MEU || ($Acl->check('action', 'rota-eliminar-all'))) {
    $Acl->setPermission('action', 'rota-eliminar', true);
} else {
    $Acl->setPermission('action', 'rota-eliminar', false);
}
