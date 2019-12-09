<?php
defined('ANS') or die();

$conditions = array();

if ($Vars->var['grupo']) {
	$conditions = array('grupos.id' => $Vars->var['grupo']);
} else {
	$conditions = array('grupos.url' => $Vars->var['codigo']);
}

$catalogo = $Vars->var['catalogo'] ? true : false;

$reinos = $Db->select(array(
	'table' => 'reinos',
	'sort' => 'nome ASC',
	'conditions' => $conditions
));

if ($Vars->getExitMode('json')) {
	die(json_encode($reinos));
}

$conAvistamentos = $Vars->var['avistamentos'];
$type = 'reinos';
$url = path('get-listado-clases') . ($catalogo ? get('catalogo', 1) : '');

$items = array();

$avistamentos = array();
foreach($reinos as $i => $reino) {

	$avistamentos = $Db->selectCount(array(
        'table' => 'avistamentos',
        'conditions' => array(
            'especies.reinos.id' => $reino['id']
        )
    ));

	if (!$conAvistamentos || $avistamentos) {
		$items[$i] = $reino;
	    $items[$i]['avistamentos'] = $avistamentos;
	}
}