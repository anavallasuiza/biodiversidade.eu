<?php
defined('ANS') or die();

$conditions = array();

if ($Vars->var['familia']) {
	$conditions = array('familias.id' => $Vars->var['familia']);
} else if ($Vars->var['codigo']) {
	$conditions = array('familias.url' => $Vars->var['codigo']);
} else if ($Vars->var['q']) {
    $conditions = array('nome LIKE' => '%' . $Vars->var['q'] . '%');
}

$catalogo = $Vars->var['catalogo'] ? true : false;

$xeneros = $Db->select(array(
	'table' => 'xeneros',
	'sort' => 'nome ASC',
	'conditions' => $conditions
));


if ($Vars->getExitMode('json')) {
	die(json_encode($xeneros));
}

$conAvistamentos = $Vars->var['avistamentos'];
$type = 'xeneros';

if (!$catalogo) {
	$url = path('get-listado-especies');
	$ulClass = 'especies-menu-selector';
}

$items = array();

$avistamentos = array();
foreach($xeneros as $i => $xenero) {

	$avistamentos = $Db->selectCount(array(
        'table' => 'avistamentos',
        'conditions' => array(
            'especies.xeneros.id' => $xenero['id']
        )
    ));

	if (!$conAvistamentos || $avistamentos) {
		$items[$i] = $xenero;
	    $items[$i]['avistamentos'] = $avistamentos;
	}
}