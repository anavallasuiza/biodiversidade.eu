<?php
defined('ANS') or die();

$avistamentos = $Db->select(array(
    'table' => 'avistamentos',
    'fields' => '*',
    'limit' => 10,
    'conditions' => array(
       'id_especies' => 0
    ),
    'add_tables' => array(
        array(
            'table' => 'usuarios',
            'name' => 'autor',
            'fields' => '*',
            'limit' => 1
        ),
        array(
            'table' => 'puntos'
        ),
        'imaxe' => array(
            'table' => 'imaxes',
            'sort' => array('portada DESC'),
            'limit' => 1,
            'conditions' => array(
                'activo' => 1
            ),
            'add_tables' => array(
                array(
                    'table' => 'imaxes_tipos',
                    'limit' => 1,
                    'conditions' => array(
                        'activo' => 1
                    )
                )
            )
        )
    )
));

$Html->meta('title', __('Catálogo'));
