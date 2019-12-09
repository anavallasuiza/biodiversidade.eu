<?php
defined('ANS') or die();

$backup = $Db->select(array(
    'table' => 'backups',
    'limit' => 1,
    'conditions' => array(
        'id' => $Vars->var['id']
    ),
    'add_tables' => array(
        'autor' => array(
            'table' => 'usuarios',
            'name' => 'autor',
            'limit' => 1
        ),
        'caderno' => array(
            'table' => 'cadernos',
            'limit' => 1
        )
    )
));

if (empty($backup)) {
    $Vars->message(__('O backup especificado non existe'), 'ko');
    redirect(path('caderno'));
}

$caderno = unserialize(base64_decode($backup['content']));

$proxecto = $Db->select(array(
    'table' => 'proxectos',
    'fields' => 'url',
    'limit' => 1,
    'conditions' => array(
        'cadernos.id' => $caderno['id']
    )
));

$backups = $Data->execute('get-backups.php', array(
    'limit' => 20,
    'conditions' => array(
        'related_table' => 'cadernos',
        'related_id' => $caderno['id']
    )
));

$Html->meta('title', $backup['caderno']['titulo']);
