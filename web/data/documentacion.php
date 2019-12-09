<?php
defined('ANS') or die();

$grupos = $Db->select(array(
    'table' => 'documentacion_grupos',
    'fields' => '*',
    'sort' => 'titulo ASC',
    'add_tables' => array(
        array(
            'table' => 'documentacion',
            'fields' => '*',
            'sort' => 'data DESC'
        )
    )
));

$Html->meta('title', __('Documentación didáctica'));
