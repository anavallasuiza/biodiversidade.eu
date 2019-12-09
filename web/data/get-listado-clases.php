<?php
defined('ANS') or die();

$conditions = array();

if ($Vars->var['grupo']) {
	$conditions = array('grupos.id' => $Vars->var['grupo']);
} else {
	$conditions = array('grupos.url' => $Vars->var['codigo']);
}

$catalogo = $Vars->var['catalogo'] ? true : false;

$clases = $Db->select(array(
	'table' => 'clases',
	'sort' => 'nome ASC',
	'conditions' => $conditions
));

if ($Vars->getExitMode('json')) {
	die(json_encode($clases));
}

$conAvistamentos = $Vars->var['avistamentos'];
$type = 'clases';
$url = path('get-listado-ordes') . ($catalogo ? get('catalogo', 1) : '');

$items = array();

$avistamentos = array();
foreach($clases as $i => $clase) {

	$avistamentos = $Db->selectCount(array(
        'table' => 'avistamentos',
        'conditions' => array(
            'especies.clases.id' => $clase['id']
        )
    ));

	if (!$conAvistamentos || $avistamentos) {
		$items[$i] = $clase;
	    $items[$i]['avistamentos'] = $avistamentos;
	}
}