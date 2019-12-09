<?php
defined('ANS') or die();

if (empty($user)) {
    $Vars->message(__('Sentímolo, pero este contido é só accesible para usuarios rexistrados.'), 'ko');
    referer(path(''));
}

include ($Data->file('acl-comunidade-editar.php'));

$Data->execute('acl-action.php', array('action' => 'comunidade-eliminar'));

$Data->execute('actions|sub-backups.php', array(
    'table' => 'comunidade',
    'id' => $comunidade['id'],
    'action' => 'eliminar',
    'content' => $comunidade
));

$Db->delete(array(
    'table' => 'comunidade',
    'limit' => 1,
    'conditions' => array(
        'id' => $comunidade['id']
    )
));

$Vars->message(__('A ficha foi eliminada correctamente.'), 'ok');

redirect(path('comunidade'));