<?php
defined('ANS') or die();

$editores = $Db->select(array(
    'table' => 'usuarios',
    'sort' => 'nome_completo desc',
    'conditions' => array(
        'roles.code' => 'editor'
    )
));

$Html->meta('title', __('Editores da web'));