<?php
defined('ANS') or die();

include ($Data->file('acl-didactica.php'));

$adxuntos = $Db->select(array(
    'table' => 'adxuntos',
    'conditions' => array(
        'didacticas.id' => $didactica['id']
    )
));

$didacticas = $Db->select(array(
    'table' => 'didacticas',
    'fields' => '*',
    'sort' => 'data DESC',
    'conditions' => array(
        'id !=' => $didactica['id'],
        'activo' => 1
    )
));

$Html->meta('title', $didactica['titulo']);
