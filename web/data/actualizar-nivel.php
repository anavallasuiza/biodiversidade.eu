<?php
defined('ANS') or die();

$id = $Vars->var['id'];

$usuario = $Db->select(array(
    'table' => 'usuarios',
    'fields' => '*',
    'limit' => 1,
    'conditions' => array(
        'id' => $id,
        'activo' => 1
    ),
    'add_tables' => array(
        array(
            'table' => 'roles',
            'fields' => '*',
            'conditions' => array(
                'enabled' => 1
            )
        )
    )
));

if (empty($usuario)) {
    $Vars->message(__('Sentímolo, pero parece que este contido xa non é accesible.'), 'ko');
    pre("erro");
}

$niveis = $Db->select(array(
    'table' => 'roles',
    'fields' => '*',
    'conditions' => array(
        'enabled' => 1,
        'code !=' => 'editor'
    )
));
