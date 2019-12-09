<?php
defined('ANS') or die();

$nota = $Db->select(array(
    'table' => 'notas',
    'fields' => '*',
    'limit' => 1,
    'conditions' => array(
        'url' => $Vars->var['url'],
        'usuarios.id' => $user['id']
    ),
    'add_tables' => array(
        array(
            'table' => 'puntos',
            'fields' => '*'
        )
    )
));

if (empty($nota)) {
    $Vars->message(__('Sentímolo, pero parece que este contido xa non é accesible.'), 'ko');
    referer(path('perfil'));
}
