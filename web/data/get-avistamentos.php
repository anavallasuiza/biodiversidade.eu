<?php
defined('ANS') or die();

$conditions = array('activo' => 1);

if ($Vars->var['especies']) {
    $especies = $Vars->var['especies'];

    $avistamentos = array();
    $shapes = array();

    if (empty($especies)) {
    	if ($Vars->getExitMode('json')) {
    		die(json_encode($avistamentos));
    	} else {
    		return true;
    	}
    }

    $listaEspecies = $Db->select(array(
        'table' => 'especies',
        'conditions' => array(
            'url' => $especies
        ),
        'add_tables' => array(
            'xenero' => array(
                'table' => 'xeneros',
                'limit' => 1
            )
        )
    ));

    foreach ($listaEspecies as $especie) {
        if (empty($especie['subespecie']) && empty($especie['variedade'])) {
            $subespecies = $Db->select(array(
                'table' => 'especies',
                'conditions' => array(
                    'nome_cientifico LIKE' => '%'.trim($especie['nome_cientifico']).'%',
                    'id !=' => $especie['id'],
                    'subespecie !=' => '',
                    'activo' => 1
                ),
                'add_tables' => array(
                    array(
                        'table' => 'xeneros',
                        'limit' => 1
                    )
                )
            ));
            
            $especies = array_merge(arrayKeyValues($subespecies, 'url'), $especies);
        }
    }

    $conditions['especies.url'] = array_unique($especies);
}

if ($Vars->var['ids']) {
    $conditions['id'] = $Vars->var['ids'];
}

if ($Vars->var['urls']) {
    $conditions['url'] = $Vars->var['urls'];
}

if ($Vars->var['pais']) {
	$conditions['paises.id'] = $Vars->var['pais'];
}

if ($Vars->var['provincias']) {
	$conditions['provincias.id'] = $Vars->var['provincias'];
}

if ($Vars->var['concellos']) {
	$conditions['concellos.id'] = $Vars->var['concellos'];
}

if ($Vars->var['ano']) {
	$conditions['data_observacion >='] = $Vars->var['ano'] . '-01-01 0:00:00';
	$conditions['data_observacion <='] = $Vars->var['ano'] . '-12-31 23:59:59'; 
}

if ($Vars->var['ameaza']) {
	$conditions['especies.nivel_ameaza'] = $Vars->var['ameaza'];
}

if ($Vars->var['validada'] === '1' || $Vars->var['validada'] === '0') {
	$conditions['validado'] = $Vars->var['validada'];
}

if (count($conditions) === 1) {
    die('<p>'.__('Non temos ningún avistamento para as especies seleccionadas').'</p>');
}

$avistamentos = $Db->select(array(
    'table' => 'avistamentos',
    'fields' => '*',
    'sort' => 'data_observacion DESC',
    'conditions' => $conditions,
    'add_tables' => array(
        array(
            'table' => 'usuarios',
            'name' => 'autor',
            'fields' => '*',
            'limit' => 1
        ),
        array(
            'table' => 'usuarios',
            'name' => 'validador',
            'fields' => '*',
            'limit' => 1
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
        'especie' => array(
            'table' => 'especies',
            'fields' => '*',
            'limit' => 1,
            'add_tables' => array(
                array(
                    'table' => 'reinos',
                    'limit' => 1,
                ),
                array(
                    'table' => 'grupos',
                    'limit' => 1,
                ),
                array(
                    'table' => 'xeneros',
                    'limit' => 1,
                ),
                array(
                    'table' => 'familias',
                    'limit' => 1,
                ),
                array(
                    'table' => 'ordes',
                    'limit' => 1,
                ),
                array(
                    'table' => 'clases',
                    'limit' => 1,
                ),
                'parent' => array(
                    'table' => 'especies-parent',
                    'limit' => 1
                )
            )
        ),
        'xeolocalizacions' => array(
            'table' => 'puntos',
        ),
        array(
            'table' => 'puntos',
            'conditions' => array(
            	'puntos_tipos.numero' => 4
            ),
            'add_tables' => array(
            	array(
            		'table' => 'puntos_tipos',
            		'limit' => 1
            	)
            )
        ),
        'shapes' => array(
        	'table' => 'puntos',
            'conditions' => array(
            	'puntos_tipos.numero' => 3,
            	'arquivo >' => 0
            ),
            'add_tables' => array(
            	array(
            		'table' => 'puntos_tipos',
            		'limit' => 1
            	)
            )
        ),
        'centroides1' => array(
        	'table' => 'puntos',
            'conditions' => array(
            	'puntos_tipos.numero' => 1,
            ),
            'add_tables' => array(
            	array(
            		'table' => 'puntos_tipos',
            		'limit' => 1
            	)
            )
        ),
        'centroides10' => array(
        	'table' => 'puntos',
            'conditions' => array(
            	'puntos_tipos.numero' => 2,
            ),
            'add_tables' => array(
            	array(
            		'table' => 'puntos_tipos',
            		'limit' => 1
            	)
            )
        )
    )
));

foreach($avistamentos as &$avistamento) {
	foreach($avistamento['shapes'] as &$shape) {
		$shape['absolute_url'] = fileWeb('uploads|' . $shape['arquivo'], null, true);
	}

	if ((count($avistamento['puntos']) + count($avistamento['centroides1']) + count($avistamento['centroides10'])) === 1) {
		
		if ($avistamento['puntos']) {
			$punto = $avistamento['puntos'][0];
		} else if ($avistamento['centroides1']) {
			$punto = $avistamento['centroides1'][0];
		} else {
			$punto = $avistamento['centroides10'][0]; 
		}
		
		if ($punto['tipo'] === 'latlong') {
			$punto = $punto['latitude'] . ', ' . $punto['lonxitude'];
		} else if ($punto['tipo'] === 'utm') {
			$punto = $punto['utm_fuso'] . ($punto['utm_fuso'] == '29' ? 'N': '') . ' ' . $punto['utm_x'] . ' ' . $punto['utm_y'];
		} else  {
			$punto = $punto['mgrs'];
		}
		
		$avistamento['punto'] = $punto;
	}
	
	$avistamento['shapes'] = str_replace('"', '\'', json_encode($avistamento['shapes']));
	$avistamento['puntos'] = str_replace('"', '\'', json_encode($avistamento['puntos']));
	$avistamento['centroides1'] = str_replace('"', '\'', json_encode($avistamento['centroides1']));
	$avistamento['centroides10'] = str_replace('"', '\'', json_encode($avistamento['centroides10']));
}

define('EDITOR', ($user && $Acl->check('action', 'avistamento-editar-all')));

if ($Vars->getExitMode('json')) {
	die(json_encode($avistamentos));
}
