<?php
defined('ANS') or die();

include ($Data->file('acl-nova.php'));

$imaxes = $Db->select(array(
    'table' => 'imaxes',
    'fields' => '*',
    'sort' => 'portada desc',
    'conditions' => array(
        'novas.id' => $nova['id'],
        'activo' => 1
    )
));

$comentarios = $Db->select(array(
    'table' => 'comentarios',
    'fields' => '*',
    'sort' => 'data DESC',
    'conditions' => array(
        'novas.id' => $nova['id'],
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

$outros_comentarios = $Db->select(array(
    'table' => 'comentarios',
    'fields' => '*',
    'limit' => 6,
    'conditions' => array(
        'novas.activo' => 1,
        'novas.id !=' => $nova['id'],
        'usuarios(autor).activo' => 1,
        'activo' => 1
    ),
    'add_tables' => array(
        array(
            'table' => 'novas',
            'fields' => '*',
            'limit' => 1
        ),
        array(
            'table' => 'usuarios',
            'name' => 'autor',
            'fields' => '*',
            'limit' => 1
        )
    )
));

$Html->meta('title', $nova['titulo']);
$Html->meta('description', $nova['texto']);

if ($imaxes) {
    $Html->meta('image', fileWeb('uploads|'.$imaxes[0]['imaxe']));
}
