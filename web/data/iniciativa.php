<?php
defined('ANS') or die();

include ($Data->file('acl-iniciativa.php'));

$comentarios = $Db->select(array(
    'table' => 'comentarios',
    'fields' => '*',
    'sort' => 'data DESC',
    'conditions' => array(
        'iniciativas.id' => $iniciativa['id'],
        'activo' => 1,
        'usuarios(autor).activo' => 1
    ),
    'add_tables' => array(
        array(
            'table' => 'usuarios',
            'name' => 'autor',
            'fields' => '*',
            'limit' => 1
        )
    )
));

$imaxes = $Db->select(array(
    'table' => 'imaxes',
    'fields' => '*',
    'conditions' => array(
        'iniciativas.id' => $iniciativa['id'],
        'activo' => 1
    )
));

$seguidores = $Db->select(array(
    'table' => 'usuarios',
    'sort' => 'nome ASC',
    'conditions' => array(
        'activo' => 1,
        'iniciativas(vixiar).id' => $iniciativa['id']
    )
));

$zonas = getZonaAmeaza($iniciativa);

$shapes = getPuntosAmeaza($iniciativa, $iniciativa['puntos'], $iniciativa['poligonos'], $iniciativa['lineas']);

$Html->meta('title', $iniciativa['titulo']);
