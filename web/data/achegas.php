<?php
defined('ANS') or die();

$texto = $Db->select(array(
    'table' => 'textos',
    'fields' => '*',
    'limit' => 1,
    'conditions' => array(
        'url' => 'achegas',
        'activo' => 1
    )
));

$Html->meta('title', $texto['titulo']);
