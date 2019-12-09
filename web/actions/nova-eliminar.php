<?php
defined('ANS') or die();

if (empty($user)) {
    $Vars->message(__('Sentímolo, pero este contido é só accesible para usuarios rexistrados.'), 'ko');
    referer(path(''));
}

include ($Data->file('acl-nova-editar.php'));

$Data->execute('acl-action.php', array('action' => 'nova-eliminar'));

$Data->execute('actions|sub-backups.php', array(
    'table' => 'novas',
    'id' => $nova['id'],
    'action' => 'eliminar',
    'content' => $nova
));

$Db->delete(array(
    'table' => 'novas',
    'limit' => 1,
    'conditions' => array(
        'id' => $nova['id']
    )
));

$Vars->message(__('A nova foi eliminada correctamente.'), 'ok');

redirect(path('novas'));