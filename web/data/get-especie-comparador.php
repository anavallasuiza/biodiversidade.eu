<?php
defined('ANS') or die();

$codigoEspecie = $Vars->var['especie'];
$reino = $Vars->var['reino'];

$images = array();

$tipos = $Db->select(array(
    'table' => 'imaxes_tipos',
    'fields' => '*',
    'sort' => 'nome ASC',
    'conditions' => array(
        'reinos.url' => $reino,
        'activo' => 1
    )
));

$especie = $Db->select(array(
    'table' => 'especies',
    'limit' => 1,
    'conditions' => array(
        'url' => $codigoEspecie
    )
));

$especie['tipos'] = array();
    
foreach($tipos as $tipo) {
    
    $especie['tipos'][$tipo['url']] = $Db->queryResult('
        select *
        from `imaxes` i
        inner join imaxes_tipos it on i.`id_imaxes_tipos` = it.id
        left join avistamentos a on i.`id_avistamentos` = a.id
        left join especies e on e.id = i.`id_especies` or e.id = a.`id_especies`
        where e.id = ' . $especie['id'] . ' and it.id = ' . $tipo['id']);
}