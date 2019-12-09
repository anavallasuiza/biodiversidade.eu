<?php
defined('ANS') or die();

if (empty($user)) {
    $Vars->message(__('Sentímolo, pero este contido é só accesible para usuarios rexistrados.'), 'ko');
    referer(path(''));
}

include ($Data->file('acl-blog-editar.php'));

$Html->meta('title', __('Xestionar os usuarios editores de %s', $blog['titulo']));
