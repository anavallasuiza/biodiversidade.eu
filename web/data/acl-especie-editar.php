<?php
defined('ANS') or die();

if (empty($user)) {
    $Vars->message(__('Sentímolo, pero este contido é só accesible para usuarios rexistrados.'), 'ko');
    referer(path(''));
}

if ($Vars->var['url']) {
    include ($Data->file('acl-especie.php'));
    $Data->execute('acl-action.php', array('action' => 'especie-editar'));
} else {
    $Data->execute('acl-action.php', array('action' => 'especie-crear'));
    $especie = array();
}

if (MEU) {
    $Acl->setPermission('action', 'especie-validar', false);
}
