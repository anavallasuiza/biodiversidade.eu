<?php
defined('ANS') or die();

if (empty($user)) {
    $Vars->message(__('Sentímolo, pero este contido é só accesible para usuarios rexistrados.'), 'ko');
    referer(path(''));
}

include ($Data->file('acl-equipo-editar.php'));

$solicitudes = $Db->select(array(
    'table' => 'usuarios',
    'fields' => array('nome', 'avatar'),
    'conditions' => array(
        'equipos(solicitudes).id' => $equipo['id']
    )
));

$Html->meta('title', __('Xestionar os usuarios editores de %s', $equipo['titulo']));
