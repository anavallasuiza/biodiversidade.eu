<?php
defined('ANS') or die();

if (empty($user)) {
    $Vars->message(__('Sentímolo, pero este contido é só accesible para usuarios rexistrados.'), 'ko');
    referer(path(''));
}

if ($Vars->var['proxecto']) {
    include ($Data->file('acl-proxecto.php'));
    $Data->execute('acl-action.php', array('action' => 'proxecto-editar'));
} else {
    $Data->execute('acl-action.php', array('action' => 'proxecto-crear'));
    $proxecto = array();
}
