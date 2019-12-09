<?php
defined('ANS') or die();

include ($Data->file('acl-ameaza-editar.php'));

$avistamento = null;

if ($ameaza) {
    $imaxes = $Db->select(array(
        'table' => 'imaxes',
        'fields' => '*',
        'conditions' => array(
            'ameazas.id' => $ameaza['id'],
            'activo' => 1
        )
    ));

    $especies = $Db->select(array(
        'table' => 'especies',
        'fields_commands' => '`especies`.`nome` as `text`, `especies`.`url` as `id`',
        'sort' => 'nome ASC',
        'conditions' => array(
            'ameazas.id' => $ameaza['id'],
            'activo' => 1
        )
    ));

    $shapes = getPuntosAmeaza($ameaza, $ameaza['puntos'], $ameaza['poligonos'], $ameaza['lineas']);
} else {
    $imaxes = $especies = $shapes = array();

    if ($Vars->var['avistamento']) {
        $avistamento = $Data->execute('acl-avistamento.php', array('codigoAvistamento' => $Vars->var['avistamento']));
    }
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
    'table' => 'ameazas_tipos',
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

if ($ameaza['id']) {
    $shapes = getPuntosAmeaza($ameaza, $ameaza['puntos'], $ameaza['poligonos'], $ameaza['lineas']);
}

if ($Data->actions['ameaza-gardar'] === null) {
    $Vars->var['ameazas'] = $ameaza;
    $Vars->var['territorio'] = $ameaza['territorios']['url'];
    $Vars->var['provincia'] = $ameaza['provincias']['nome']['url'];
    $Vars->var['concello'] = $ameaza['concellos']['nome']['url'];
    $Vars->var['puntos'] = $ameaza['puntos'];
    $Vars->var['ameazas_tipos'] = simpleArrayColumn($ameaza['ameazas_tipos'], 'id');
    $Vars->var['proxectos'] = simpleArrayColumn($ameaza['proxectos'], 'id');

    if (empty($ameaza) && $Vars->var['especie']) {
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

if ($avistamento) {
    if ($avistamento['especies']['nome']) {
        $titulo = $avistamento['especies']['nome'];
    } else {
        $titulo = $avistamento['posible'] ? __('Posible %s', $avistamento['posible']) : __('Sen identificar');
    }

    $Vars->var['ameazas'] = array(
        'titulo' => $titulo,
        'data' => date('d-m-Y', strtotime($avistamento['data_alta'])),
        'lugar' =>  $avistamento['nome_zona']
    );
    $Vars->var['territorio'] = $avistamento['territorios']['url'];
    $Vars->var['provincia'] = $avistamento['provincias']['nome']['url'];
    $Vars->var['concello'] = $avistamento['concellos']['nome']['url'];
}

$Html->meta('title', $ameaza['titulo'] ?: __('Nova ameaza'));
