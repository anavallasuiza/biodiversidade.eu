<?php
defined('ANS') or die();

if (empty($user)) {
    $Vars->message(__('Sentímolo, pero este contido é só accesible para usuarios rexistrados.'), 'ko');
    referer(path(''));
}

include ($Data->file('acl-rota-editar.php'));

$Data->execute('acl-action.php', array('action' => 'rota-eliminar'));

$Data->execute('actions|sub-backups.php', array(
    'table' => 'rotas',
    'id' => $rota['id'],
    'action' => 'eliminar',
    'content' => $rota
));

$Db->delete(array(
    'table' => 'rotas',
    'limit' => 1,
    'conditions' => array(
        'id' => $rota['id']
    )
));

$Vars->message(__('A rota foi eliminada correctamente.'), 'ok');

redirect(path('rotas'));