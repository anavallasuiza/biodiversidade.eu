<?php
defined('ANS') or die();

if (empty($user)) {
    $Vars->message(__('Sentímolo, pero este contido é só accesible para usuarios rexistrados.'), 'ko');
    referer(path(''));
}

include ($Data->file('acl-evento-editar.php'));

$Data->execute('acl-action.php', array('action' => 'evento-eliminar'));

$Data->execute('actions|sub-backups.php', array(
    'table' => 'eventos',
    'id' => $evento['id'],
    'action' => 'eliminar',
    'content' => $evento
));

$Db->delete(array(
    'table' => 'eventos',
    'limit' => 1,
    'conditions' => array(
        'id' => $evento['id']
    )
));

$Vars->message(__('O evento foi eliminado correctamente.'), 'ok');

redirect(path('eventos'));