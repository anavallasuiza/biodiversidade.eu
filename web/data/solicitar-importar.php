<?php
defined('ANS') or die();

$grupos = $Db->select(array(
    'table' => 'grupos',
    'conditions' => array(
        'activo' => 1
    )
));

$usuarios = $Db->select(array(
    'table' => 'usuarios',
    'fields' => array('nome', 'apelido1', 'apelido2', 'especialidade'),
    'group' => 'usuarios.id',
    'conditions' => array(
        'roles.code' => array('nivel-3', 'editor'),
        'activo' => 1
    )
));

array_walk($usuarios, function (&$value) {
    $value['nome_completo'] = trim($value['nome']['title'].' '.$value['apelido1'].' '.$value['apelido2']);
});
