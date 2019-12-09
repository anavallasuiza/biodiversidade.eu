<?php
defined('ANS') or die();

if (empty($user)) {
    $Vars->message(__('Sentímolo, pero este contido é só accesible para usuarios rexistrados.'), 'ko');
    referer(path(''));
}

include ($Data->file('acl-iniciativa-editar.php'));

$Data->execute('acl-action.php', array('action' => 'iniciativa-eliminar'));

$Data->execute('actions|sub-backups.php', array(
    'table' => 'iniciativas',
    'id' => $iniciativa['id'],
    'action' => 'eliminar',
    'content' => $iniciativa
));

$Db->delete(array(
    'table' => 'puntos',
    'conditions' => array(
        'iniciativas.id' => $iniciativa['id']
    )
));

$Db->delete(array(
    'table' => 'iniciativas',
    'limit' => 1,
    'conditions' => array(
        'id' => $iniciativa['id']
    )
));

$Vars->message(__('A iniciativa foi eliminada correctamente.'), 'ok');

redirect(path('ameazas'));