<?php
defined('ANS') or die();

include ($Data->file('acl-espazo.php'));

if ($Vars->getExitMode('csv')) {
    
    $espazos = $Db->queryResult('
        select e.*, f.url as shape, p.*
        from espazos as e
        inner join formas as f on e.id = f.`id_espazos`
        inner join puntos as p on f.id = p.`id_formas`
        where e.id = ' . $espazo['id'] . ';
    ');
    
    $Data->execute('sub-csv.php', array(
        'name' => __('Espazo'),
        'data' => $espazos,
        'fields' => '*',
        'exclude' => array('validado', 'bloqueado', 'activo', 'kml')
    ));
}

if ($Vars->getExitMode('kml')) {
    $Data->execute('sub-kml.php', array(
        'name' => $espazo['titulo'],
        'description' => $espazo['texto'],
        'polygons' => $espazo['poligonos']
    ));
}

//TODO: Recuperar las especies en funcion de las coordenadas
$especies = array();

$imaxes = $Db->select(array(
    'table' => 'imaxes',
    'fields' => '*',
    'sort' => array('portada DESC'),
    'conditions' => array(
        'espazos.id' => $espazo['id'],
        'activo' => 1
    ),
    'add_tables' => array(
        array(
            'table' => 'imaxes_tipos',
            'fields' => '*',
            'limit' => 1,
            'conditions' => array(
                'activo' => 1
            )
        )
    )
));

$seguidores = $Db->select(array(
	'table' => 'usuarios',
	'sort' => 'nome ASC',
	'conditions' => array(
		'activo' => 1,
		'espazos(vixiar).id' => $espazo['id']
	)
));

$comentarios = $Db->select(array(
    'table' => 'comentarios',
    'fields' => '*',
    'sort' => 'data DESC',
    'conditions' => array(
        'espazos.id' => $espazo['id'],
        'activo' => 1,
        'usuarios(autor).activo' => 1
    ),
    'add_tables' => array(
        array(
            'table' => 'usuarios',
            'name' => 'autor',
            'fields' => '*',
            'limit' => 1
        )
    )
));

$tiposPois = $Db->select(array(
	'table' => 'pois_tipos',
	'sort' => 'nome ASC',
	'conditions' => array(
		'activo' => 1
	)
));

$avistamentos = $Db->select(array(
    'table' => 'avistamentos',
    'fields' => '*',
    'limit' => 500,
    'conditions' => array(
        'activo' => 1,
        'espazos.id' => $espazo['id']
    ),
    'add_tables' => array(
        array(
            'table' => 'usuarios',
            'name' => 'autor',
            'fields' => '*',
            'limit' => 1
        ),
        array(
            'table' => 'especies',
            'fields' => '*',
            'limit' => 1
        ),
         array(
            'table' => 'puntos',
            'conditions' => array(
            	'puntos_tipos.numero' => 4,
                'espazos.id' => $espazo['id']
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
            	'arquivo >' => 0,
                'espazos.id' => $espazo['id']
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
                'espazos.id' => $espazo['id']
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
                'espazos.id' => $espazo['id']
            ),
            'add_tables' => array(
            	array(
            		'table' => 'puntos_tipos',
            		'limit' => 1
            	)
            )
        ),
        'imaxe' => array(
            'table' => 'imaxes',
            'sort' => 'portada DESC',
            'limit' => 1,
            'conditions' => array(
                'activo' => 1
            ),
            'add_tables' => array(
                array(
                    'table' => 'imaxes_tipos',
                    'fields' => '*',
                    'limit' => 1,
                    'conditions' => array(
                        'activo' => 1
                    )
                )
            )
        )
    )
));

$grupos = array();

foreach($espazo['especies'] as $especie) {
    
    $grupo = $especie['grupos'];
    $familia = $especie['familias'];
    
    if (!$grupos[$grupo['url']]) {
        $grupos[$grupo['url']] = array(
            'nome' => $grupo['nome'],
            'familias' => array()
        );
    }
    
    if (!$grupos[$grupo['url']]['familias'][$familia['url']]) {
        $grupos[$grupo['url']]['familias'][$familia['url']] = array(
            'nome' => $familia['nome'],
            'especies' => array()
        );
    }
    
    $grupos[$grupo['url']]['familias'][$familia['url']]['especies'][] = $especie;
}

$shapes = getPuntosFormas('espazo', $espazo, null, null, $espazo['puntos'], $espazo['poligonos'], $espazo['lineas']);

$Html->meta('title', $espazo['titulo']);
