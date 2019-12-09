<?php
defined('ANS') or die();

$concellos = $Db->select(array(
    'table' => 'concellos',
    'fields' => '*', 
    'sort' => array('nome ASC'),
    'conditions' => array(
    	'provincias.nome-url' => $Vars->var['codigo']
    )
));

if ($Vars->getExitMode('json')) {
	die(json_encode($concellos));
}
