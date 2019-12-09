<?php
defined('ANS') or die();

include ($Data->file('acl-ameaza.php'));

$comentarios = $Db->select(array(
    'table' => 'comentarios',
    'fields' => '*',
    'sort' => 'data DESC',
    'conditions' => array(
        'ameazas.id' => $ameaza['id'],
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
        'ameazas.id' => $ameaza['id'],
        'activo' => 1
    )
));

$seguidores = $Db->select(array(
    'table' => 'usuarios',
    'sort' => 'nome ASC',
    'conditions' => array(
        'activo' => 1,
        'ameazas(vixiar).id' => $ameaza['id']
    )
));

$estado = $Db->select(array(
    'table' => 'logs',
    'fields' => '*',
    'limit' => 1,
    'sort' => 'id DESC',
    'conditions' => array(
        'ameazas.id' => $ameaza['id'],
        'action LIKE' => 'ameazas-estado-%'
    ),
    'add_tables' => array(
        array(
            'table' => 'usuarios',
            'name' => 'autor',
            'fields' => 'nome',
            'limit' => 1
        )
    )
));

if ($estado) {
    $estado['action'] = str_replace('ameazas-estado-', '', $estado['action']);
}

$zonas = getZonaAmeaza($ameaza);

$shapes = getPuntosAmeaza($ameaza, $ameaza['puntos'], $ameaza['poligonos'], $ameaza['lineas']);

$Html->meta('title', $ameaza['titulo']);
