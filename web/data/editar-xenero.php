<?php
defined('ANS') or die();

if (!in_array('editor', arrayKeyValues($user['roles'], 'code'))) {
    $Vars->message(__('Sentímolo, pero non tes permisos para editar este contido.'), 'ko');
    referer(path(''));
}

$grupos = $Db->select(array(
    'table' => 'grupos',
    'fields' => '*',
    'sort' => 'nome ASC',
    'conditions' => array(
        'activo' => 1
    )
));
