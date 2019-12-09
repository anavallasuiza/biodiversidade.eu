<?php
defined('ANS') or die();

if (empty($user)) {
    $Vars->message(__('Sentímolo, pero este contido é só accesible para usuarios rexistrados.'), 'ko');
    referer(path(''));
}

include ($Data->file('acl-didactica-editar.php'));

$Data->execute('acl-action.php', array('action' => 'didactica-eliminar'));

$Data->execute('actions|sub-backups.php', array(
    'table' => 'didacticas',
    'id' => $didactica['id'],
    'action' => 'eliminar',
    'content' => $didactica
));

$Db->delete(array(
    'table' => 'didacticas',
    'limit' => 1,
    'conditions' => array(
        'id' => $didactica['id']
    )
));

$Vars->message(__('A actividade didactica foi eliminada correctamente.'), 'ok');

redirect(path('actividades-didacticas'));