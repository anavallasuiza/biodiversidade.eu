<?php
defined('ANS') or die();

$iniciativas = $Db->select(array(
    'table' => 'iniciativas',
    'fields' => '*',
    'limit' => 4,
    'sort' => 'data DESC',
    'conditions' => array(
        'data <=' => date('Y-m-d H:i:s'),
        'activo' => 1
    )
));

array_walk($iniciativas, function (&$value) {
    $value['titulo'] = __('Iniciativa').': '.$value['titulo'];
});

$ameazas = setRowsLanguage(array(
    'table' => 'ameazas',
    'fields' => '*',
    'limit' => (10 - count($iniciativas)),
    'sort' => 'data_alta DESC',
    'conditions' => array(
        'data_alta <=' => date('Y-m-d H:i:s'),
        'activo' => 1
    )
));

array_walk($ameazas, function (&$value) {
    $value['titulo'] = __('Ameaza').': '.$value['titulo'];
});

$ameazas = array_merge($ameazas, $iniciativas);

unset($inciativas);

usort($ameazas, function ($a, $b) {
    $adata = $a['data_alta'] ?: $a['data'];
    $bdata = $b['data_alta'] ?: $b['data'];

    return (strtotime($adata) > strtotime($bdata)) ? -1 : 1;
});

$listado = array();

foreach ($ameazas as $ameaza) {
    $listado[] = array(
        'titulo' => $ameaza['titulo'],
        'link' => absolutePath('ameaza', $ameaza['url']),
        'texto' => $ameaza['texto'],
        'data_publicacion' => $ameaza['data_alta']
    );
}

$Html->meta('title', __('Ameazas'));
