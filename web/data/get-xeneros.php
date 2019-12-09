<?php
defined('ANS') or die();

$xeneros = $Db->select(array(
	'table' => 'xeneros',
    'fields_commands' => 'id, url, nome as text',
	'sort' => 'nome ASC',
	'conditions' => array(
        'nome LIKE' => '%' . $Vars->var['q'] . '%'
    ),
    'add_tables' => array(
        array(
            'table' => 'grupos',
            'limit' => 1,
            'conditions' => array(
                'activo' => 1
            )
        ),
        array(
            'table' => 'clases',
            'limit' => 1
        ),
        array(
            'table' => 'ordes',
            'limit' => 1
        ),
        array(
            'table' => 'familias',
            'limit' => 1
        )
    )
));

die(json_encode($xeneros));