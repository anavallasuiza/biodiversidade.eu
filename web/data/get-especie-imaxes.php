<?php
defined('ANS') or die();

$codigo = $Vars->var['codigo'];

$hide = 'templates|img/logo-imaxe.png';
$editable = true;

$imaxesEspecie = $Db->select(array(
    'table' => 'imaxes',
    'sort' => array('portada DESC'),
    'conditions' => array(
        'activo' => 1,
        'especies.url' => $codigo
    ),
    'add_tables' => array(
        array(
            'table' => 'imaxes_tipos',
            'fields' => '*',
            'limit' => 1,
            'conditions' => array(
                'activo' => 1
            )
        )
    )
));

// As que se poden editar
$editables = $imaxesEspecie;

$imaxesAvistamentos = $Db->select(array(
    'table' => 'imaxes',
    'sort' => array('portada DESC'),
    'conditions' => array(
        'activo' => 1,
        'avistamentos.especies.url' => $codigo
    ),
    'add_tables' => array(
        array(
            'table' => 'imaxes_tipos',
            'fields' => '*',
            'limit' => 1,
            'conditions' => array(
                'activo' => 1
            )
        ),
        array(
            'table' => 'avistamentos',
            'fields' => '*',
            'limit' => 1,
            'sort' => 'data_alta DESC',
            'conditions' => array(
                'especies.id' => $especie['id'],
                'activo' => 1
            )
        )
    )
));

$images = $imaxesEspecie;
    
// Imaxes de avistamentos
foreach ($imaxesAvistamentos as $imaxe) {
    $imaxe['related'] = 'avistamento';
    $imaxe['related_url'] = $imaxe['avistamentos']['url'];
    $images[] = $imaxe;
}

$tables = $Config->tables[getDatabaseConnection()];
$licenzas = $tables['imaxes']['licenza']['values'];

$imaxes_tipos = $Db->select(array(
    'table' => 'imaxes_tipos',
    'fields' => '*',
    'sort' => 'nome ASC',
    'conditions' => array(
        'reinos.especies.id' => $especie['id'],
        'activo' => 1
    )
));
