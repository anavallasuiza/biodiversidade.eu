<?php
defined('ANS') or die();

if ($Vars->getExitMode('csv')) {
    
    if ($Vars->var['tipo'] === 'observacions') {
        $fields = 'a.`nome`, a.`localidade`, a.`nome_zona`, a.`referencia`, a.`tipo_referencia`, a.`outros_observadores`, a.`colector`, a.`observacions`, ' . 
            'u.`nome-title` as `observador`, p.`latitude`, p.`lonxitude`, p.`datum`, p.`mgrs`, p.`utm_x`, p.`utm_y`, ' . 
            'c.`nome-title` as `concello`, pr.`nome-title` as `provincia` ';
        
        $avistamentos = \Eu\Biodiversidade\FerramentaAreas::getAvistamentos($Vars->var['data'], $fields);
        
        $Data->execute('sub-csv.php', array(
            'name' => __('Observacions'),
            'data' => $avistamentos,
            'fields' => '*',
            'exclude' => array('validada', 'bloqueada', 'activo')
        ));
        
    } else {
        
        $codigos = $Vars->var['especies'];
        
        $especies = $Db->select(array(
            'table' => 'especies',
            'fields' => array('nome', 'aloctona', 'invasora', 'protexida', 'nivel_ameaza'),
            'sort' => 'nome DESC',
            'conditions' => array(
                'url' => $codigos,
                'activo' => 1
            )
        ));
        
        $Data->execute('sub-csv.php', array(
            'name' => __('Especies'),
            'data' => $especies,
            'fields' => '*',
            'exclude' => array('validada', 'bloqueada', 'activo')
        ));
    }
}

$territorios = $Db->select(array(
    'table' => 'territorios',
    'fields' => '*',
    'sort' => array('orde ASC', 'nome ASC')
));

$proteccions = $Db->select(array(
    'table' => 'proteccions_tipos',
    'fields' => '*',
    'sort' => 'nome ASC',
    'conditions' => array(
        'activo' => 1
    )
));
