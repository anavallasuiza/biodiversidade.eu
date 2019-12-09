<?php
defined('ANS') or die();

if (empty($user)) {
    $Vars->message(__('Sentímolo, pero este contido é só accesible para usuarios rexistrados.'), 'ko');
    referer(path(''));
}

include ($Data->file('acl-avistamento-editar.php'));

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

$ameazas = $Db->select(array(
    'table' => 'ameazas_tipos',
    'fields' => '*',
    'sort' => 'nome ASC',
    'conditions' => array(
        'activo' => 1
    )
));

$habitats = $Db->select(array(
    'table' => 'habitats_tipos',
    'fields' => '*',
    'sort' => 'nome ASC',
    'conditions' => array(
        'activo' => 1
    )
));

$codigoNotaCampo = $Vars->var['nota'];

if ($avistamento) {
    $imaxes = $Db->select(array(
        'table' => 'imaxes',
        'fields' => '*',
        'sort' => array('portada DESC'),
        'conditions' => array(
            'avistamentos.id' => $avistamento['id'],
            'activo' => 1
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

    $Vars->var['grupo'] = $avistamento['grupos']['url'];
} else {
    if ($Vars->var['especie']) {
        $Vars->var['grupo'] = $Db->select(array(
            'table' => 'grupos',
            'fields' => 'url',
            'limit' => 1,
            'conditions' => array(
                'especies.url' => $Vars->var['especie']
            )
        ));

        $Vars->var['grupo'] = $Vars->var['grupo']['url'];
    }

    if (empty($Vars->var['grupo'])) {
        if ($codigoNotaCampo) {
            redirect(path('editar-grupo', 'avistamento').'?nota='.$codigoNotaCampo);
        } else {
            redirect(path('editar-grupo', 'avistamento'));
        }
    }

    $imaxes = array();
}

$notaCampo = null;

if ($codigoNotaCampo) {
    $notaCampo = $Db->select(array(
        'table' => 'notas',
        'fields' => '*',
        'limit' => 1,
        'conditions' => array(
            'url' => $codigoNotaCampo,
            'usuarios.id' => $user['id']
        ),
        'add_tables' => array(
            array(
                'table' => 'puntos',
                'fields' => '*'
            )
        )
    ));
}

$Vars->var['grupo'] = $Vars->var['grupo'] ?: 'plantas';

$imaxes_tipos = $Db->select(array(
    'table' => 'imaxes_tipos',
    'fields' => '*',
    'sort' => 'nome ASC',
    'conditions' => array(
        'reinos.grupos.url' => $Vars->var['grupo'],
        'activo' => 1
    )
));

$acompanhantes = $Db->select(array(
    'table' => 'especies',
    'fields_commands' => '`especies`.`nome` as `text`, `especies`.`url` as `id`',
    'sort' => 'nome ASC',
    'conditions' => array(
        'avistamentos(acompanhantes).id' => $avistamento['id'],
        'activo' => 1
    )
));

$proxectos = $Db->select(array(
    'table' => 'proxectos',
    'sort' => 'titulo ASC',
    'conditions' => array(
        'usuarios.id' => $user['id'],
        'activo' => 1
    )
));

$puntosTipos = $Db->select(array(
    'table' => 'puntos_tipos',
    'conditions' => array(
        'numero' => array(1,2, 3, 4) // Centroides
    )
));

$referencias = $Db->select(array(
    'table' => 'referencias_tipos'
));

$tables = $Config->tables[getDatabaseConnection()];
$enum = array();

foreach ($tables['avistamentos'] as $field => $settings) {
    if (is_array($settings) && ($settings['format'] === 'enum')) {
        $enum[$field] = $settings['values'];
    }
}

$licenzas = $tables['imaxes']['licenza']['values'];

$datums = $Db->select(array(
    'table' => 'datums',
    'sort' => 'orde ASC',
    'condition' => array(
        'activo' => 1
    )
));

if ($Data->actions['avistamento-gardar'] === null) {
    $Vars->var['avistamentos'] = $avistamento;
    $Vars->var['territorio'] = $avistamento['territorios']['url'];
    $Vars->var['provincia'] = $avistamento['provincias']['nome']['url'];
    $Vars->var['concello'] = $avistamento['concellos']['nome']['url'];
    $Vars->var['puntos'] = $avistamento['puntos'];
    $Vars->var['habitats_tipos'] = $avistamento['habitats_tipos'];
    $Vars->var['ameazas_tipos_nivel1'] = str_replace('"', "'", json_encode(simpleArrayColumn($avistamento['ameazas_tipos_nivel1'], 'id')));
    $Vars->var['ameazas_tipos_nivel2'] = str_replace('"', "'", json_encode(simpleArrayColumn($avistamento['ameazas_tipos_nivel2'], 'id')));
    $Vars->var['referencias_tipos'] = str_replace('"', "'", json_encode(simpleArrayColumn($avistamento['referencias_tipos'], 'id')));
    $Vars->var['proxectos'] = str_replace('"', "'", json_encode(simpleArrayColumn($avistamento['proxectos'], 'id')));
    
    if ($avistamento) {
        $Vars->var['avistamentos']['data_observacion'] = date('d-m-Y H:i P', strtotime($avistamento['data_observacion']));
    } else {
        $Vars->var['avistamentos']['data_observacion'] = '';
    }

    if (empty($avistamento) && $Vars->var['especie']) {
        $Vars->var['especies'] = $Db->select(array(
            'table' => 'especies',
            'fields' => array('url', 'nome'),
            'limit' => 1,
            'conditions' => array(
                'url' => $Vars->var['especie']
            )
        ));
    } else {
        $Vars->var['especies'] = $avistamento['especies'];
    }
} else {
    $Vars->var['referencias_tipos'] = str_replace('"', "'", json_encode($Vars->var['referencias_tipos']));
}


$Html->meta('title', $avistamento['nome'] ?: __('Novo avistamento'));
