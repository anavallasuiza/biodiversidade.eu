<?php
defined('ANS') or die();

include ($Data->file('acl-proxecto.php'));

$cadernos = $Db->select(array(
    'table' => 'cadernos',
    'fields' => '*',
    'limit' => 10,
    'sort' => 'data_actualizado DESC',
    'conditions' => array(
        'proxectos.id' => $proxecto['id'],
        'activo' => 1
    ),
    'pagination' => array(
        'page' => ($Vars->int('p') ?: 1),
        'map' => 10
    ),
    'add_tables' => array(
        array(
            'table' => 'usuarios',
            'name' => 'autor',
            'fields' => '*',
            'limit' => 1
        ),
        array(
            'table' => 'comentarios',
            'fields' => 'id',
            'conditions' => array(
                'activo' => 1
            )
        )
    )
));

$imaxes = $Db->select(array(
    'table' => 'imaxes',
    'fields' => '*',
    'sort' => 'portada desc',
    'conditions' => array(
        'proxectos.id' => $proxecto['id'],
        'activo' => 1
    )
));

$adxuntos = $Db->select(array(
    'table' => 'adxuntos',
    'fields' => '*',
    'conditions' => array(
        'proxectos.id' => $proxecto['id']
    )
));

array_walk($cadernos, function (&$value) {
    $value['data_actualizado'] = strstr($value['data_actualizado'], '0000') ? null : $value['data_actualizado'];
});

$comentarios = $Db->select(array(
    'table' => 'comentarios',
    'fields' => '*',
    'sort' => 'data DESC',
    'conditions' => array(
        'activo' => 1,
        'proxectos.id' => $proxecto['id']
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

$avistamentos = $Db->select(array(
    'table' => 'avistamentos',
    'fields' => '*',
    'sort' => 'data_observacion DESC',
    'conditions' => array(
        'proxectos.id' => $proxecto['id'],
        'activo' => 1
    ),
    /*'pagination' => array(
        'page' => ($Vars->int('p') ?: 1),
        'map' => 10
    ),*/
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
            'table' => 'concellos',
            'fields' => '*',
            'limit' => 1
        ),
        'imaxe' => array(
            'table' => 'imaxes',
            'fields' => '*',
            'limit' => 1,
            'sort' => 'portada DESC',
            'conditions' => array(
                'portada' => 1,
                'activo' => 1
            )
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

$especies = $Db->select(array(
    'table' => 'especies',
    'fields' => '*',
    'sort' => 'nome ASC',
    'conditions' => array(
        'activo' => 1,
        'proxectos.id' => $proxecto['id']
    )
));

$ameazas = $Db->select(array(
    'table' => 'ameazas',
    'fields' => '*',
    'sort' => 'data DESC',
    'conditions' => array(
        'proxectos.id' => $proxecto['id'],
        'activo' => 1
    ),
    'add_tables' => array(
        array(
            'table' => 'ameazas_tipos',
            'fields' => '*'
        ),
        array(
            'table' => 'concellos',
            'fields' => '*',
            'limit' =>1
        ),
        array(
            'table' => 'comentarios',
            'fields' => 'id'
        ),
        'imaxe' => array(
            'table' => 'imaxes',
            'fields' => '*',
            'limit' => 1,
            'sort' => 'portada DESC',
            'conditions' => array(
                'portada' => 1,
                'activo' => 1
            )
        ),
        array(
            'table' => 'usuarios',
            'name' => 'autor',
            'limit' => 1
        )
    )
));

$espazos = $Db->select(array(
    'table' => 'espazos',
    'sort' => 'data DESC',
    'conditions' => array(
        'proxectos.id' => $proxecto['id'],
        'activo' => 1
    ),
    'add_tables' => array(
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
            'table' => 'usuarios',
            'name' => 'autor',
            'fields' => '*',
            'limit' => 1
        ),
        array(
            'table' => 'comentarios',
            'fields' => 'id'
        ),
        array(
            'table' => 'espazos_figuras',
            'limit' => 1
        ),
        array(
            'table' => 'espazos_tipos',
            'limit' => 1
        ),
        array(
            'table' => 'territorios',
            'limit' => 1
        )
    )
));

$rotas = $Db->select(array(
    'table' => 'rotas',
    'fields' => '*',
    'sort' => 'data DESC',
    'conditions' => array(
        'proxectos.id' => $proxecto['id'],
        'activo' => 1
    ),
    'add_tables' => array(
        'imaxe' => array(
            'table' => 'imaxes',
            'fields' => '*',
            'limit' => 1,
            'sort' => 'portada DESC',
            'conditions' => array(
                'portada' => 1,
                'activo' => 1
            )
        )
    )
));

$iniciativas = $Db->select(array(
    'table' => 'iniciativas',
    'sort' => 'titulo ASC',
    'conditions' => array(
        'proxectos.id' => $proxecto['id'],
        'activo' => 1
    ),
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
            'table' => 'usuarios',
            'name' => 'autor',
            'limit' => 1
        )
    )
));

if (MEU) {
    $logs = $Db->select(array(
        'table' => 'logs',
        'fields' => '*',
        'sort' => 'date DESC',
        'limit' => 5,
        'conditions' => array(
            'proxectos.id' => $proxecto['id']
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
} else {
    $log = array();
}

$Html->meta('title', $proxecto['titulo']);
