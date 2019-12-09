<?php
defined('ANS') or die();

if (empty($user)) {
    $Vars->message(__('Sentímolo, pero este contido é só accesible para usuarios rexistrados.'), 'ko');
    referer(path(''));
}

if ($Vars->var['url']) {
    include ($Data->file('acl-rota.php'));
    $Data->execute('acl-action.php', array('action' => 'rota-editar'));
} else {
    $Data->execute('acl-action.php', array('action' => 'rota-crear'));
    $rota = array();
}

if (MEU) {
    $Acl->setPermission('action', 'rota-validar', false);
}
