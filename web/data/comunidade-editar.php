<?php
defined('ANS') or die();

if (empty($user)) {
    $Vars->message(__('Sentímolo, pero este contido é só accesible para usuarios rexistrados.'), 'ko');
    referer(path(''));
}

include ($Data->file('acl-comunidade-editar.php'));

if ($Data->actions['comunidade-gardar'] === null) {
    $Vars->var['comunidade'] = $comunidade;
}

$Html->meta('title', $comunidade['nome'] ?: __('Alta de ficha'));
