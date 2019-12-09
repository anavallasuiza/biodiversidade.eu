<?php
defined('ANS') or die();

$eventos = setRowsLanguage(array(
    'table' => 'eventos',
    'fields' => '*',
    'limit' => 10,
    'sort' => 'data ASC',
    'conditions' => array(
        'data >=' => date('Y-m-d'),
        'activo' => 1
    )
));

$listado = array();

foreach ($eventos as $evento) {
    $listado[] = array(
        'titulo' => $evento['titulo'],
        'link' => absolutePath('evento', $evento['url']),
        'texto' => $evento['texto'],
        'data_publicacion' => $evento['data']
    );
}

$Html->meta('title', __('Eventos'));
