<?php
defined('ANS') or die();

if (empty($user)) {
    $Vars->message(__('Sentímolo, pero este contido é só accesible para usuarios rexistrados.'), 'ko');
    referer(path(''));
}

if (empty($blog)) {
    $Vars->message(__('Sentímolo, pero parece que este contido xa non é accesible.'), 'ko');
    referer(path('proxectos'));
}

if ($Vars->var['url']) {
    include ($Data->file('acl-post.php'));
    $Data->execute('acl-action.php', array('action' => 'post-editar'));
} else {
    $Data->execute('acl-action.php', array('action' => 'post-crear'));
    $post = array();
}
