<?php
defined('ANS') or die();

if (empty($user)) {
    $Vars->message(__('Sentímolo, pero este contido é só accesible para usuarios rexistrados.'), 'ko');
    referer(path(''));
}

if ($Vars->var['url']) {
    include ($Data->file('acl-espazo.php'));
    $Data->execute('acl-action.php', array('action' => 'espazo-editar'));
} else {
    $Data->execute('acl-action.php', array('action' => 'espazo-crear'));
    $espazo = array();
}

if (MEU) {
    $Acl->setPermission('action', 'espazo-validar', false);
}
