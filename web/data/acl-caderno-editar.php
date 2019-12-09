<?php
defined('ANS') or die();

if (empty($user)) {
    $Vars->message(__('Sentímolo, pero este contido é só accesible para usuarios rexistrados.'), 'ko');
    referer(path(''));
}

if (empty($proxecto)) {
    $Vars->message(__('Sentímolo, pero parece que este contido xa non é accesible.'), 'ko');
    referer(path('proxectos'));
}

if ($Vars->var['url']) {
    include ($Data->file('acl-caderno.php'));
    $Data->execute('acl-action.php', array('action' => 'caderno-editar'));
} else {
    $Data->execute('acl-action.php', array('action' => 'caderno-crear'));
    $caderno = array();
}
