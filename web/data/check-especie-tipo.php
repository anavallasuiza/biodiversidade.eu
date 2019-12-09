<?php
defined('ANS') or die();

$nome = $Vars->var['nome'];

$especie = $Db->select(array(
    'table' => 'especies',
    'fields' => 'id',
    'limit' => 1,
    'conditions' => array(
        'nome_cientifico' => $nome,
        'subespecie' => '',
        'variedade' => ''
    )
));

die(json_encode($especie));