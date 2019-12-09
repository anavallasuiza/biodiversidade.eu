<?php
defined('ANS') or die();

include ($Data->file('acl-proxecto.php'));
include ($Data->file('acl-caderno.php'));

$imaxes = $Db->select(array(
    'table' => 'imaxes',
    'fields' => '*',
    'sort' => 'portada desc',
    'conditions' => array(
        'cadernos.id' => $caderno['id'],
        'activo' => 1
    )
));

$comentarios = $Db->select(array(
    'table' => 'comentarios',
    'fields' => '*',
    'sort' => 'data DESC',
    'conditions' => array(
        'activo' => 1,
        'cadernos.id' => $caderno['id']
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

$backups = $Data->execute('get-backups.php', array(
    'limit' => 20,
    'conditions' => array(
        'related_table' => 'cadernos',
        'related_id' => $caderno['id']
    )
));

$Html->meta('title', $caderno['titulo']);
