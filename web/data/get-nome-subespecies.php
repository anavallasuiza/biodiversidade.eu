<?php 
defined('ANS') or die();

$especies = $Db->select(array(
    'table' => 'especies',
    'fields_commands' => 'subespecie as id, subespecie as label, subespecie as value, subespecie_autor as autor',
    'group' => array('subespecie', 'subespecie_autor'), 
    'conditions' => array(
        'subespecie LIKE' => ('%'.strip_tags($Vars->var['term']).'%')
    )
));

die(json_encode($especies));
