<?php
defined('ANS') or die();

include ($Data->file('acl-rota.php'));

if ($Vars->getExitMode('csv')) {
    
    $rotas = $Db->queryResult('
        select r.*, f.url as shape, p.*
        from rotas as r
        inner join formas as f on r.id = f.`id_rotas`
        inner join puntos as p on f.id = p.`id_formas`
        where r.id = ' . $rota['id'] . ';
    ');
    
    $Data->execute('sub-csv.php', array(
        'name' => __('Rota'),
        'data' => $rotas,
        'fields' => '*',
        'exclude' => array('validado', 'bloqueado', 'activo', 'kml')
    ));
}

if ($user) {
    $votar = !$Db->selectCount(array(
        'table' => 'votos',
        'conditions' => array(
            'rotas.id' => $rota['id'],
            'usuarios.id' => $user['id']
        )
    ));
} else {
    $votar = false;
}

$seguidores = $Db->select(array(
	'table' => 'usuarios',
	'sort' => 'nome ASC',
	'conditions' => array(
		'activo' => 1,
		'rotas(vixiar).id' => $rota['id']
	)
));

$imaxes = $Db->select(array(
    'table' => 'imaxes',
    'fields' => '*',
    'sort' => array('portada DESC'),
    'conditions' => array(
        'rotas.id' => $rota['id'],
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

$shapes = getPuntosFormas('rota', $rota, null, null, $rota['puntos'], $rota['poligonos'], $rota['lineas']);

$comentarios = $Db->select(array(
    'table' => 'comentarios',
    'fields' => '*',
    'sort' => 'data DESC',
    'conditions' => array(
        'rotas.id' => $rota['id'],
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

$points = $Db->select(array(
    'table' => 'puntos',
    'conditions' => array(
        'rotas.id' => $rota['id'],
        'so_altitude' => 1
    )
));

$elevation = str_replace('"', '\'', json_encode($points));

$avistamentos = $Db->select(array(
    'table' => 'avistamentos',
    'fields' => '*',
    'limit' => 500,
    'conditions' => array(
        'activo' => 1,
        'rotas.id' => $rota['id']
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
                'rotas.id' => $rota['id']
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
                'rotas.id' => $rota['id']
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
                'rotas.id' => $rota['id']
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
                'rotas.id' => $rota['id']
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

foreach($rota['especies'] as $especie) {
    
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

$Html->meta('title', $rota['titulo']);
$Html->meta('description', $rota['texto']);

if ($imaxes) {
    $Html->meta('image', fileWeb('uploads|'.$imaxes[0]['imaxe']));
}
