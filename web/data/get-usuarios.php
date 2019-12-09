<?php
defined('ANS') or die();

$q = strip_tags($Vars->var['q']);

(strlen($q) > 2) or die(json_encode(array()));

$select = $Db->select(array(
    'table' => 'usuarios',
    'fields' => array('nome', 'apelido1', 'apelido2'),
    'fields_commands' => '`nome-title` as text, `nome-url` as id',
    'conditions' => array(
        'activo' => 1
    ),
    'conditions_or' => array(
        'nome-title LIKE ' => ('%'.str_replace(' ', '%', $q).'%'),
        'apelido1 LIKE ' => ('%'.str_replace(' ', '%', $q).'%'),
        'apelido2 LIKE ' => ('%'.str_replace(' ', '%', $q).'%')
    )
));

array_walk($select, function (&$value) {
    $value['text'] .= ' '.$value['apelido1'].' '.$value['apelido2'].' ('.$value['id'].')';
});

die(json_encode($select));