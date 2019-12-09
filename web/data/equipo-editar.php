<?php
defined('ANS') or die();

if (empty($user)) {
    $Vars->message(__('Sentímolo, pero este contido é só accesible para usuarios rexistrados.'), 'ko');
    referer(path(''));
}

include ($Data->file('acl-equipo-editar.php'));

if ($Data->actions['equipo-gardar'] === null) {
    $Vars->var['equipos'] = $equipo;
}

$Html->meta('title', $equipo['titulo'] ?: __('Alta de equipo'));
