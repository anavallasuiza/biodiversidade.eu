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


$paises = $Db->select(array(
    'table' => 'paises',
    'fields' => '*',
    'sort' => 'nome ASC'
));

$territorios = $Db->select(array(
    'table' => 'territorios',
    'fields' => '*',
    'sort' => 'nome ASC',
    'conditions' => array(
        'activo' => 1
    )
));

$proteccions_tipos = $Db->select(array(
    'table' => 'proteccions_tipos',
    'sort' => 'nome ASC',
    'conditions' => array(
        'activo' => 1
    )
));

$limit = 6;
$conditions = array(
    'activo' => 1
);

if ($Vars->var['pais']) {
    $limit = 50;
    $conditions['paises.id'] = $Vars->int('pais');
}

if ($Vars->var['provincia']) {
    $limit = 50;
    $conditions['provincias.id'] = $Vars->int('provincia');
}

if ($Vars->var['concello']) {
    $limit = 100;
    $conditions['concellos.id'] = $Vars->int('concello');
}

if ($Vars->var['nivel_ameaza']) {
    $limit = 100;
    $conditions['nivel_ameaza'] = $Vars->int('nivel_ameaza');
}

if (strlen($Vars->var['validada'])) {
    $limit = 100;
    $conditions['validado'] = $Vars->int('validada');
}

if ($Vars->var['especie'] || $Vars->var['especies']) {

    $codigoEspecie = $Vars->var['especie'] ?: $Vars->var['especies'];

    $especies = $Db->select(array(
        'table' => 'especies',
        'conditions' => array(
            'url' => $codigoEspecie
        )
    ));
}

$bodyClass = 'compact-header';

$Html->meta('title', __('Catálogo'));
