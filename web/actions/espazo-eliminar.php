<?php
defined('ANS') or die();

if (empty($user)) {
    $Vars->message(__('Sentímolo, pero este contido é só accesible para usuarios rexistrados.'), 'ko');
    referer(path(''));
}

include ($Data->file('acl-espazo-editar.php'));

$Data->execute('acl-action.php', array('action' => 'espazo-eliminar'));

$Data->execute('actions|sub-backups.php', array(
    'table' => 'espazos',
    'id' => $espazo['id'],
    'action' => 'eliminar',
    'content' => $espazo
));

$Db->delete(array(
    'table' => 'puntos',
    'conditions' => array(
        'espazos.id' => $espazo['id']
    )
));

$Db->delete(array(
    'table' => 'espazos',
    'limit' => 1,
    'conditions' => array(
        'id' => $espazo['id']
    )
));

$Vars->message(__('O espazo foi eliminado correctamente.'), 'ok');

redirect(path('espazos'));