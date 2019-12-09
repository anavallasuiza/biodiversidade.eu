<?php
defined('ANS') or die();

if (empty($user)) {
    $Vars->message(__('Sentímolo, pero este contido é só accesible para usuarios rexistrados.'), 'ko');
    referer(path(''));
}

include ($Data->file('acl-ameaza-editar.php'));

$Data->execute('acl-action.php', array('action' => 'ameaza-eliminar'));

$Data->execute('actions|sub-backups.php', array(
    'table' => 'ameazas',
    'id' => $ameaza['id'],
    'action' => 'eliminar',
    'content' => $ameaza
));

$Db->delete(array(
    'table' => 'puntos',
    'conditions' => array(
        'ameazas.id' => $ameaza['id']
    )
));

$Db->delete(array(
    'table' => 'ameazas',
    'limit' => 1,
    'conditions' => array(
        'id' => $ameaza['id']
    )
));

$Vars->message(__('A ameaza foi eliminada correctamente.'), 'ok');

redirect(path('ameazas'));