<?php
defined('ANS') or die();

$conditions = array(
    'activo' => 1
);

if ($Vars->var['dificultade']) {
    $conditions['dificultade'] = $Vars->var['dificultade'];
}

switch ($Vars->var['distancia']) {
    case '<10':
        $conditions['distancia <='] = 10;
        break;
    case '10-20':
        $conditions['distancia >='] = 10;
        $conditions['distancia <='] = 20;
        break;
    case '>20':
        $conditions['distancia >='] = 20;
        break;
}

switch ($Vars->var['duracion']) {
    case '<2':
        $conditions['duracion <='] = 120;
        break;
    case '2-4':
        $conditions['duracion >='] = 120;
        $conditions['duracion <='] = 240;
        break;
    case '>4':
        $conditions['duracion >='] = 240;
        break;
}

if ($Vars->var['especie']) {
    $conditions['especies.url'] = $Vars->var['especie'];
}

$rotas = setRowsLanguage(array(
    'table' => 'rotas',
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
            'table' => 'comentarios',
            'fields' => 'id'
        ),
        array(
            'table' => 'territorios',
            'fields' => '*',
            'limit' => 1
        ),
        array(
            'table' => 'usuarios',
            'name' => 'validador',
            'fields' => '*',
            'limit' => 1
        ),
    )
));

$Html->meta('title', __('Rotas'));
