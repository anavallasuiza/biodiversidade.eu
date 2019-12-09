<?php 
defined('ANS') or die();

$q = strip_tags($Vars->var['q']);

(strlen($q) > 2) or die(json_encode(array()));

$select = $Db->select(array(
    'table' => 'iniciativas',
    'fields' => 'titulo',
    'fields_commands' => '`titulo-'.LANGUAGE.'` as text, `url` as id',
    'sort' => 'titulo ASC',
    'conditions' => array(
        'titulo LIKE' => ('%'.str_replace(' ', '%', $q).'%'),
        'activo' => 1
    )
));

die(json_encode($select));