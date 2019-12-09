<?php
defined('ANS') or die();

$conditions = array();

if ($Vars->var['clase']) {
	$conditions = array('clases.id' => $Vars->var['clase']);
} else {
	$conditions = array('clases.url' => $Vars->var['codigo']);
}

$catalogo = $Vars->var['catalogo'] ? true : false;

$ordes = $Db->select(array(
	'table' => 'ordes',
	'sort' => 'nome ASC',
	'conditions' => $conditions
));


if ($Vars->getExitMode('json')) {
	die(json_encode($ordes));
}

$conAvistamentos = $Vars->var['avistamentos'];
$type = 'ordes';
$url = path('get-listado-familias') . ($catalogo ? get('catalogo', 1) : '');

$items = array();

$avistamentos = array();
foreach($ordes as $i => $orde) {

	$avistamentos = $Db->selectCount(array(
        'table' => 'avistamentos',
        'conditions' => array(
            'especies.ordes.id' => $orde['id']
        )
    ));

	if (!$conAvistamentos || $avistamentos) {
		$items[$i] = $orde;
	    $items[$i]['avistamentos'] = $avistamentos;
	}
}