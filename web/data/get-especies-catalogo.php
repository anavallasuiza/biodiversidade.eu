<?php
defined('ANS') or die();

$page = ($Vars->int('p') ?: 1);

$query = array(
	'table' => 'especies',
	'fields' => '*',
	'sort' => 'url ASC',
	'limit' => 15,
    'conditions' => array(
        'activo' => 1
    ),
    'add_tables' => array(
        'imaxe' => array(
            'table' => 'imaxes',
            'fields' => '*',
            'sort' => 'portada DESC',
            'limit' => 1,
            'conditions' => array(
                'activo' => 1
            )
        ),
        'avistamentos' => array(
            'table' => 'avistamentos',
            'limit' => 1,
            'sort' => 'data_alta desc',
            'conditions' => array(
                'imaxes.id !=' => 0
            ),
            'add_tables' => array(
                array(
                    'table' => 'imaxes',
                    'fields' => '*',
                    'sort' => 'portada DESC',
                    'limit' => 1,
                    'conditions' => array(
                        'activo' => 1
                    )
                )
            )
        )
    ),
	'pagination' => array(
	    'map' => 10,
	    'page' => $page
	)
);


if ($Vars->var['busca']) {
	$q = '%'.trim(strip_tags($Vars->var['busca'])).'%';

	$query['conditions_or'] = array(
        'nome LIKE' => $q,
        'lsid_name LIKE' => $q,
        'especie LIKE' => $q,
        'autor LIKE' => $q
    );
}

if ($Vars->var['familias']) {
	$query['conditions']['familias.url'] = $Vars->var['familias'];
}

if ($Vars->var['xeneros']) {
	$query['conditions']['xeneros.url'] = $Vars->var['xeneros'];
}

if ($Vars->var['ameaza']) {
	$query['conditions']['nivel_ameaza'] = $Vars->var['ameaza'];	
}

$especies = $Db->select($query);
