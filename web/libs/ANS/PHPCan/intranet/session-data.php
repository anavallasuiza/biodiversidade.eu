<?php
defined('ANS') or die();

$user['roles'] = $Db->select(array(
    'table' => 'roles',
    'fields' => '*',
    'conditions' => array(
        'usuarios.id' => $user['id']
    )
));

$permissions = $Db->select(array(
    'table' => 'permissions',
    'fields' => '*',
    'group' => 'permissions.id',
    'conditions' => array(
        'roles.id' => arrayKeyValues($user['roles'], 'id')
    )
));

$Acl->setPermission($permissions);

$user['fullname'] = trim($user['name'].' '.$user['surname1'].' '.$user['surname2']);
