<?php
defined('ANS') or die();

$codigo = $codigoAvistamento ?: $Vars->var['url'];

$avistamento = $Db->select(array(
    'table' => 'avistamentos',
    'fields' => '*',
    'limit' => 1,
    'conditions' => array(
        'url' => $codigo,
        'activo' => 1
    ),
    'add_tables' => array(
    	array(
    		'table' => 'especies',
    		'limit' => 1,
            'add_tables' => array(
                array(
                    'table' => 'grupos',
                    'fields' => 'url',
                    'limit' => 1
                )
            )
    	),
        array(
            'table' => 'grupos',
            'limit' => 1
        ),
    	array(
    		'table' => 'provincias',
    		'limit' => 1,
    	),
    	array(
    		'table' => 'concellos',
    		'limit' => 1,
    	),
    	array(
    		'table' => 'paises',
    		'limit' => 1,
    	),
    	array(
    		'table' => 'territorios',
    		'limit' => 1,
    	),
        array(
            'table' => 'ameazas_tipos',
            'name' => 'nivel1',
            'fields' => '*'
        ),
        array(
            'table' => 'ameazas_tipos',
            'name' => 'nivel2',
            'fields' => '*'
        ),
        array(
            'table' => 'habitats_tipos',
            'fields' => '*',
            'limit' => 1
        ),
        array(
            'table' => 'puntos',
            'fields' => '*',
            'sort' => 'id asc',
            'add_tables' => array(
                array(
                    'table' => 'puntos_tipos',
                    'fields' => '*',
                    'limit' => 1
                ),
                array(
                    'table' => 'datums',
                    'fields' => '*',
                    'limit' => 1
                )
            )
        ),
        array(
            'table' => 'proxectos',
            'fields' => array('url', 'titulo')
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
            'table' => 'referencias_tipos'
        )
    )
));

if (empty($avistamento)) {
    $Vars->message(__('Sentímolo, pero parece que este contido xa non é accesible.'), 'ko');
    referer(path('catalogo', 'mapa'));
}

define('MEU', ($user && ($avistamento['usuarios_autor']['id'] == $user['id'])));

if (MEU || ($Acl->check('action', 'avistamento-editar-all'))) {
    $Acl->setPermission('action', 'avistamento-editar', true);
} else {
    $Acl->setPermission('action', 'avistamento-editar', false);
}

if (MEU || ($Acl->check('action', 'avistamento-eliminar-all'))) {
    $Acl->setPermission('action', 'avistamento-eliminar', true);
} else {
    $Acl->setPermission('action', 'avistamento-eliminar', false);
}

if (!MEU || ($Acl->check('action', 'avistamento-validar'))) {
    $Acl->setPermission('action', 'avistamento-validar', true);
} else {
    $Acl->setPermission('action', 'avistamento-validar', false);
}

return $avistamento;
