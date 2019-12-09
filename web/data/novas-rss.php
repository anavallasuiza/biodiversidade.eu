<?php
defined('ANS') or die();

$novas = setRowsLanguage(array(
    'table' => 'novas',
    'fields' => '*',
    'limit' => 10,
    'sort' => 'data DESC',
    'conditions' => array(
        'data <=' => date('Y-m-d H:i:s'),
        'activo' => 1
    )
));

$listado = array();

foreach ($novas as $nova) {
    $listado[] = array(
        'titulo' => $nova['titulo'],
        'link' => absolutePath('nova', $nova['url']),
        'texto' => $nova['texto'],
        'data_publicacion' => $nova['data']
    );
}

$Html->meta('title', __('Novas'));
