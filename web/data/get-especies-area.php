<?php
defined('ANS') or die();

$especies = \Eu\Biodiversidade\FerramentaAreas::getEspecies($Vars->var);

$grupos = array();

foreach($especies as $especie) {
    
    if (!$grupos[$especie['grupo_url']]) {
        $grupos[$especie['grupo_url']] = array(
            'nome' => $especie['grupo_nome'],
            'especies' => array()
        );
    }
    
    $grupos[$especie['grupo_url']]['especies'][] = $especie;
}
