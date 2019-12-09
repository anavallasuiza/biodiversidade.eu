<?php
defined('ANS') or die();

$provincias = $Db->select(array(
    'table' => 'provincias',
    'fields' => '*',
    'sort' => array('nome ASC'),
    'conditions' => array(
    	'territorios.url' => $Vars->var['codigo']
    )
));

if ($Vars->getExitMode('json')) {
	die(json_encode($provincias));
}
