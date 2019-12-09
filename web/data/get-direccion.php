<?php
defined('ANS') or die();

$pais = $Vars->var['pais'];
$provincia = $Vars->var['provincia'];
$concello = $Vars->var['concello'];

$result = array();

if ($pais && $provincia) {
    
    $result['territorio'] = $Db->select(array(
        'table' => 'territorios',
        'limit' => 1,
        'conditions' => array(
            'provincias.nome LIKE' => '%' . $provincia . '%',
            'paises.nome' => $pais
        )
    ));
    
    $result['provincia'] = $Db->select(array(
        'table' => 'provincias',
        'limit' => 1,
        'conditions' => array(
            'nome' => $provincia,
            'paises.nome' => $pais
        )
    ));
    
    if ($concello) {
        $result['concello'] = $Db->select(array(
            'table' => 'concellos',
            'limit' => 1,
            'conditions' => array(
                'nome LIKE' => '%' . $concello . '%',
                'provincias.id' => $result['provincia']['id']
            )
        ));
    }
}

echo(json_encode($result));
die();