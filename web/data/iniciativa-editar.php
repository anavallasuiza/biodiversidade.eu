<?php
defined('ANS') or die();

include ($Data->file('acl-iniciativa-editar.php'));

if ($iniciativa) {
    $imaxes = $Db->select(array(
        'table' => 'imaxes',
        'fields' => '*',
        'conditions' => array(
            'iniciativas.id' => $iniciativa['id'],
            'activo' => 1
        )
    ));

    $especies = $Db->select(array(
        'table' => 'especies',
        'fields_commands' => '`especies`.`nome` as `text`, `especies`.`url` as `id`',
        'sort' => 'nome ASC',
        'conditions' => array(
            'iniciativas.id' => $iniciativa['id'],
            'activo' => 1
        )
    ));

    $shapes = getPuntosAmeaza($iniciativa, $iniciativa['puntos'], $iniciativa['poligonos'], $iniciativa['lineas']);
} else {
    $imaxes = $especies = $shapes = array();
}

$proxectos = $Db->select(array(
    'table' => 'proxectos',
    'sort' => 'titulo ASC',
    'conditions' => array(
        'usuarios.id' => $user['id'],
        'activo' => 1
    )
));

$paises = $Db->select(array(
    'table' => 'paises',
    'fields' => '*',
    'sort' => 'nome ASC'
));

$territorios = $Db->select(array(
    'table' => 'territorios',
    'fields' => '*',
    'sort' => array('orde ASC', 'nome ASC')
));

$tipos = $Db->select(array(
    'table' => 'iniciativas_tipos',
    'fields' => '*',
    'sort' => 'nome ASC',
    'conditions' => array(
        'activo' => 1
    )
));

$taxons = $Db->select(array(
    'table' => 'familias',
    'fields' => '*',
    'sort' => 'url ASC',
    'add_tables' => array(
        array(
            'table' => 'xeneros',
            'fields' => '*',
            'sort' => 'url ASC'
        )
    )
));

$tables = $Config->tables[getDatabaseConnection()];
$licenzas = $tables['imaxes']['licenza']['values'];

$shapes = array();

if ($iniciativa['id']) {
    $shapes = getPuntosAmeaza($iniciativa, $iniciativa['puntos'], $iniciativa['poligonos'], $iniciativa['lineas']);
}

if ($Data->actions['iniciativa-gardar'] === null) {
    $Vars->var['iniciativas'] = $iniciativa;
    $Vars->var['territorio'] = $iniciativa['territorios']['url'];
    $Vars->var['provincia'] = $iniciativa['provincias']['nome']['url'];
    $Vars->var['concello'] = $iniciativa['concellos']['nome']['url'];
    $Vars->var['puntos'] = $iniciativa['puntos'];
    $Vars->var['iniciativas_tipos'] = simpleArrayColumn($iniciativa['iniciativas_tipos'], 'id');
    $Vars->var['proxectos'] = simpleArrayColumn($iniciativa['proxectos'], 'id');

    if (empty($iniciativa) && $Vars->var['especie']) {
        $Vars->var['especies'] = $Db->select(array(
            'table' => 'especies',
            'fields_commands' => '`especies`.`nome` as `text`, `especies`.`url` as `id`',
            'sort' => 'nome ASC',
            'conditions' => array(
                'url' => $Vars->var['especie']
            )
        ));
    } else {
        $Vars->var['especies'] = $especies;
    }
} else if ($Vars->var['especies']['url']) {
    $Vars->var['especies'] = $Db->select(array(
        'table' => 'especies',
        'fields_commands' => '`especies`.`nome` as `text`, `especies`.`url` as `id`',
        'sort' => 'nome ASC',
        'conditions' => array(
            'url' => explode(',', $Vars->var['especies']['url'])
        )
    ));
} else {
    $Vars->var['especies'] = array();
}

$Html->meta('title', $iniciativa['titulo'] ?: __('Nova iniciativa'));
