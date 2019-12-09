<?php
defined('ANS') or die();

if (empty($user)) {
    $Vars->message(__('Sentímolo, pero este contido é só accesible para usuarios rexistrados.'), 'ko');
    referer(path(''));
}

include ($Data->file('acl-proxecto-editar.php'));

$solicitudes = $Db->select(array(
    'table' => 'usuarios',
    'fields' => '*',
    'conditions' => array(
        'proxectos(solicitude).id' => $proxecto['id']
    )
));

$Html->meta('title', __('Xestionar os usuarios editores de %s', $proxecto['titulo']));
