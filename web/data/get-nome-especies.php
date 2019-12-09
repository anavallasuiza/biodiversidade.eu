<?php 
defined('ANS') or die();

$q = '%' . strip_tags($Vars->var['term']) . '%';


$especies = $Db->select(array(
    'table' => 'especies',
    'fields_commands' => 'nome_cientifico as id, nome_cientifico as label, nome_cientifico as value, autor',
    'group' => array('nome_cientifico', 'autor'), 
    'conditions' => array(
        'nome_cientifico LIKE' => '%' . strip_tags($q) . '%'
    )
));


echo json_encode($especies);
die();