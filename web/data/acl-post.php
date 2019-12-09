<?php
defined('ANS') or die();

if (empty($blog)) {
    $Vars->message(__('Sentímolo, pero parece que este contido xa non é accesible.'), 'ko');
    referer(path('proxectos'));
}

$post = $Db->select(array(
    'table' => 'blogs_posts',
    'fields' => '*',
    'limit' => 1,
    'conditions' => array(
        'url' => $Vars->var['url'],
        'blogs.id' => $blog['id'],
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

if (empty($post)) {
    $Vars->message(__('Sentímolo, pero parece que este contido xa non é accesible.'), 'ko');
    referer(path('blogs'));
}

if (MEU || ($Acl->check('action', 'post-editar-all'))) {
    $Acl->setPermission('action', 'post-editar', true);
} else {
    $Acl->setPermission('action', 'post-editar', false);
}

if (MEU || ($Acl->check('action', 'post-eliminar-all'))) {
    $Acl->setPermission('action', 'post-eliminar', true);
} else {
    $Acl->setPermission('action', 'post-eliminar', false);
}
