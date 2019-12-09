<?php
defined('ANS') or die();

function showError ($message, $type = 'ko', $redirects = null) {
    global $Vars;

    if ($Vars->var['origin'] === 'iframe') {
        if ($type === 'ko') {
            $data = json_encode(array(
                'result' => 'ko',
                'message' => $message
            ));

            echo '<script>';
            echo 'window.parent.postMessage(\''.str_replace('\\"', "\\'", $data).'\', window.location.origin);';
            echo '</script>';

            die();
        }
    } else {
        $Vars->message($message, $type);

        if ($redirect !== null) {
            referer(path($redirect));
        } else if ($type === 'ko') {
            return false;
        }
    }
}

function getEspecieNome($especie) {
    $nome = $especie['nome_cientifico'] . ($especie['autor'] ? ' ' . $especie['autor'] : '');

    if ($especie['subespecie']) {
        $nome .= ' subsp. ' . $especie['subespecie'] . ($especie['subespecie_autor'] ? ' ' . $especie['subespecie_autor'] : '');
    }

    if ($especie['variedade']) {
        $nome .= ' var. ' . $especie['variedade'] . ($especie['variedade_autor'] ? ' ' . $especie['variedade_autor'] : '');
    }

    return $nome;
}

function myTrimArray ($array)
{
    if (!is_array($array)) {
        return trim(str_replace('&nbsp;', '', $array));
    }
 
    return array_map('myTrimArray', $array);
}

if (empty($user)) {
    return showError(__('Sentímolo, pero este contido é só accesible para usuarios rexistrados.'), 'ko', '');
}

include ($Data->file('acl-especie-editar.php'));

if ($especie['bloqueada']) {
    return showError(__('Esta especie está bloqueada. A súa edición está deshabilitada.'), 'ko');
}

$f = myTrimArray($Vars->var);

if (empty($f['especies']['nome_cientifico']) || empty($f['especies']['autor'])) {
    return showError(__('O nome e o autor da especie non poden quedar baleiros.'), 'ko');
}

if (empty($f['grupos']) || empty($f['clases']) || empty($f['ordes']) || empty($f['familias']) || empty($f['xeneros'])) {
    return showError(__('A categorización da especie non pode quedar baleira.'), 'ko');
}

$conditionsAnterior = array(
    'nome_cientifico' => $f['especies']['nome_cientifico'],
    'subespecie' => $f['especies']['subespecie'],
    'variedade' => $f['especies']['variedade']
);

if ($especie) {
    $conditionsAnterior['id !='] = $especie['id'];
}
   
$anterior = $Db->select(array(
    'table' => 'especies',
    'limit' => 1,
    'conditions' => $conditionsAnterior
));

if ($anterior) {
    return showError(__('Xa existe unha especie con ese nome na base de datos, comprobe o nome ou a subespecie'), 'ko');
}

$action = $especie ? 'update' : 'insert';

$query = array(
    'table' => 'especies',
    'data' => array(),
    'limit' => 1,
    'conditions' => array(
        'id' => $especie['id']
    ),
    'relate' => array()
);

$fields = array_keys($Db->getTable('especies')->formats);

foreach ($fields as $field) {
    if (($f['especies'][$field] === null) || strstr($field, 'id_')) {
        continue;
    }

    $query['data'][$field] = $f['especies'][$field];
}

$query['data']['nome'] = $nome = getEspecieNome($f['especies']);

if ($especie) {
    if ($nome !== $especie['nome']) {
        $query['data']['validada'] = 0;
    }

    unset($query['data']['estado']);
} else {
    $query['data']['url'] = $query['data']['nome'];
    $query['data']['activo'] = 1;
    $query['data']['data_alta'] = date('Y-m-d H:i:s');
}

if ($especie && $Acl->check('action', 'especie-validar')) {
    $query['data']['validada'] = $f['especies']['validada'];

    if ($f['especies']['validada']) {
        $query['relate'][] = array(
            'table' => 'usuarios',
            'name' => 'validador',
            'limit' => 1,
            'conditions' => array(
                'id' => $user['id']
            )
        );
    }
}

foreach (array('grupos', 'clases', 'ordes', 'familias', 'xeneros') as $relation) {
    if ($f[$relation]) {
        $query['relate'][] = array(
            'table' => $relation,
            'conditions' => array(
                'url' => $f[$relation]
            ),
            'limit' => 1
        );
    }
}

$query['relate'][] = array(
    'table' => 'reinos',
    'conditions' => array(
        'clases.url' => $f['clases']
    ),
    'limit' => 1
);

$query['relate'][] = array(
    'table' => 'filos',
    'conditions' => array(
        'clases.url' => $f['clases']
    ),
    'limit' => 1
);

if (empty($especie['usuarios_autor']['id'])) {
    $query['relate'][] = array(
        'table' => 'usuarios',
        'name' => 'autor',
        'limit' => 1,
        'conditions' => array(
            'id' => $user['id']
        )
    );

    $query['relate'][] = array(
        'table' => 'usuarios',
        'name' => 'vixiar',
        'limit' => 1,
        'conditions' => array(
            'id' => $user['id']
        )
    );
}

if ($f['especies']['subespecie'] || $f['especies']['variedade']) {
    $especieTipo = $Db->select(array(
        'table' => 'especies',
        'limit' => 1,
        'conditions' => array(
            'nome_cientifico' => $f['especies']['nome_cientifico'],
            'subespecie' => '',
            'variedade' => '',
            'id !=' => $especie['id']
        )
    ));
    
    if (empty($especieTipo)) {
        $relate = $query['relate'];
        $relate[] = array(
            'table' => 'usuarios',
            'name' => 'autor',
            'conditions' => array(
                'id' => $user['id']
            ),
            'limit' => 1
        );

        $res = $Db->insert(array(
            'table' => 'especies',
            'data' => array(
                'url' => $f['especies']['nome_cientifico'] . ' ' . $f['especies']['autor'],
                'nome' => $f['especies']['nome_cientifico'] . ' ' . $f['especies']['autor'],
                'nome_cientifico' => $f['especies']['nome_cientifico'],
                'autor' => $f['especies']['autor'],
                'data_alta' => date('Y-m-d H:i:s'),
                'activo' => 1
            ),
            'relate' => $relate
        ));

        if (empty($res)) {
            return showError($Errors->getList(), 'ko');
        }

        $especieTipo['id'] = $res;
    }

    $query['relate'][] = array(
        'table' => 'especies',
        'conditions' => array(
            'id' => $especieTipo['id']
        ),
        'limit' => 1
    );
}

$query['idioma'] = $especie['idioma'] ?: LANGUAGE;

$id_especie = $Db->$action($query);

if (empty($id_especie)) {
    return showError($Errors->getList(), 'ko');
}

$Db->unrelate(array(
    'tables' => array(
        array(
            'table' => 'especies',
            'limit' => 1,
            'conditions' => array(
                'id' => $id_especie
            )
        ),
        array(
            'table' => 'proteccions_tipos',
            'conditions' => 'all'
        )
    )
));

if ($f['especies']['proteccions'] && $f['especies']['protexida']) {
    $Db->relate(array(
        'tables' => array(
            array(
                'table' => 'especies',
                'limit' => 1,
                'conditions' => array(
                    'id' => $id_especie
                )
            ),
            array(
                'table' => 'proteccions_tipos',
                'conditions' => array(
                    'id' => explode(",", $f['especies']['proteccions'])
                )
            )
        )
    ));
}

if ($action === 'update') {
    $Data->execute('actions|sub-backups.php', array(
        'table' => 'especies',
        'id' => $especie['id'],
        'action' => 'editar',
        'content' => $especie
    ));

    $Vars->message(__('A especie foi actualizada correctamente.'), 'ok');
} else {
    $Data->execute('actions|sub-logs.php', array(
        'table' => 'especies',
        'id' => $id_especie,
        'action' => 'crear'
    ));

    $Vars->message(__('A especie foi data de alta correctamente.'), 'ok');
}

$Data->execute('actions|sub-imaxes.php', array(
    'table' => 'especies',
    'id' => ($especies['id'] ?: $id_especie),
    'imaxes' => $f['imaxes']
));

if (($action === 'update') && ($especie['nivel_ameaza'] !== $f['especies']['nivel_ameaza'])) {
    $niveles = array(
        '1' => __('baixo'),
        '2' => __('medio'),
        '3' => __('alto')
    );

    $Data->execute('actions|mail.php', array(
        'vixiantes' => array(
            'especies(vixiar).id' => $especie['id']
        ),
        'text' => array(
            'code' => 'mail-especie-nivel-ameaza',
            'especie' => $especie['nome'],
            'nivel-antes' => $niveles[$especie['nivel_ameaza']],
            'nivel-agora' => $niveles[$f['especies']['nivel_ameaza']]
        )
    ));
}

$especie = $Db->select(array(
    'table' => 'especies',
    'limit' => 1,
    'conditions' => array(
        'id' => ($especies['id'] ?: $id_especie)
    ),
    'add_tables' => array(
        array(
            'table' => 'imaxes',
            'sort' => array('portada DESC'),
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

if ($Vars->var['origin'] === 'iframe') {
    $data = json_encode(array(
        'result' => 'ok',
        'especie' => $especie
    ));
    
    echo '<script>';
    echo 'window.parent.postMessage(\''.str_replace('\\"', "\\'", $data).'\', window.location.origin);';
    echo '</script>';

    die();
} else {
    redirect(path('especie', $especie['url']));
}
