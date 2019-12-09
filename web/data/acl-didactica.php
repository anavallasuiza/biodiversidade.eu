<?php
defined('ANS') or die();

$didactica = $Db->select(array(
    'table' => 'didacticas',
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

if (empty($didactica)) {
    $Vars->message(__('Sentímolo, pero parece que este contido xa non é accesible.'), 'ko');
    referer(path('didacticas'));
}

$didactica = translateRow($didactica, 'didacticas');

define('MEU', ($user && ($didactica['usuarios_autor']['id'] == $user['id'])));

if (MEU || ($Acl->check('action', 'didactica-editar-all'))) {
    $Acl->setPermission('action', 'didactica-editar', true);
} else {
    $Acl->setPermission('action', 'didactica-editar', false);
}

if (MEU || ($Acl->check('action', 'didactica-eliminar-all'))) {
    $Acl->setPermission('action', 'didactica-eliminar', true);
} else {
    $Acl->setPermission('action', 'didactica-eliminar', false);
}
