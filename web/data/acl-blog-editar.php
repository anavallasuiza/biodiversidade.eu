<?php
defined('ANS') or die();

if (empty($user)) {
    $Vars->message(__('Sentímolo, pero este contido é só accesible para usuarios rexistrados.'), 'ko');
    referer(path(''));
}

if ($Vars->var['blog']) {
    include ($Data->file('acl-blog.php'));
    $Data->execute('acl-action.php', array('action' => 'blog-editar'));
} else {
    $Data->execute('acl-action.php', array('action' => 'blog-crear'));
    $blog = array();
}
