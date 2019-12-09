<?php
defined('ANS') or die();

if (empty($user)) {
    $Vars->message(__('Precisas estar logueado para poder realizar esta acción'), 'ko');
    return false;
} else if (empty($Vars->var['id'])) {
    $Vars->message(__('Parece que este contido non está dispoñible neste momento'), 'ko');
    return false;
}

$comentario = $Db->select(array(
    'table' => 'comentarios',
    'fields' => 'id',
    'limit' => 1,
    'conditions' => array(
        'id' => (integer)$Vars->var['id'],
        'activo' => 1
    ),
    'add_tables' => array(
        array(
            'table' => 'usuarios',
            'name' => 'autor',
            'fields' => 'id',
            'limit' => 1
        )
    )
));

if (empty($comentario)) {
    $Vars->message(__('Parece que este contido non está dispoñible neste momento'), 'ko');
    return false;
}

if (($user['id'] !== $comentario['usuarios_autor']['id']) && !$Acl->check('action', 'comentario-eliminar-all')) {
    $Vars->message(__('Non dispós dos permisos necesarios para poder realizar esta acción'), 'ko');
    return false;
}

$Data->execute('actions|sub-backups.php', array(
    'table' => 'comentarios',
    'id' => $comentario['id'],
    'action' => 'eliminar',
    'content' => $comentario
));

$Db->delete(array(
    'table' => 'comentarios',
    'limit' => 1,
    'conditions' => array(
        'id' => $comentario['id']
    )
));

$Vars->message(__('O comentario foi borrado correctamente'), 'ok');

redirect(path());
