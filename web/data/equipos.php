<?php
defined('ANS') or die();

$conditions = array(
    'activo' => 1
);

$Vars->var['q'] = trim(strip_tags($Vars->var['q']));

if ((isBot() === false) && $Vars->var['q']) {
    $conditions['titulo LIKE'] = '%'.$Vars->var['q'].'%';
}

$equipos = $Db->select(array(
    'table' => 'equipos',
    'fields' => '*',
    'limit' => 5,
    'sort' => 'titulo ASC',
    'conditions' => $conditions,
    'pagination' => array(
        'page' => ($Vars->int('p') ?: 1),
        'map' => 10
    ),
    'add_tables' => array(
        array(
            'table' => 'usuarios',
            'fields' => array('nome', 'avatar')
        )
    )
));

$Html->meta('title', __('Equipos'));
