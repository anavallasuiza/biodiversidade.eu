<?php
defined('ANS') or die();

$espazos = setRowsLanguage(array(
    'table' => 'espazos',
    'fields' => '*',
    'limit' => 10,
    'sort' => 'data DESC',
    'conditions' => array(
        'data <=' => date('Y-m-d H:i:s'),
        'activo' => 1
    )
));

$listado = array();

foreach ($espazos as $espazo) {
    $listado[] = array(
        'titulo' => $espazo['titulo'],
        'link' => absolutePath('espazo', $espazo['url']),
        'texto' => $espazo['texto'],
        'data_publicacion' => $espazo['data']
    );
}

$Html->meta('title', __('Espazos'));
