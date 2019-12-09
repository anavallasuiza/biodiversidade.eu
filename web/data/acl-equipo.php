<?php
defined('ANS') or die();

$equipo = $Db->select(array(
    'table' => 'equipos',
    'fields' => '*',
    'limit' => 1,
    'conditions' => array(
        'url' => $Vars->var['url'],
        'activo' => 1
    ),
    'add_tables' => array(
        array(
            'table' => 'usuarios',
            'fields' => array('nome', 'avatar')
        ),
        array(
            'table' => 'usuarios',
            'name' => 'autor',
            'fields' => '*',
            'limit' => 1
        )
    )
));

if (empty($equipo)) {
    $Vars->message(__('Sentímolo, pero parece que este contido xa non é accesible.'), 'ko');
    referer(path('equipos'));
}

define('MEU', ($user && ($equipo['usuarios_autor']['id'] == $user['id'])));
define('DENTRO', ($user && in_array($user['id'], simpleArrayColumn($equipo['usuarios'], 'id'))));

if (MEU || ($Acl->check('action', 'equipo-editar-all'))) {
    $Acl->setPermission('action', 'equipo-editar', true);
} else {
    $Acl->setPermission('action', 'equipo-editar', false);
}

if (MEU || ($Acl->check('action', 'equipo-eliminar-all'))) {
    $Acl->setPermission('action', 'equipo-eliminar', true);
} else {
    $Acl->setPermission('action', 'equipo-eliminar', false);
}
