<?php
defined('ANS') or die();

$evento = $Db->select(array(
    'table' => 'eventos',
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
        ),
    )
));

if (empty($evento)) {
    $Vars->message(__('Sentímolo, pero parece que este contido xa non é accesible.'), 'ko');
    referer(path('eventos'));
}

$evento = translateRow($evento, 'eventos');

define('MEU', ($user && ($evento['usuarios_autor']['id'] == $user['id'])));

if (MEU || ($Acl->check('action', 'evento-editar-all'))) {
    $Acl->setPermission('action', 'evento-editar', true);
} else {
    $Acl->setPermission('action', 'evento-editar', false);
}

if (MEU || ($Acl->check('action', 'evento-eliminar-all'))) {
    $Acl->setPermission('action', 'evento-eliminar', true);
} else {
    $Acl->setPermission('action', 'evento-eliminar', false);
}
