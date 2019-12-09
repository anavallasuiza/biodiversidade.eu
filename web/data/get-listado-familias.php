<?php
defined('ANS') or die();

$conditions = array();

if ($Vars->var['orde']) {
	$conditions = array('ordes.id' => $Vars->var['orde']);
} else {
	$conditions = array('ordes.url' => $Vars->var['codigo']);
}

$catalogo = $Vars->var['catalogo'] ? true : false;

$familias = $Db->select(array(
	'table' => 'familias',
	'sort' => 'nome ASC',
	'conditions' => $conditions
));


if ($Vars->getExitMode('json')) {
	die(json_encode($familias));
}

$conAvistamentos = $Vars->var['avistamentos'];
$type = 'familias';
$url = path('get-listado-xeneros') . ($catalogo ? get('catalogo', 1) : '');

$items = array();

$avistamentos = array();
foreach($familias as $i => $familia) {

	$avistamentos = $Db->selectCount(array(
        'table' => 'avistamentos',
        'conditions' => array(
            'especies.familias.id' => $familia['id']
        )
    ));

	if (!$conAvistamentos || $avistamentos) {
		$items[$i] = $familia;
	    $items[$i]['avistamentos'] = $avistamentos;
	}
}