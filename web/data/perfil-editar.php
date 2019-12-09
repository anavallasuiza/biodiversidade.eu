<?php
defined('ANS') or die();

if (empty($user)) {
    redirect(path(''));
}

if ($Data->actions['perfil-gardar'] === null) {
    $Vars->var['usuarios'] = $user;
    $Vars->var['usuarios']['nome'] = $user['nome']['title'];
}

$Html->meta('title', __('Edita o teu perfil'));
