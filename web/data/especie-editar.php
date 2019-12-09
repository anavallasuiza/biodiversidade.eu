<?php
defined('ANS') or die();

if (empty($user)) {
    $Vars->message(__('Sentímolo, pero este contido é só accesible para usuarios rexistrados.'), 'ko');
    referer(path(''));
}

include ($Data->file('acl-especie-editar.php'));

$grupos = $Db->select(array(
    'table' => 'grupos',
    'fields' => '*',
    'sort' => 'nome ASC',
    'conditions' => array(
        'activo' => 1
    )
));

$xeneros = $Db->select(array(
    'table' => 'xeneros',
    'fields' => '*',
    'sort' => 'nome ASC'
));

if ($Data->actions['especies-gardar'] === null) {
    $Vars->var['especies'] = $especie;
}

$Html->meta('title', $especie['nome'] ?: __('Nova especie'));
