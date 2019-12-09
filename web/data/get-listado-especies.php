<?php
defined('ANS') or die();

$conditions = array('xeneros.url' => $Vars->var['codigo']);

$especies = $Db->select(array(
	'table' => 'especies',
	'sort' => 'nome ASC',
	'conditions' => $conditions
));

if ($Vars->getExitMode('json')) {
	die(json_encode($ordes));
}

$conAvistamentos = $Vars->var['avistamentos'];
$items = array();

$avistamentos = array();
foreach($especies as $i => $especie) {

	$avistamentos = $Db->selectCount(array(
        'table' => 'avistamentos',
        'conditions' => array(
            'especies.id' => $especie['id']
        )
    ));

	if (!$conAvistamentos || $avistamentos) {
		$items[$i] = $especie;
	    $items[$i]['avistamentos'] = $avistamentos;
	}
}