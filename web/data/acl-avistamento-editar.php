<?php
defined('ANS') or die();

if (empty($user)) {
    $Vars->message(__('Sentímolo, pero este contido é só accesible para usuarios rexistrados.'), 'ko');
    referer(path(''));
}

if ($Vars->var['url']) {
    include ($Data->file('acl-avistamento.php'));
    $Data->execute('acl-action.php', array('action' => 'avistamento-editar'));
} else {
    $Data->execute('acl-action.php', array('action' => 'avistamento-crear'));
    $avistamento = array();
}

if (MEU) {
    $Acl->setPermission('action', 'avistamento-validar', false);
}
