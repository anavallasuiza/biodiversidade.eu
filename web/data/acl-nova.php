<?php
defined('ANS') or die();

$nova = $Db->select(array(
    'table' => 'novas',
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

if (empty($nova)) {
    $Vars->message(__('Sentímolo, pero parece que este contido xa non é accesible.'), 'ko');
    referer(path('novas'));
}

$nova = translateRow($nova, 'novas');

define('MEU', ($user && ($nova['usuarios_autor']['id'] == $user['id'])));

if (MEU || ($Acl->check('action', 'nova-editar-all'))) {
    $Acl->setPermission('action', 'nova-editar', true);
} else {
    $Acl->setPermission('action', 'nova-editar', false);
}

if (MEU || ($Acl->check('action', 'nova-eliminar-all'))) {
    $Acl->setPermission('action', 'nova-eliminar', true);
} else {
    $Acl->setPermission('action', 'nova-eliminar', false);
}
