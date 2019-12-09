<?php
defined('ANS') or die();

if (empty($user)) {
    $Vars->message(__('Sentímolo, pero este contido é só accesible para usuarios rexistrados.'), 'ko');
    referer(path(''));
}

include ($Data->file('acl-equipo-editar.php'));

$Data->execute('acl-action.php', array('action' => 'equipo-eliminar'));

$Data->execute('actions|sub-backups.php', array(
    'table' => 'equipos',
    'id' => $equipo['id'],
    'action' => 'eliminar',
    'content' => $equipo
));

$Db->delete(array(
    'table' => 'equipos',
    'limit' => 1,
    'conditions' => array(
        'id' => $equipo['id']
    )
));

$Vars->message(__('O equipo foi eliminado correctamente.'), 'ok');

redirect(path('equipos'));