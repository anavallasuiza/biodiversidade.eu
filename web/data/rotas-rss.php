<?php
defined('ANS') or die();

$rotas = setRowsLanguage(array(
    'table' => 'rotas',
    'fields' => '*',
    'limit' => 10,
    'sort' => 'data DESC',
    'conditions' => array(
        'data <=' => date('Y-m-d H:i:s'),
        'activo' => 1
    )
));

$listado = array();

foreach ($rotas as $rota) {
    $listado[] = array(
        'titulo' => $rota['titulo'],
        'link' => absolutePath('rota', $rota['url']),
        'texto' => $rota['texto'],
        'data_publicacion' => $rota['data']
    );
}

$Html->meta('title', __('Rotas'));
