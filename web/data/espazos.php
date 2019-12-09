<?php
defined('ANS') or die();

$figuras = $Db->select(array(
    'table' => 'espazos_figuras',
    'sort' => 'nome ASC',
    'conditions' => array(
        'activo' => 1
    )
));

$tipos = $Db->select(array(
    'table' => 'espazos_tipos',
    'sort' => 'nome ASC',
    'conditions' => array(
        'activo' => 1
    )
));

$territorios = $Db->select(array(
    'table' => 'territorios',
    'sort' => 'nome ASC',
    'conditions' => array(
        'activo' => 1
    )
));

$conditions = array(
    'activo' => 1
);

if ($Vars->var['zona']) {
    $conditions['territorios.url'] = $Vars->var['zona'];
}

if ($Vars->var['tipo']) {
    $conditions['espazos_tipos.id'] = $Vars->var['tipo'];
}

if ($Vars->var['figura']) {
    $conditions['espazos_figuras.id'] = $Vars->var['figura'];
}

if (!empty($Vars->var['validado'])) {
    $conditions['validado'] = $Vars->int('validado');
}

$espazos = setRowsLanguage(array(
    'table' => 'espazos',
    'fields' => '*',
    'limit' => 6,
    'sort' => 'data DESC',
    'conditions' => $conditions,
    'pagination' => array(
        'page' => ($Vars->int('p') ?: 1),
        'map' => 10
    ),
    'add_tables' => array(
        'imaxe' => array(
            'table' => 'imaxes',
            'fields' => '*',
            'sort' => 'portada DESC',
            'limit' => 1,
            'conditions' => array(
                'activo' => 1
            )
        ),
        array(
            'table' => 'usuarios',
            'name' => 'autor',
            'fields' => '*',
            'limit' => 1
        ),
        array(
            'table' => 'comentarios',
            'fields' => 'id'
        ),
        array(
            'table' => 'espazos_figuras',
            'limit' => 1
        ),
        array(
            'table' => 'espazos_tipos',
            'limit' => 1
        ),
        array(
            'table' => 'territorios',
            'limit' => 1
        )
    )
));

$Html->meta('title', __('Espazos'));
