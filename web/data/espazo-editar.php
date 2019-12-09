<?php
defined('ANS') or die();

if (empty($user)) {
    $Vars->message(__('Sentímolo, pero este contido é só accesible para usuarios rexistrados.'), 'ko');
    referer(path(''));
}

include ($Data->file('acl-espazo-editar.php'));

if ($espazo) {
	$imaxes = $Db->select(array(
	    'table' => 'imaxes',
	    'fields' => '*',
	    'sort' => array('portada DESC'),
	    'conditions' => array(
	        'espazos.id' => $espazo['id'],
	        'activo' => 1
	    )
	));
    
    $especies = $Db->select(array(
        'table' => 'especies',
        'fields_commands' => '`especies`.`nome` as `text`, `especies`.`url` as `id`',
        'sort' => 'nome ASC',
        'conditions' => array(
            'espazos.id' => $espazo['id'],
            'activo' => 1
        )
    ));

    $especies = preg_replace('/"/i', '\'', json_encode($especies));    
    $shapes = getPuntosFormas('espazo', $espazo, null, null, $espazo['puntos'], $espazo['poligonos'], $espazo['lineas']);
} else {
	$imaxes = $shapes = array();
}

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

$tiposPois = $Db->select(array(
	'table' => 'pois_tipos',
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

$tables = $Config->tables[getDatabaseConnection()];
$licenzas = $tables['imaxes']['licenza']['values'];

if ($Data->actions['espazo-gardar'] === null) {
    $Vars->var['espazo'] = $espazo;
    $Vars->var['espazos_tipos'] = $espazo['espazos_tipos']['id'];
    $Vars->var['espazos_figuras'] = $espazo['espazos_figuras']['id'];
    $Vars->var['territorios'] = $espazo['territorios']['id'];
}

$Html->meta('title', $espazo['titulo'] ?: __('Novo espazo'));
