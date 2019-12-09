<?php
defined('ANS') or die();

$url = $Vars->var['url'] ?: $user['nome']['url'];

$usuario = $Db->select(array(
    'table' => 'usuarios',
    'fields' => '*',
    'limit' => 1,
    'conditions' => array(
        'nome-url' => $url,
        'activo' => 1
    )
));

if (empty($usuario)) {
    $Vars->message(__('Sentímolo, pero parece que este contido xa non é accesible.'), 'ko');
    referer(path('perfil'));
}

if (!$user) {
    $Vars->message(__('Para poder enviar unha mensaxe a un usuario debes estar rexistrado.'), 'ko');
    referer(path('perfil', $url));
}

$Html->meta('title', $usuario['nome']['title']);
