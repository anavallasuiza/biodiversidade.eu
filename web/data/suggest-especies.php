<?php
defined('ANS') or die();

if (empty($Vars->var['q'])) {
	if ($Vars->getExitMode('json')) {
		die(json_encode(array()));
	} else {
		return false;
	}
}

$conditions = array(
    'nome LIKE' => ('%'.$Vars->var['q'].'%')
);

if ($Vars->var['reino']) {
    $conditions['reinos.url'] = $Vars->var['reino'];
}

if ($Vars->var['grupo']) {
    $conditions['grupos.url'] = $Vars->var['grupo'];
}

$especies = $Db->select(array(
    'table' => 'especies',
    'fields_commands' => 'especies.url AS id, especies.nome AS text',
    'sort' => 'url ASC',
    'limit' => 20,
    'conditions' => $conditions
));

if ($Vars->getExitMode('json')) {
	die(json_encode($especies));
}
