<?php
/**
* phpCan - http://idc.anavallasuiza.com/
*
* phpCan is released under the GNU Affero GPL version 3
*
* More information at license.txt
*/

defined('ANS') or die();

$Config->load('session.php');

$Session = new \ANS\PHPCan\Users\Session('Session');
$Acl = new \ANS\PHPCan\Users\Acl('Acl');

$Session->setSettings();

$Session->add('regular');

$Session->load();

$user = array();

$logged = $Session->logged('regular');

if (empty($logged)) {
    return true;
}

$user = $Session->user('');

$user['roles'] = $Db->select(array(
    'table' => 'roles',
    'fields' => '*',
    'conditions' => array(
        'usuarios.id' => $user['id']
    )
));

$user['permissions'] = $Db->select(array(
    'table' => 'permissions',
    'fields' => '*',
    'conditions' => array(
        'roles.id' => simpleArrayColumn($user['roles'], 'id')
    )
));

$Acl->setPermission($user['permissions']);
