<?php
defined('ANS') or die();

$taxon = $Vars->var['taxon'];
$subtaxon = $Vars->var['subtaxon'];
$busca = $Vars->var['busca'];
$avistamentos = $Vars->var['avistamentos'];

if (empty($taxon) && empty($subtaxon) && strlen($busca) < 3) {
	if ($Vars->getExitMode('json')) {
		die(json_encode(array()));
	} else {
		return false;
	}
}

$conditions = array(
	'activo' => 1
);

if ($taxon) {
	$conditions['familias.url'] = $taxon;
}

if ($subtaxon) {
	$conditions['xeneros.url'] = $subtaxon;
}

if ($avistamentos) {
	$conditions['avistamentos.id >'] = 0;
}

if ($busca) {
    $q = '%'.trim(strip_tags($busca)).'%';

    $like = array(
    	'nome LIKE' => $q,
		'lsid_name LIKE' => $q,
		'especie LIKE' => $q,
		'autor LIKE' => $q
    );

	$conditions[] = $like;
}

$especies = $Db->select(array(
	'table' => 'especies',
	'fields' => '*',
	'sort' => 'url ASC',
	'group' => 'id',
	'conditions' => $conditions,
));

if ($Vars->getExitMode('json')) {
	die(json_encode($especies));
}
