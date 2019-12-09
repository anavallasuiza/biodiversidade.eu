<?php
defined('ANS') or die();

$texto = $Vars->var['texto'];
$especies = $Vars->var['especies'];
$avistamentos = $Vars->var['avistamentos'];

$conditionsFamilias = array(
	array(
    	'nome LIKE' => '%' . $texto . '%',
    	'xeneros.nome LIKE' => '%' . $texto . '%'
    )
);

$conditionsXeneros = array(
	array(
    	'nome LIKE' => '%' . $texto . '%',
    	'familias.nome LIKE' => '%' . $texto . '%'
    )
);

if ($avistamentos) {
	$conditionsFamilias = array_merge($conditionsFamilias, array(
		'xeneros.especies.avistamentos.id >' => 0
	));

	$conditionsXeneros = array_merge($conditionsXeneros, array(
		'especies.avistamentos.id >' => 0
	));
}

$taxons = $Db->select(array(
    'table' => 'familias',
    'fields' => '*',
    'sort' => 'url ASC',
    'group' => 'familias.id',
    'conditions' => $conditionsFamilias,
    'add_tables' => array(
        array(
            'table' => 'xeneros',
            'fields' => '*',
            'sort' => 'url ASC',
            'group' => 'id',
            'conditions' => $conditionsXeneros
        )
    )
));
