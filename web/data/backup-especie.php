<?php
defined('ANS') or die();

$id = $Vars->var['id'];

$backup = $Db->select(array(
    'table' => 'backups',
    'limit' => 1,
    'conditions' => array(
        'id' => $id
    ),
    'add_tables' => array(
        'autor' => array(
            'table' => 'usuarios',
            'name' => 'autor',
            'limit' => 1
        ),
        'especie' => array(
            'table' => 'especies',
            'limit' => 1
        )
    )
));

if (!$backup) {
    $Vars->message(__('O backup especificado non existe'), 'ko');
    redirect(path('especie'));
}

$especie = unserialize(base64_decode($backup['content']));

// Cargar todas as imaxes para a especie
$imaxes = $especie['imaxes'];

