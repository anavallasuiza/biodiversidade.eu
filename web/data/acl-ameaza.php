<?php
defined('ANS') or die();

$ameaza = $Db->select(array(
    'table' => 'ameazas',
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
            'fields' => array('url', 'nome', 'email'),
            'limit' => 1
        ),
        array(
            'table' => 'paises',
            'fields' => array('nome', 'email'),
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
            'table' => 'especies',
            'fields' => array('url', 'nome')
        ),
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
        array(
            'table' => 'ameazas_tipos',
            'fields' => '*'
        ),
        array(
            'table' => 'puntos',
            'fields' => '*'
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
        array(
            'table' => 'proxectos',
            'fields' => array('url', 'titulo')
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

if (empty($ameaza)) {
    $Vars->message(__('Sentímolo, pero parece que este contido xa non é accesible.'), 'ko');
    referer(path('ameazas'));
}

$ameaza = translateRow($ameaza, 'ameazas');

$meu = ($user && ($ameaza['usuarios_autor']['id'] == $user['id']));

if ($meu || ($Acl->check('action', 'ameaza-editar-all'))) {
    $Acl->setPermission('action', 'ameaza-editar', true);
} else {
    $Acl->setPermission('action', 'ameaza-editar', false);
}

if ($meu || ($Acl->check('action', 'ameaza-eliminar-all'))) {
    $Acl->setPermission('action', 'ameaza-eliminar', true);
} else {
    $Acl->setPermission('action', 'ameaza-eliminar', false);
}
