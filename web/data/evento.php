<?php
defined('ANS') or die();

include ($Data->file('acl-evento.php'));

$imaxes = $Db->select(array(
    'table' => 'imaxes',
    'fields' => '*',
    'sort' => 'portada desc',
    'conditions' => array(
        'eventos.id' => $evento['id'],
        'activo' => 1
    )
));

$adxuntos = $Db->select(array(
    'table' => 'adxuntos',
    'fields' => '*',
    'conditions' => array(
        'eventos.id' => $evento['id']
    )
));

$eventos = $Db->select(array(
    'table' => 'eventos',
    'fields' => '*',
    'limit' => 3,
    'sort' => 'data ASC',
    'conditions' => array(
        'id !=' => $evento['id'],
        'data >=' => date('Y-m-d'),
        'activo' => 1
    )
));

$Datetime = getDatetimeObject();

$comentarios = $Db->select(array(
    'table' => 'comentarios',
    'fields' => '*',
    'sort' => 'data DESC',
    'conditions' => array(
        'eventos.id' => $evento['id'],
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

$Html->meta('title', $evento['titulo']);
$Html->meta('description', $evento['texto']);

if ($imaxes) {
    $Html->meta('image', fileWeb('uploads|'.$imaxes[0]['imaxe']));
}
