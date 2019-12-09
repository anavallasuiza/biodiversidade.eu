<?php
defined('ANS') or die();

$grupos = $Db->select(array(
    'table' => 'grupos',
    'fields' => '*',
    'group' => 'id',
    'sort' => 'url ASC',
    'conditions' => array(
        'activo' => 1
    )
));

$avistamentos = array();

foreach($grupos as &$grupo) {
    $grupo['avistamentos'] = $Db->selectCount(array(
        'table' => 'avistamentos',
        'limit' => 1,
        'conditions' => array(
            'especies.grupos.id' => $grupo['id']
        )
    ));
}

unset($grupo);

$Html->meta('title', __('Catálogo'));
