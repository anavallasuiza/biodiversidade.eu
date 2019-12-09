<?php
defined('ANS') or die();

if (empty($user)) {
    $Vars->message(__('Sentímolo, pero este contido é só accesible para usuarios rexistrados.'), 'ko');
    referer(path(''));
}

include ($Data->file('acl-nota.php'));

if ($Data->actions['nota-gardar'] === null) {
    $Vars->var['notas'] = $nota;
}

$Html->meta('title', $nota['titulo']);
