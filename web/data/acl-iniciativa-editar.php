<?php
defined('ANS') or die();

if (empty($user)) {
    $Vars->message(__('Sentímolo, pero este contido é só accesible para usuarios rexistrados.'), 'ko');
    referer(path(''));
}

if ($Vars->var['url']) {
    include ($Data->file('acl-iniciativa.php'));
    $Data->execute('acl-action.php', array('action' => 'iniciativa-editar'));
} else {
    $Data->execute('acl-action.php', array('action' => 'iniciativa-crear'));
    $iniciativa = array();
}

if (MEU) {
    $Acl->setPermission('action', 'iniciativa-validar', false);
}
