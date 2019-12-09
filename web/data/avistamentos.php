<?php
defined('ANS') or die();

$grupos = $Db->select(array(
    'table' => 'grupos',
    'fields' => '*',
    'sort' => 'nome ASC',
    'conditions' => array(
        'activo' => 1
    )
));

$ameazas = $Db->select(array(
    'table' => 'ameazas_tipos',
    'fields' => '*',
    'sort' => 'nome ASC',
    'conditions' => array(
        'activo' => 1,
        'avistamentos(nivel1).id >' => 0
    )
));

$habitats = $Db->select(array(
    'table' => 'habitats_tipos',
    'fields' => '*',
    'sort' => 'nome ASC',
    'conditions' => array(
        'activo' => 1,
        'avistamentos.id >' => 0
    )
));

$tables = $Config->tables[getDatabaseConnection()];
$enum = array();

foreach ($tables['avistamentos'] as $field => $settings) {
    if (is_array($settings) && ($settings['format'] === 'enum')) {
        $enum[$field] = $settings['values'];
    }
}

$observadores = $Db->select(array(
    'table' => 'usuarios',
    'fields' => 'nome',
    'sort' => 'nome ASC',
    'group' => 'id',
    'conditions' => array(
        'activo' => 1,
        'avistamentos(autor).id >' => 0
    )
));

$anos = $Db->select(array(
    'table' => 'avistamentos',
    'fields_commands' => 'DATE_FORMAT(data_observacion, "%Y") AS ano',
    'group_commands' => 'ano',
    'sort_commands' => 'ano ASC',
    'conditions' => array(
        'data_observacion NOT LIKE' => '%0000%',
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

$conditions = array(
    'activo' => 1
);

if ($Vars->var['grupo']) {
    $conditions['especies.grupos.id'] = (integer)$Vars->var['grupo'];
}

if ($Vars->var['habitat']) {
    $conditions['habitats_tipos.id'] = (integer)$Vars->var['habitat'];
}

if ($Vars->var['ameaza']) {
    $conditions['ameazas_tipos(nivel1).id'] = (integer)$Vars->var['ameaza'];
}

if ($Vars->var['fonte']) {
    $conditions['tipo_referencia'] = $Vars->var['fonte'];
}

if ($Vars->var['validada']) {
    $conditions['validado'] = '1';
}

if ($Vars->var['ano']) {
    $conditions['data_observacion LIKE'] = intval($Vars->var['ano']).'%';
}

if ($Vars->var['observador']) {
    $conditions['usuarios(autor).id'] = (int)$Vars->var['observador'];
}

if ($Vars->var['proteccion']) {
    $conditions['especies.proteccions_tipos.id'] = $Vars->var['proteccion'];
}

$avistamentos = $Db->select(array(
    'table' => 'avistamentos',
    'fields' => '*',
    'limit' => 6,
    'sort' => 'data_alta DESC',
    'conditions' => $conditions,
    'pagination' => array(
        'page' => ($Vars->int('p') ?: 1),
        'map' => 10
    ),
    'add_tables' => array(
        array(
            'table' => 'usuarios',
            'name' => 'autor',
            'fields' => '*',
            'limit' => 1
        ),
        array(
            'table' => 'usuarios',
            'name' => 'validador',
            'fields' => '*',
            'limit' => 1
        ),
        array(
            'table' => 'especies',
            'fields' => '*',
            'limit' => 1,
            'add_tables' => array(
                array(
                    'table' => 'reinos',
                    'limit' => 1,
                ),
                array(
                    'table' => 'grupos',
                    'limit' => 1,
                ),
                array(
                    'table' => 'xeneros',
                    'limit' => 1,
                ),
                array(
                    'table' => 'familias',
                    'limit' => 1,
                ),
                array(
                    'table' => 'ordes',
                    'limit' => 1,
                ),
                array(
                    'table' => 'clases',
                    'limit' => 1,
                )
            )
        ),
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
            'table' => 'puntos'
        )
    )
));

$Html->meta('title', __('Avistamentos'));
