<?php
defined('ANS') or die();

$caderno = $Db->select(array(
    'table' => 'cadernos',
    'fields' => '*',
    'limit' => 1,
    'conditions' => array(
        'url' => $Vars->var['url'],
        'proxectos.id' => $proxecto['id'],
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

if (empty($caderno)) {
    $Vars->message(__('Sentímolo, pero parece que este contido xa non é accesible.'), 'ko');
    referer(path('proxectos'));
}

if (MEU || ($Acl->check('action', 'caderno-editar-all'))) {
    $Acl->setPermission('action', 'caderno-editar', true);
} else {
    $Acl->setPermission('action', 'caderno-editar', false);
}

if (MEU || ($Acl->check('action', 'caderno-eliminar-all'))) {
    $Acl->setPermission('action', 'caderno-eliminar', true);
} else {
    $Acl->setPermission('action', 'caderno-eliminar', false);
}
