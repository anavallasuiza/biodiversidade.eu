<?php
defined('ANS') or die();

include ($Data->file('acl-especie.php'));

if ($Vars->getExitMode('csv')) {
    if ($Vars->var['tipo'] === 'observacions') {
        $avistamentos = $Db->queryResult('
            select a.*, e.url as`especie_url`, e.nome as `especie_nome`, u.`nome-url` as `usuarios_autor_url`, u.`nome-title` as `usuarios_autor_nome`, p.*
            from avistamentos as a
            inner join puntos as `p` on a.`id` = p.`id_avistamentos`
            inner join usuarios `u` on a.`id_usuarios_autor` = u.id
            left join avistamentos_territorios as `at` on a.id = at.`id_avistamentos`
            left join territorios as `t` on at.`id_territorios` = t.id
            left join avistamentos_provincias `ap` on a.id = ap.`id_avistamentos`
            left join provincias `pr` on ap.`id_provincias` = `pr`.id
            left join avistamentos_concellos `ac` on a.id = ac.`id_avistamentos`
            left join concellos `c` on ac.`id_concellos` = `c`.id
            left join especies e on a.`id_especies` = e.id
            left join familias f on f.id = e.`id_familias`
            left join grupos g on g.id = e.`id_grupos`
            where a.`id_especies` = ' . $especie['id'] . ';
        ');

        $Data->execute('sub-csv.php', array(
            'name' => __('Observacions de %s', $especie['nome']),
            'data' => $avistamentos,
            'fields' => '*',
            'exclude' => array('validada', 'bloqueada', 'activo')
        ));
    } else {
        $Data->execute('sub-csv.php', array(
            'name' => __('Especie %s', $especie['nome']),
            'data' => array($especie),
            'fields' => '*',
            'exclude' => array('validada', 'bloqueada', 'activo')
        ));
    }
}

$avistamentos = $Db->select(array(
    'table' => 'avistamentos',
    'fields' => '*',
    'sort' => 'data_alta DESC',
    'conditions' => array(
        'especies.id' => $especie['id'],
        'activo' => 1
    ),
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
        array(
            'table' => 'especies',
            'fields' => '*',
            'limit' => 1
        ),
        array(
            'table' => 'puntos',
            'fields' => array('latitude', 'lonxitude')
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

$imaxesAvistamentos = $Db->select(array(
    'table' => 'imaxes',
    'sort' => array('portada DESC'),
    'conditions' => array(
        'activo' => 1,
        'avistamentos.especies.id' => $especie['id']
    ),
    'add_tables' => array(
        array(
            'table' => 'imaxes_tipos',
            'fields' => '*',
            'limit' => 1,
            'conditions' => array(
                'activo' => 1
            )
        ),
        array(
            'table' => 'avistamentos',
            'fields' => '*',
            'limit' => 1,
            'sort' => 'data_alta DESC',
            'conditions' => array(
                'especies.id' => $especie['id'],
                'activo' => 1
            )
        )
    )
));

$backups = $Data->execute('get-backups.php', array(
    'limit' => 100,
    'conditions' => array(
        'related_table' => 'especies',
        'related_id' => $especie['id']
    )
));

$grupos = $Db->select(array(
    'table' => 'grupos',
    'fields' => '*',
    'sort' => 'nome ASC',
    'conditions' => array(
        'activo' => 1
    )
));

$usuarios = $Db->select(array(
    'table' => 'usuarios',
    'fields' => '*',
    'conditions' => array(
        'especies(vixiar).id' => $especie['id']
    )
));

$tables = $Config->tables[getDatabaseConnection()];
$licenzas = $tables['imaxes']['licenza']['values'];

$imaxes = $especie['imaxes'] = $Db->select(array(
    'table' => 'imaxes',
    'fields' => '*',
    'sort' => array('portada DESC'),
    'conditions' => array(
        'especies.id' => $especie['id'],
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

// Imaxes de avistamentos
foreach ($imaxesAvistamentos as $imaxe) {
    $imaxe['related'] = 'avistamento';
    $imaxe['related_url'] = $imaxe['avistamentos']['url'];
    $imaxes[] = $imaxe;
}

$imaxes_tipos = $Db->select(array(
    'table' => 'imaxes_tipos',
    'fields' => '*',
    'sort' => 'nome ASC',
    'conditions' => array(
        'reinos.id' => $especie['reinos']['id'],
        'activo' => 1
    )
));

$proteccions_tipos = $Db->select(array(
    'table' => 'proteccions_tipos',
    'sort' => 'nome ASC',
    'conditions' => array(
        'activo' => 1
    )
));

$relacionadas = array();

if ($especie['subespecie'] || $especie['variedade']) {
    $especieTipo = $Db->select(array(
        'table' => 'especies',
        'limit' => 1,
        'conditions' => array(
            'especies.especies-child.id' => $especie['id']
        )
    ));   
}

$relacionadas = array_merge($relacionadas, $Db->select(array(
    'table' => 'especies',
    'conditions' => array(
        'especies.especies-parent.id' => $especieTipo['id'] ?: $especie['id'],
        'especies.id !=' => $especie['id']
    )
)));

$comentarios = $Db->select(array(
    'table' => 'comentarios',
    'fields' => '*',
    'sort' => 'data DESC',
    'conditions' => array(
        'especies.id' => $especie['id'],
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

//TODO: Imaxes de ameazas
$ficha = $especie['descricion'] || $especie['cromosomas'] || $especie['distribucion'] || $especie['habitat'] || $especie['poboacion'] || $especie['conservacion'];
$info = $ficha || $especie['conservacion'] || $especie['observacions'] || $especie['bibliografia'];

$Html->meta('title', $especie['nome']);
$Html->meta('description', $especie['descricion']);

if ($imaxes) {
    $Html->meta('image', fileWeb('uploads|'.$imaxes[0]['imaxe']));
}
