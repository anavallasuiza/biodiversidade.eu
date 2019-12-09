<?php
defined('ANS') or die();

$comunidade = $Db->select(array(
    'table' => 'comunidade',
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
        )
    )
));

if (empty($comunidade)) {
    $Vars->message(__('Sentímolo, pero parece que este contido xa non é accesible.'), 'ko');
    referer(path('comunidade'));
}

$comunidade = translateRow($comunidade, 'comunidade');

define('MEU', ($user && ($comunidade['usuarios_autor']['id'] == $user['id'])));

if (MEU || ($Acl->check('action', 'comunidade-editar-all'))) {
    $Acl->setPermission('action', 'comunidade-editar', true);
} else {
    $Acl->setPermission('action', 'comunidade-editar', false);
}

if (MEU || ($Acl->check('action', 'comunidade-eliminar-all'))) {
    $Acl->setPermission('action', 'comunidade-eliminar', true);
} else {
    $Acl->setPermission('action', 'comunidade-eliminar', false);
}
