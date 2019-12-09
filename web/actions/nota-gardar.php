<?php
defined('ANS') or die();

if (empty($user)) {
    return false;
}

include ($Data->file('acl-nota.php'));

$id_nota = $Db->update(array(
    'table' => 'notas',
    'data' => array(
        'titulo' => $Vars->var['notas']['titulo'],
        'texto' => $Vars->var['notas']['texto']
    ),
    'limit' => 1,
    'conditions' => array(
        'id' => $nota['id']
    )
));

if (empty($id_nota)) {
    $Vars->message($Errors->getList(), 'ko');
    return false;
}

$Vars->message(__('A nota foi actualizada correctamente'), 'ok');

redirect(path('nota', $nota['url']));
