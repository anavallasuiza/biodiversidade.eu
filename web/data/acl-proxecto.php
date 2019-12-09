<?php
defined('ANS') or die();

$proxecto = $Db->select(array(
    'table' => 'proxectos',
    'fields' => '*',
    'limit' => 1,
    'conditions' => array(
        'url' => $Vars->var['proxecto'],
        'activo' => 1
    ),
    'add_tables' => array(
        array(
            'table' => 'usuarios',
            'name' => 'solicitude',
            'limit' => 1,
            'conditions' => array(
                'id' => $user['id']
            )
        ),
        array(
            'table' => 'usuarios',
            'name' => 'autor',
            'fields' => '*',
            'limit' => 1
        ),
        array(
            'table' => 'usuarios',
            'fields' => '*'
        )
    )
));

if (empty($proxecto)) {
    $Vars->message(__('Sentímolo, pero parece que este contido xa non é accesible.'), 'ko');
    referer(path('proxectos'));
}

define('MEU', ($user && in_array($user['id'], simpleArrayColumn($proxecto['usuarios'], 'id'))));

if ((MEU === false) && empty($proxecto['aberto'])) {
    $Vars->message(__('Sentímolo, pero este proxecto non é público.'), 'ko');
    referer(path('proxectos'));
}

if (MEU || ($Acl->check('action', 'proxecto-editar-all'))) {
    $Acl->setPermission('action', 'proxecto-editar', true);
} else {
    $Acl->setPermission('action', 'proxecto-editar', false);
    $Acl->setPermission('action', 'caderno-crear', false);
}

if (MEU || ($Acl->check('action', 'proxecto-eliminar-all'))) {
    $Acl->setPermission('action', 'proxecto-eliminar', true);
} else {
    $Acl->setPermission('action', 'proxecto-eliminar', false);
}
