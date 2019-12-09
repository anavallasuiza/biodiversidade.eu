<?php
defined('ANS') or die();

$comunidade = arrayColumns($Db->select(array(
    'table' => 'comunidade',
    'fields' => '*',
    'sort' => 'nome ASC',
    'conditions' => array(
        'activo' => 1
    )
)), 3);

$Html->meta('title', __('A Comunidade'));
