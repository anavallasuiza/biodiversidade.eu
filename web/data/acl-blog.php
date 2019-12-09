<?php
defined('ANS') or die();

$blog = $Db->select(array(
    'table' => 'blogs',
    'fields' => '*',
    'limit' => 1,
    'conditions' => array(
        'url' => $Vars->var['blog'],
        'activo' => 1
    ),
    'add_tables' => array(
        array(
            'table' => 'usuarios',
            'name' => 'autor',
            'fields' => '*'
        ),
        array(
            'table' => 'proxectos',
            'fields' => array('url', 'titulo')
        ),
        'vixiar' => array(
            'table' => 'usuarios',
            'name' => 'vixiar',
            'fields' => 'id',
            'conditions' => array(
                'id' => $user['id']
            )
        ),
    )
));

if (empty($blog)) {
    $Vars->message(__('Sentímolo, pero parece que este contido xa non é accesible.'), 'ko');
    referer(path('blogs'));
}

define('MEU', ($user && in_array($user['id'], simpleArrayColumn($blog['usuarios_autor'], 'id'), true)));

if (MEU || ($Acl->check('action', 'blog-editar-all'))) {
    $Acl->setPermission('action', 'blog-editar', true);
} else {
    $Acl->setPermission('action', 'blog-editar', false);
}

if (MEU || ($Acl->check('action', 'blog-eliminar-all'))) {
    $Acl->setPermission('action', 'blog-eliminar', true);
} else {
    $Acl->setPermission('action', 'blog-eliminar', false);
}
