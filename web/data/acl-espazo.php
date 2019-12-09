<?php
defined('ANS') or die();

$espazo = $Db->select(array(
    'table' => 'espazos',
    'fields' => '*',
    'limit' => 1,
    'language' => 'all',
    'conditions' => array(
        'url' => $Vars->var['url'],
        'activo' => 1
    ),
    'add_tables' => array(
        array(
            'table' => 'usuarios',
            'name' => 'autor',
            'fields' => '*',
            'limit' => 1
        ),
        'vixiar' => array(
            'table' => 'usuarios',
            'name' => 'vixiar',
            'fields' => 'id',
            'conditions' => array(
                'id' => $user['id']
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
        array(
            'table' => 'espazos_figuras',
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
        )
    )
));

if (empty($espazo)) {
    $Vars->message(__('Sentímolo, pero parece que este contido xa non é accesible.'), 'ko');
    referer(path('espazos'));
}

$espazo = translateRow($espazo, 'espazos');

define('MEU', ($user && ($espazo['usuarios_autor']['id'] == $user['id'])));

if (MEU || ($Acl->check('action', 'espazo-editar-all'))) {
    $Acl->setPermission('action', 'espazo-editar', true);
} else {
    $Acl->setPermission('action', 'espazo-editar', false);
}

if (MEU || ($Acl->check('action', 'espazo-eliminar-all'))) {
    $Acl->setPermission('action', 'espazo-eliminar', true);
} else {
    $Acl->setPermission('action', 'espazo-eliminar', false);
}
