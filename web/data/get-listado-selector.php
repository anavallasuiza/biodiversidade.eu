<?php
defined('ANS') or die();

$texto = $Vars->var['texto'];

$catalogo = $Vars->var['catalogo'] ? true : false;

$grupos = array();
$clases = array();
$ordes = array();
$familias = array();
$xeneros = array();
$especies = array();

// Se hai menos de tres caracteres devolvemos todo
if (strlen($texto) < 3) {
	$grupos = $Db->select(array(
		'table' => 'grupos',
		'sort' => 'nome ASC',
        'group' => 'nome',
	    'conditions' => array(
	        'activo' => 1
	    )
	));

	foreach ($grupos as &$grupo) {
		$grupo['avistamentos'] = $Db->select(array(
			'table' => 'avistamentos',
            'fields' => 'id',
			'fields_command' => 'COUNT(id) as number',
			'limit' => 1,
			'conditions' => array(
				'especies.grupos.id' => $grupo['id'],
				'activo' => 1
			)
		));
	}	

	unset($grupo);

	return true;
}

function isInArray($item, $data) {
	foreach ($data as $row) {
		if ($row['id'] === $item['id']) {
			return true;
		}
	}

	return false;
}

function mergeData($data, $items) {
	foreach ($items as $item) {
		if (!isInArray($item, $data, $debug)) {
			$data[] = $item;
		}
	}

	return $data;
}

$grupos = $Db->select(array(
	'table' => 'grupos',
	'sort' => 'nome ASC',
	'conditions' => array(
		'activo' => 1,
		array(
			'nome LIKE' => '%' . $texto . '%'
		)
	)
));

$clases = $Db->select(array(
	'table' => 'clases',
	'group' => 'id',
	'sort' => 'nome ASC',
	'conditions' => array(
		array(
			'grupos.id' => arrayKeyValues($grupos, 'id'),
			'nome LIKE' => '%' . $texto . '%'
		)
	),
	'add_tables' => array(
        array(
			'table' => 'grupos',
			'limit' => 1
		)
	)
));

$grupos = mergeData($grupos, arrayKeyValues($clases, 'grupos'));

$ordes = $Db->select(array(
	'table' => 'ordes',
	'group' => 'id',
	'sort' => 'nome ASC',
	'conditions' => array(
		array(
			'clases.id' => arrayKeyValues($clases, 'id'),
			'nome LIKE' => '%' . $texto . '%'
		)
	),
	'add_tables' => array(
        array(
			'table' => 'grupos',
			'limit' => 1
		),
		array(
			'table' => 'clases',
			'limit' => 1,
			'add_tables' => array(
				array(
					'table' => 'grupos',
					'limit' => 1
				)
			)
		)
	)
));

$grupos = mergeData($grupos, arrayKeyValues($ordes, 'grupos'));
$clases = mergeData($clases, arrayKeyValues($ordes, 'clases'));

$familias = $Db->select(array(
	'table' => 'familias',
	'group' => 'id',
	'sort' => 'nome ASC',
	'conditions' => array(
		array(
			'ordes.id' => arrayKeyValues($ordes, 'id'),
			'nome LIKE' => '%' . $texto . '%'
		)
	),
	'add_tables' => array(
        array(
			'table' => 'grupos',
			'limit' => 1
		),
		array(
			'table' => 'clases',
			'limit' => 1,
			'add_tables' => array(
				array(
					'table' => 'grupos',
					'limit' => 1
				)
			)
		),
		array(
			'table' => 'ordes',
			'limit' => 1,
			'add_tables' => array(
				array(
					'table' => 'clases',
					'limit' => 1
				)
			)
		)
	)
));

$grupos = mergeData($grupos, arrayKeyValues($familias, 'grupos'));
$clases = mergeData($clases, arrayKeyValues($familias, 'clases'));
$ordes = mergeData($ordes, arrayKeyValues($familias, 'ordes'));

$xeneros = $Db->select(array(
	'table' => 'xeneros',
	'group' => 'id',
	'sort' => 'nome ASC',
	'conditions' => array(
		array(
			'familias.id' => arrayKeyValues($familias, 'id'),
			'nome LIKE' => '%' . $texto . '%'
		)
	),
	'add_tables' => array(
        array(
			'table' => 'grupos',
			'limit' => 1
		),
		array(
			'table' => 'clases',
			'limit' => 1,
			'add_tables' => array(
				array(
					'table' => 'grupos',
					'limit' => 1
				)
			)
		),
		array(
			'table' => 'ordes',
			'limit' => 1,
			'add_tables' => array(
				array(
					'table' => 'clases',
					'limit' => 1
				)
			)
		),
		array(
			'table' => 'familias',
			'limit' => 1,
			'add_tables' => array(
				array(
					'table' => 'ordes',
					'limit' => 1
				)
			)
		)
	)
));

$grupos = mergeData($grupos, arrayKeyValues($xeneros, 'grupos'));
$clases = mergeData($clases, arrayKeyValues($xeneros, 'clases'));
$ordes = mergeData($ordes, arrayKeyValues($xeneros, 'ordes'));
$familias = mergeData($familias, arrayKeyValues($xeneros, 'familias'));

if (empty($catalogo)) {
    $conditions = array(
        array(
            'xeneros.id' => arrayKeyValues($xeneros, 'id'),
            'nome LIKE' => '%' . $texto . '%',
            'nome_comun LIKE' => '%' . $texto . '%',
            'sinonimos LIKE' => '%' . $texto . '%'
        ),
        'activo' => 1
    );

	$especies = $Db->select(array(
		'table' => 'especies',
		'group' => 'id',
		'sort' => 'nome ASC',
		'conditions' => $conditions,
		'add_tables' => array(
            array(
				'table' => 'grupos',
				'limit' => 1
			),
			array(
				'table' => 'clases',
				'limit' => 1,
				'add_tables' => array(
					array(
						'table' => 'grupos',
						'limit' => 1
					)
				)
			),
			array(
				'table' => 'ordes',
				'limit' => 1,
				'add_tables' => array(
					array(
						'table' => 'clases',
						'limit' => 1
					)
				)
			),
			array(
				'table' => 'familias',
				'limit' => 1,
				'add_tables' => array(
					array(
						'table' => 'ordes',
						'limit' => 1
					)
				)
			),
			array(
				'table' => 'xeneros',
				'limit' => 1,
				'add_tables' => array(
					array(
						'table' => 'familias',
						'limit' => 1
					)
				)
			),
	        'imaxe' => array(
	            'table' => 'imaxes',
	            'fields' => '*',
	            'sort' => 'portada DESC',
	            'limit' => 1,
	            'conditions' => array(
	                'activo' => 1
	            )
	        )
		)
	));

    $grupos = mergeData($grupos, arrayKeyValues($especies, 'grupos'));
	$clases = mergeData($clases, arrayKeyValues($especies, 'clases'));
	$ordes = mergeData($ordes, arrayKeyValues($especies, 'ordes'));
	$familias = mergeData($familias, arrayKeyValues($especies, 'familias'));
	$xeneros = mergeData($xeneros, arrayKeyValues($especies, 'xeneros'));
}

$datos = array(
    'grupos' => array(),
	'clases' => array(),
	'ordes' => array(),
	'familias' => array(),
	'xeneros' => array(),
	'especies' => array()
);

foreach ($grupos as $grupo) {
	$datos['grupos'][$grupo['id']] = $Db->selectCount(array(
		'table' => 'avistamentos',
		'conditions' => array(
			'especies.grupos.id' => $grupo['id']
		)
	));
}

foreach ($clases as $clase) {
	$datos['clases'][$clase['id']] = $Db->selectCount(array(
		'table' => 'avistamentos',
		'conditions' => array(
			'especies.clases.id' => $clase['id']
		)
	));
}

foreach ($ordes as $orde) {
	$datos['ordes'][$orde['id']] = $Db->selectCount(array(
		'table' => 'avistamentos',
		'conditions' => array(
			'especies.ordes.id' => $orde['id']
		)
	));
}

foreach ($familias as $familia) {
	$datos['familias'][$familia['id']] = $Db->selectCount(array(
		'table' => 'avistamentos',
		'conditions' => array(
			'especies.familias.id' => $familia['id']
		)
	));
}

foreach ($xeneros as $xenero) {
	$datos['xeneros'][$xenero['id']] = $Db->selectCount(array(
		'table' => 'avistamentos',
		'conditions' => array(
			'especies.xeneros.id' => $xenero['id']
		)
	));
}

if (!$catalogo) {
	foreach ($especies as $especie) {
		$datos['especies'][$especie['id']] = $Db->selectCount(array(
			'table' => 'avistamentos',
			'conditions' => array(
				'especies.id' => $especie['id']
			)
		));
	}
}