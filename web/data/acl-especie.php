<?php
defined('ANS') or die();

$especie = $Db->select(array(
    'table' => 'especies',
    'fields' => '*',
    'limit' => 1,
    'language' => 'all',
    'conditions' => array(
        'url' => $Vars->var['url'],
        'activo' => 1
    ),
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
        ),
        array(
            'table' => 'proteccions_tipos',
            'sort' => 'nome ASC',
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
        )
    )
));

if (empty($especie)) {
    $Vars->message(__('Sentímolo, pero parece que este contido xa non é accesible.'), 'ko');
    referer(path('catalogo'));
}

$especie = translateRow($especie, 'especies');

define('MEU', ($user && ($especie['usuarios_autor']['id'] == $user['id'])));

if (empty($especie['bloqueada']) && (MEU || ($Acl->check('action', 'especie-editar-all')))) {
    $Acl->setPermission('action', 'especie-editar', true);
} else {
    $Acl->setPermission('action', 'especie-editar', false);
}

if (empty($especie['bloqueada']) && (MEU || ($Acl->check('action', 'especie-eliminar-all')))) {
    $Acl->setPermission('action', 'especie-eliminar', true);
} else {
    $Acl->setPermission('action', 'especie-eliminar', false);
}

if (!MEU || ($Acl->check('action', 'especie-validar'))) {
    $Acl->setPermission('action', 'especie-validar', true);
} else {
    $Acl->setPermission('action', 'especie-validar', false);
}