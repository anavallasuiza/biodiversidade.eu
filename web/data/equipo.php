<?php
defined('ANS') or die();

include ($Data->file('acl-equipo.php'));

$equipos = $Db->select(array(
    'table' => 'equipos',
    'fields' => '*',
    'conditions' => array(
        'id !=' => $equipo['id'],
        'usuarios.id' => $user['id'],
        'activo' => 1
    ),
    'add_tables' => array(
        'imaxe' => array(
            'table' => 'imaxes',
            'fields' => '*',
            'sort' => 'portada DESC',
            'limit' => 1,
            'conditions' => array(
                'activo' => 1
            )
        )
    )
));

$solicitado = $solicitudes = 0;

if (MEU) {
    $solicitudes = $Db->selectCount(array(
        'table' => 'usuarios',
        'conditions' => array(
            'equipos(solicitudes).id' => $equipo['id']
        )
    ));
} else if (DENTRO === false) {
    $solicitado = $Db->selectCount(array(
        'table' => 'usuarios',
        'conditions' => array(
            'equipos(solicitudes).id' => $equipo['id'],
            'id' => $user['id']
        )
    ));
}

$Html->meta('title', $equipo['titulo']);
