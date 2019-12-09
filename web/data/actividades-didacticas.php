<?php
defined('ANS') or die();

$texto = $Db->select(array(
    'table' => 'textos',
    'fields' => '*',
    'limit' => 1,
    'conditions' => array(
        'url' => 'unidades-didacticas'
    )
));

$didacticas = $Db->select(array(
    'table' => 'didacticas',
    'fields' => '*',
    'sort' => 'data DESC',
    'conditions' => array(
        'activo' => 1
    )
));

$Html->meta('title', __('Actividades didácticas'));
