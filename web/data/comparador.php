<?php
defined('ANS') or die();

$codigoReino = $Vars->var['reino'];
$codigos = $Vars->var['especies'];

$especies = array();

if ($codigos) {
    $especies = $Db->select(array(
        'table' => 'especies',
        'conditions' => array(
            'url' => $codigos
        )
    ));
}

$reino = $Db->select(array(
    'table' => 'reinos',
    'limit' => 1,
    'conditions' => array(
        'url' => $codigoReino
    )
));

$grupos = $Db->select(array(
    'table' => 'grupos',
    'conditions' => array(
        'activo' => 1
    ),
    'add_tables' => array(
        array(
            'table' => 'reinos',
            'limit' => 1
        )
    )
));

$imaxes_tipos = $Db->select(array(
    'table' => 'imaxes_tipos',
    'fields' => '*',
    'sort' => 'nome ASC',
    'conditions' => array(
        'reinos.url' => $reino,
        'activo' => 1
    )
));

$imaxes = array();

foreach($especies as $especie) {
    
    $imaxes[$especie['url']] = array('nome' => $especie['nome'], 'url' => $especie['url'], 'tipos' => array());
    
    foreach($imaxes_tipos as $tipo) {
        
        $imaxes[$especie['url']]['tipos'][$tipo['url']] = $Db->queryResult('
            select *
            from `imaxes` i
            inner join imaxes_tipos it on i.`id_imaxes_tipos` = it.id
            left join avistamentos a on i.`id_avistamentos` = a.id
            left join especies e on e.id = i.`id_especies` or e.id = a.`id_especies`
            where e.id = ' . $especie['id'] . ' and it.id = ' . $tipo['id']);
    }
}

$Html->meta('title', __('Comparador de imaxes'));