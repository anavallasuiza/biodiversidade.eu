<?php
defined('ANS') or die();

$territorios = $Db->select(array(
    'table' => 'territorios',
    'fields' => '*',
    'sort' => array('orde ASC', 'nome ASC')
));

$ameazas_tipos = $Db->select(array(
    'table' => 'ameazas_tipos',
    'fields' => '*',
    'sort' => array('orde ASC', 'nome ASC')
));

$iniciativas_tipos = $Db->select(array(
    'table' => 'iniciativas_tipos',
    'fields' => '*',
    'sort' => array('orde ASC', 'nome ASC')
));

$conditions_ameazas = $conditions_iniciativas = array(
    'activo' => 1
);

if ($Vars->var['ameaza_tipo']) {
    $conditions_ameazas['ameazas_tipos.id'] = intval($Vars->var['ameaza_tipo']);
}

if ($Vars->var['iniciativa_tipo']) {
    $conditions_iniciativas['iniciativas_tipos.id'] = intval($Vars->var['iniciativa_tipo']);
}

if ($Vars->var['nivel']) {
    $conditions_ameazas['nivel'] = intval($Vars->var['nivel']);
}

if (strlen($Vars->var['estado'])) {
    $conditions_iniciativas['estado'] = $conditions_ameazas['estado'] = intval($Vars->var['estado']);
}

if ($Vars->var['concello']) {
    $conditions_iniciativas['concellos.nome-url'] = $conditions_ameazas['concellos.nome-url'] = $Vars->var['concello'];
}

if ($Vars->var['provincia']) {
    $conditions_iniciativas['provincias.nome-url'] = $conditions_ameazas['provincias.nome-url'] = $Vars->var['provincia'];
}

if ($Vars->var['territorio']) {
    $conditions_iniciativas['territorios.url'] = $conditions_ameazas['territorios.url'] = $Vars->var['territorio'];
}

if ($Vars->var['data']) {
    $conditions_iniciativas['data LIKE'] = $conditions_ameazas['data LIKE'] = intval($Vars->var['ano']).'%';
}

if ($Vars->var['especie']) {
    $conditions_ameazas['especies.url'] = $Vars->var['especie'];

    $idEspecie = $Vars->var['especie'];

    $especie = $Db->select(array(
        'table' => 'especies',
        'limit' => 1,
        'conditions' => array(
            'url' => $idEspecie
        )
    ));

    $nomeEspecie = $especie['nome'];
}

$listaAmeazas = setRowsLanguage(array(
    'table' => 'ameazas',
    'fields' => '*',
    'sort' => 'data DESC',
    'conditions' => $conditions_ameazas,
    'add_tables' => array(
        array(
            'table' => 'ameazas_tipos',
            'fields' => '*'
        ),
        array(
            'table' => 'especies',
            'fields' => '*'
        ),
        array(
            'table' => 'concellos',
            'fields' => '*',
            'limit' =>1
        ),
        'imaxe' => array(
            'table' => 'imaxes',
            'fields' => '*',
            'sort' => 'portada DESC',
            'limit' => 1,
            'conditions' => array(
                'activo' => 1
            )
        ),
        array(
            'table' => 'provincias',
            'fields' => '*',
            'limit' =>1
        ),
        array(
            'table' => 'territorios',
            'fields' => '*',
            'limit' =>1
        ),
        array(
            'table' => 'comentarios',
            'fields' => 'id'
        ),
        array(
            'table' => 'puntos',
            'fields' => '*'
        ),
        'lineas' => array(
            'table' => 'formas',
            'fields' => '*',
            'conditions' => array(
                'tipo' => 'polyline'
            ),
            'add_tables' => array(
                array(
                    'table' => 'puntos',
                    'sort' => 'orde desc',
                    'fields' => '*'
                )
            )
        ),
        'poligonos' => array(
            'table' => 'formas',
            'fields' => '*',
            'conditions' => array(
                'tipo' => 'polygon'
            ),
            'add_tables' => array(
                array(
                    'table' => 'puntos',
                    'sort' => 'orde desc',
                    'fields' => '*'
                )
            )
        ),
        array(
            'table' => 'usuarios',
            'name' => 'autor',
            'limit' => 1
        )
    )
));

$ameazas = array();
$paxina = array(array(), array());

foreach ($listaAmeazas as $i => $ameaza) {
    $ameaza['zonas'] = getZonaAmeaza($ameaza);
    $ameaza['shapes'] = getPuntosAmeaza($ameaza, $ameaza['puntos'], $ameaza['poligonos'], $ameaza['lineas']);
    
    if ($i % 2 === 0) {
        $paxina[0][] = $ameaza;
    } else {
        $paxina[1][] = $ameaza;
    }

    if ($i && ($i + 1) % 6 === 0) {
        $ameazas[] = $paxina;
        $paxina = array(array(), array());
    }
}

if ($paxina && $paxina[0]) {
    $ameazas[] = $paxina;
}

$listaIniciativas = setRowsLanguage(array(
    'table' => 'iniciativas',
    'fields' => '*',
    'sort' => 'data DESC',
    'conditions' => $conditions_iniciativas,
    'add_tables' => array(
        array(
            'table' => 'iniciativas_tipos',
            'fields' => '*'
        ),
        array(
            'table' => 'especies',
            'fields' => '*'
        ),
        array(
            'table' => 'concellos',
            'fields' => '*',
            'limit' =>1
        ),
        'imaxe' => array(
            'table' => 'imaxes',
            'fields' => '*',
            'sort' => 'portada DESC',
            'limit' => 1,
            'conditions' => array(
                'activo' => 1
            )
        ),
        array(
            'table' => 'provincias',
            'fields' => '*',
            'limit' =>1
        ),
        array(
            'table' => 'territorios',
            'fields' => '*',
            'limit' =>1
        ),
        array(
            'table' => 'comentarios',
            'fields' => 'id'
        ),
        array(
            'table' => 'puntos',
            'fields' => '*'
        ),
        'lineas' => array(
            'table' => 'formas',
            'fields' => '*',
            'conditions' => array(
                'tipo' => 'polyline'
            ),
            'add_tables' => array(
                array(
                    'table' => 'puntos',
                    'sort' => 'orde desc',
                    'fields' => '*'
                )
            )
        ),
        'poligonos' => array(
            'table' => 'formas',
            'fields' => '*',
            'conditions' => array(
                'tipo' => 'polygon'
            ),
            'add_tables' => array(
                array(
                    'table' => 'puntos',
                    'sort' => 'orde desc',
                    'fields' => '*'
                )
            )
        ),
        array(
            'table' => 'usuarios',
            'name' => 'autor',
            'limit' => 1
        )
    )
));

$iniciativas = array();
$paxina = array(array(), array());

foreach ($listaIniciativas as $i => $iniciativa) {
    $iniciativa['zonas'] = getZonaAmeaza($iniciativa);
    $iniciativa['shapes'] = getPuntosAmeaza($iniciativa, $iniciativa['puntos'], $iniciativa['poligonos'], $iniciativa['lineas']);
    
    if ($i % 2 === 0) {
        $paxina[0][] = $iniciativa;
    } else {
        $paxina[1][] = $iniciativa;
    }

    if ($i && ($i + 1) % 6 === 0) {
        $iniciativas[] = $paxina;
        $paxina = array(array(), array());
    }
}

if ($paxina && $paxina[0]) {
    $iniciativas[] = $paxina;
}

$Html->meta('title', __('Ameazas e iniciativas de conservación'));
