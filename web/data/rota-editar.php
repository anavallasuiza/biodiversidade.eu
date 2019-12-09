<?php
defined('ANS') or die();

if (empty($user)) {
    $Vars->message(__('Sentímolo, pero este contido é só accesible para usuarios rexistrados.'), 'ko');
    referer(path(''));
}

include ($Data->file('acl-rota-editar.php'));

if ($rota) {
    $imaxes = $Db->select(array(
        'table' => 'imaxes',
        'fields' => '*',
        'conditions' => array(
            'rotas.id' => $rota['id'],
            'activo' => 1
        )
    ));

    $especies = $Db->select(array(
        'table' => 'especies',
        'fields_commands' => '`especies`.`nome` as `text`, `especies`.`url` as `id`',
        'sort' => 'nome ASC',
        'conditions' => array(
            'rotas.id' => $rota['id'],
            'activo' => 1
        )
    ));

    $shapes = getPuntosFormas('rota', $rota, null, null, $rota['puntos'], $rota['poligonos'], $rota['lineas']);
} else {
    $imaxes = $especies = $shapes = array();
}

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

$tiposPois = $Db->select(array(
	'table' => 'pois_tipos',
	'sort' => 'nome ASC',
	'conditions' => array(
		'activo' => 1
	)
));


$tables = $Config->tables[getDatabaseConnection()];

$licenzas = $tables['imaxes']['licenza']['values'];
$dificultades = $tables['rotas']['dificultade']['values'];

if ($Data->actions['rota-gardar'] === null) {
    $Vars->var['rotas'] = $rota;
    $Vars->var['territorio'] = $rota['territorios']['url'];
    $Vars->var['provincia'] = $rota['provincias']['nome']['url'];
    $Vars->var['concello'] = $rota['concellos']['nome']['url'];
    $Vars->var['puntos'] = $rota['puntos'];
    $Vars->var['especies'] = $especies;
}

$Html->meta('title', $rota['titulo'] ?: __('Nova rota'));
