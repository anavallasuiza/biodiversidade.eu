<?php
defined('ANS') or die();

$eventos = setRowsLanguage(array(
    'table' => 'eventos',
    'fields' => '*',
    'limit' => 3,
    'sort' => 'data ASC',
    'conditions' => array(
        'data >=' => date('Y-m-d'),
        'activo' => 1
    ),
    'add_tables' => array(
        array(
            'table' => 'comentarios',
            'fields' => 'id'
        )
    )
));

$novas = setRowsLanguage(array(
    'table' => 'novas',
    'fields' => '*',
    'limit' => 5,
    'sort' => 'data DESC',
    'conditions' => array(
        'data <=' => date('Y-m-d H:i:s'),
        'activo' => 1
    ),
    'pagination' => array(
        'page' => ($Vars->int('p') ?: 1),
        'map' => 10
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
        array(
            'table' => 'comentarios',
            'fields' => 'id',
            'conditions' => array(
                'activo' => 1
            )
        )
    )
));

$Html->meta('title', __('Novas'));
