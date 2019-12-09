<?php
defined('ANS') or die();

if (empty($user)) {
    $Vars->message(__('Sentímolo, pero este contido é só accesible para usuarios rexistrados.'), 'ko');
    referer(path(''));
}

include ($Data->file('acl-nota.php'));

$Db->delete(array(
    'table' => 'puntos',
    'conditions' => array(
        'notas.id' => $nota['id']
    )
));

$Db->delete(array(
    'table' => 'notas',
    'limit' => 1,
    'conditions' => array(
        'id' => $nota['id']
    )
));

$Vars->message(__('A nota foi eliminada correctamente.'), 'ok');

redirect(path('perfil'));