<?php
defined('ANS') or die();

if (empty($user)) {
    $Vars->message(__('Sentímolo, pero este contido é só accesible para usuarios rexistrados.'), 'ko');
    referer(path(''));
}

$f = $Vars->var;

include ($Data->file('acl-avistamento-editar.php'));

$territorios = $Db->select(array(
    'table' => 'territorios',
    'fields' => 'url',
    'add_tables' => array(
        array(
            'table' => 'provincias',
            'fields' => 'nome',
            'add_tables' => array(
                array(
                    'table' => 'concellos',
                    'fields' => 'nome'
                )
            )
        )
    )
));

$id_territorio = $id_provincia = $id_concello = 0;

foreach ($territorios as $cada) {
    if ($cada['url'] === $f['territorio']) {
        $id_territorio = $cada['id'];

        foreach ($cada['provincias'] as $cada) {
            if ($cada['nome']['url'] === $f['provincia']) {
                $id_provincia = $cada['id'];

                foreach ($cada['concellos'] as $cada) {
                    if ($cada['nome']['url'] === $f['concello']) {
                        $id_concello = $cada['id'];
                        break;
                    }
                }

                break;
            }
        }

        break;
    }
}

if (empty($f['xeolocalizacion'])) {
    $Vars->message(__('A información de referencias xeográfica e obrigatoria'), 'ko');
    return false;   
}

if (empty($f['especies']['url']) && empty($f['desconecida'])) {
    $Vars->message(__('Debes indicar unha especie. Se non a coñeces, debes marcar a opción de "Non sei exactamente o que é"'), 'ko');
    return false;
}

if (empty($f['referencias_tipos']['id'])) {
    $Vars->message(__('Debes indicar a fonte dos datos'), 'ko');
    return false;
}

$action = $avistamento ? 'update' : 'insert';

if ($f['especies']['url']) {
    $especie = $Db->select(array(
        'table' => 'especies',
        'fields' => array('url', 'nome'),
        'limit' => 1,
        'conditions' => array(
            'url' => $f['especies']['url']
        )
    ));
} else {
    $especie = array();
}

if ($especie && ($avistamento['especies']['id'] === $especie['id'])) {
    $url = $avistamento['url'];
} else if ($especie) {
    $url = $especie['url'];
} else {
    $url = '';
}

$fields = array(
    'url' => $url,
    'nome' => ($especie ? $especie['nome'] : __('Sen identificar')),
    'localidade' => $f['avistamentos']['localidade'],
    'nome_zona' => $f['avistamentos']['nome_zona'],
    'observacions' => $f['avistamentos']['observacions'],
    'outros_observadores' => $f['avistamentos']['outros_observadores'],
    'autoctona' => $f['avistamentos']['autoctona'],
    'invasora' => $f['avistamentos']['invasora'],
    'referencia' => $f['avistamentos']['referencia'],
    'data_alta' => ($avistamento['data_alta'] ?: date('Y-m-d H:i:s')),
    'idcoleccion' => $f['avistamentos']['idcoleccion'],
    'colector' => $f['avistamentos']['colector'],

    'substrato_xeoloxico' => $f['avistamentos']['substrato_xeoloxico'],
    'fenoloxia_froito' => $f['avistamentos']['fenoloxia_froito'],
    'fenoloxia_individuos' => $f['avistamentos']['fenoloxia_individuos'],
    'uso_solo' => $f['avistamentos']['uso_solo'],
    'xestion_ambiental' => $f['avistamentos']['xestion_ambiental'],
    'substrato_xeoloxico' => $f['avistamentos']['substrato_xeoloxico'],
    'estado_conservacion' => $f['avistamentos']['estado_conservacion'],
    'abundancia' => $f['avistamentos']['abundancia'],
    'distribucion' => $f['avistamentos']['distribucion'],
    'uso_activo' => $f['avistamentos']['uso_activo'],
    'xestion_ambiental' => $f['avistamentos']['xestion_ambiental'],
    'observacions_conservacion' => $f['avistamentos']['observacions_conservacion'],
    'banco_xeoplasma' => $f['avistamentos']['banco_xeoplasma'],
    'numero_colleita' => $f['avistamentos']['numero_colleita'],
    'xermoplasma_herbario' => $f['avistamentos']['xermoplasma_herbario'],
    'xermoplasma_herbario_numero' => $f['avistamentos']['xermoplasma_herbario_numero'],
    'arquivo_gps' => $f['avistamentos']['arquivo_gps'],
    'profundidade_auga' => $f['avistamentos']['profundidade_auga'],
    'pendente' => $f['avistamentos']['pendente'],
    'orientacion' => $f['avistamentos']['orientacion'],
    'metodo_muestreo' => $f['avistamentos']['metodo_muestreo'],
    'adultas_localizadas' => $f['avistamentos']['adultas_localizadas'],
    'adultas_mostreadas' => $f['avistamentos']['adultas_mostreadas'],
    'area_mostreada' => $f['avistamentos']['area_mostreada'],
    'area_ocupacion' => $f['avistamentos']['area_ocupacion'],
    'fenoloxia_froito' => $f['avistamentos']['fenoloxia_froito'],
    'procedencia_semente' => $f['avistamentos']['procedencia_semente'],
    'tipo_vexetacion' => $f['avistamentos']['tipo_vexetacion'],
    'textura_solo' => $f['avistamentos']['textura_solo'],
    'sexo' => $f['avistamentos']['sexo'],
    'fase' => $f['avistamentos']['fase'],
    'migracion' => $f['avistamentos']['migracion'],
    'climatoloxia' => $f['avistamentos']['climatoloxia'],
    'observacions_xermoplasma' => $f['avistamentos']['observacions_xermoplasma'],

    'distancia_umbral' => $f['avistamentos']['distancia_umbral'],
    'definicion_individuo' => $f['avistamentos']['definicion_individuo'],
    'coroloxia_herbario' => $f['avistamentos']['coroloxia_herbario'],
    'coroloxia_herbario_numero' => $f['avistamentos']['coroloxia_herbario_numero'],
    'numero_exemplares' => $f['avistamentos']['numero_exemplares'],
    'area_presencia_shapefile' => $f['avistamentos']['area_presencia_shapefile'],
    'tipo_censo' => $f['avistamentos']['tipo_censo'],
    'superficie_ocupacion' => $f['avistamentos']['superficie_ocupacion'],
    'densidade_ocupacion' => $f['avistamentos']['densidade_ocupacion'],
    'area_prioritaria' => $f['avistamentos']['area_prioritaria'],
    'area_potencial' => $f['avistamentos']['area_potencial'],
    'superficie_mostreada' => $f['avistamentos']['superficie_mostreada'],
    'individuos_contados' => $f['avistamentos']['individuos_contados'],
    'superficie_potencial' => $f['avistamentos']['superficie_potencial'],
    'densidade' => $f['avistamentos']['densidade'],
    'individuos_estimados' => $f['avistamentos']['individuos_estimados'],
    'observacions_coroloxia' => $f['avistamentos']['observacions_coroloxia'],

    'validado' => 0,
    'activo' => 1
);

if ($f['avistamentos']['data_observacion'] && strtotime($f['avistamentos']['data_observacion']) > 0) {
    $fields['data_observacion'] = date('Y-m-d H:i:s', strtotime($f['avistamentos']['data_observacion']));
}

if ($f['desconecida']) {
    $fields['posible'] = $f['avistamentos']['posible'];
} else {
    $fields['posible'] = '';
}

$relate = array(
    array(
        'table' => 'usuarios',
        'name' => 'autor',
        'limit' => 1,
        'conditions' => array(
            'id' => ($avistamento['usuarios_autor']['id'] ?: $user['id'])
        )
    ),
    array(
        'table' => 'usuarios',
        'name' => 'vixiar',
        'limit' => 1,
        'conditions' => array(
            'id' => $user['id']
        )
    )
);

if ($avistamento && $Acl->check('action', 'avistamento-validar')) {
    $fields['validado'] = $f['avistamentos']['validado'];

    if ($f['avistamentos']['validado']) {
        $relate[] = array(
            'table' => 'usuarios',
            'name' => 'validador',
            'limit' => 1,
            'conditions' => array(
                'id' => $user['id']
            )
        );
    }
}

$query = array(
    'table' => 'avistamentos',
    'data' => $fields,
    'limit' => 1,
    'conditions' => array(
        'id' => $avistamento['id']
    ),
    'relate' => $relate
);

$id_avistamento = $Db->$action($query);

if (empty($id_avistamento)) {
    $Vars->message($Errors->getList(), 'ko');
    return false;
}

if ($action === 'insert') {
    $Data->execute('actions|sub-logs.php', array(
        'table' => 'avistamentos',
        'id' => $id_avistamento,
        'action' => 'crear'
    ));

    $avistamento = array('id' => $id_avistamento);
} else {
    $Data->execute('actions|sub-backups.php', array(
        'table' => 'avistamentos',
        'id' => $avistamento['id'],
        'action' => 'editar',
        'content' => $avistamento
    ));

    if ($Acl->check('action', 'avistamento-validar')) {
        if ($f['avistamentos']['validado'] && ($avistamento['validado'] != $f['avistamentos']['validado'])) {
            $Data->execute('actions|sub-logs.php', array(
                'table' => 'avistamentos',
                'id' => $avistamento['id'],
                'action' => 'validar',
                'public' => 0
            ));
        }
    }
}

$relate = array(
    array(
        'exists' => $Vars->var['grupo'],
        'table' => 'grupos',
        'conditions' => array(
            'url' => $Vars->var['grupo']
        ),
        'limit' => 1
    ),
    array(
        'exists' => $especie['id'],
        'table' => 'especies',
        'conditions' => array(
            'id' => $especie['id']
        ),
        'limit' => 1
    ),
    array(
        'exists' => $id_territorio,
        'table' => 'paises',
        'conditions' => array(
            'territorios.id' => $id_territorio
        ),
        'limit' => 1
    ),
    array(
        'exists' => $id_territorio,
        'table' => 'territorios',
        'conditions' => array(
            'id' => $id_territorio
        ),
        'limit' => 1
    ),
    array(
        'exists' => $id_provincia,
        'table' => 'provincias',
        'conditions' => array(
            'id' => $id_provincia
        ),
        'limit' => 1
    ),
    array(
        'exists' => $id_concello,
        'table' => 'concellos',
        'conditions' => array(
            'id' => $id_concello
        ),
        'limit' => 1
    ),
    array(
        'exists' => $f['acompanhantes'],
        'table' => 'especies',
        'name' => 'acompanhantes',
        'conditions' => array(
            'url' => explode(',', $f['acompanhantes'])
        )
    ),
    array(
        'exists' => $f['ameazas_tipos_nivel1']['id'],
        'name' => 'nivel1',
        'table' => 'ameazas_tipos',
        'conditions' => array(
            'id' => $f['ameazas_tipos_nivel1']['id']
        )
    ),
    array(
        'exists' => $f['ameazas_tipos_nivel2']['id'],
        'name' => 'nivel2',
        'table' => 'ameazas_tipos',
        'conditions' => array(
            'id' => $f['ameazas_tipos_nivel2']['id']
        )
    ),
    array(
        'exists' => $f['proxectos']['id'],
        'table' => 'proxectos',
        'conditions' => array(
            'id' => $f['proxectos']['id'],
            'usuarios.id' => $user['id']
        )
    ),
    array(
        'exists' => $f['habitats_tipos']['id'],
        'table' => 'habitats_tipos',
        'conditions' => array(
            'id' => $f['habitats_tipos']['id']
        )
    ),
    array(
        'exists' => $f['referencias_tipos']['id'],
        'table' => 'referencias_tipos',
        'conditions' => array(
            'id' => $f['referencias_tipos']['id']
        )
    ),
);

foreach ($relate as $relation) {
    $Db->unrelate(array(
        'name' => $relation['name'],
        'tables' => array(
            array(
                'table' => 'avistamentos',
                'conditions' => array(
                    'id' => $avistamento['id']
                )
            ),
            array(
                'table' => $relation['table'],
                'conditions' => 'all'
            )
        )
    ));

    if (empty($relation['exists'])) {
        continue;
    }

    $Db->relate(array(
        'name' => $relation['name'],
        'tables' => array(
            array(
                'table' => 'avistamentos',
                'conditions' => array(
                    'id' => $avistamento['id']
                ),
                'limit' => 1
            ),
            array(
                'table' => $relation['table'],
                'conditions' => $relation['conditions'],
                'limit' => $relation['limit']
            )
        )
    ));
}

//-- PUntos
//----------------------

$xeolocalizacion = $f['xeolocalizacion'];

foreach ($xeolocalizacion as $punto) {
    $action = $punto['action'];

    if ($action === 'deleted') {
        $res = $Db->delete(array(
            'table' => 'puntos',
            'conditions' => array(
                'id' => $punto['id']
            )
        ));

        if (empty($res)) {
            $Vars->message($Errors->getList(), 'ko');
            return false;
        }

        $res = $Db->queryResult("
            delete from gis_points
            where id_puntos = " . $punto['id'] . "
        ");

        $res = $Db->queryResult("
            delete from gis_polygons
            where id_puntos = " . $punto['id'] . "
        ");
    } else if ($action) {
        $tipo = 4; // Punto

        if ($punto['tipo'] === 'mgrs') {
            $centroide = getMGRSCentroid($punto['mgrs']);
            $tipo = getMGRSCentroidType(trim($punto['mgrs']));

            $latLong = Coordinates::mgrsToLatLong($centroide, strtoupper($punto['datum']));

            $data = array(
                'datum' => $punto['datum'],
                'mgrs' => $punto['mgrs'],
                'tipo' => $punto['tipo'],
                'latitude' => round($latLong['lat'], 14),
                'lonxitude' => round($latLong['lng'], 14),
                'altitude' => ((integer)$punto['altitude'])
            );
        } else if ($punto['tipo'] === 'utm') {
            $tipo = 4; // Punto

            if ($punto['datum'] == 'ed50-spain-a-portugal') {
                $latLong = Coordinates::utmToLatLong(($punto['utm_x'] - 132), ($punto['utm_y'] - 115), $punto['utm_fuso'], ($punto['utm_sur'] ? 'S' : 'N'), strtoupper($punto['datum']));
            } else {
                $latLong = Coordinates::utmToLatLong($punto['utm_x'], $punto['utm_y'], $punto['utm_fuso'], ($punto['utm_sur'] ? 'S' : 'N'), strtoupper($punto['datum']));
            }

            $data = array(
                'datum' => $punto['datum'],
                'utm_fuso' => $punto['utm_fuso'],
                'utm_sur' => $punto['utm_sur'] ? 1 : 0,
                'utm_x' => $punto['utm_x'],
                'utm_y' => $punto['utm_y'],
                'tipo' => $punto['tipo'],
                'latitude' => round($latLong['lat'], 14),
                'lonxitude' => round($latLong['lng'], 14),
                'altitude' => (integer)$punto['altitude']
            );
        } else {
            $tipo = 4; // Punto

            $data = array(
                'datum' => $punto['datum'],
                'latitude' => $punto['latitude'],
                'lonxitude' => $punto['lonxitude'],
                'tipo' => $punto['tipo'],
                'altitude' => (integer)$punto['altitude']
            );
        }

        if ($tipo == 1 || $tipo == 2) {

            $tableGis = 'gis_polygons';
            $field = 'polygon';

            $polygonPoints = Coordinates::getCentroidCorners($punto['latitude'], $punto['lonxitude'], $tipo == 1 ? 1000: 10000);

            $polygonString = array();

            foreach($polygonPoints as $polPoint) {
                $polygonString[] = $polPoint['latitude'] . ' ' . $polPoint['longitude'];
            }

            $polygonString[] = trim($polygonPoints[0]['latitude'] . ' ' . $polygonPoints[0]['longitude']);

            if ($polygonString) {
                $shape = "POLYGON((" . join(', ', $polygonString) . "))";
            } else {
                $shape = '';
            }
        } else {
            $tableGis = 'gis_points';
            $field = 'point';

            if ($punto['latitude'] && $punto['lonxitude']) {
                $shape = "POINT(" . $punto['latitude']  . " " . $punto['lonxitude'] . ")";
            } else {
                $shape = '';
            }
        }

        if ($action === 'modified') {
            $idPunto = $Db->update(array(
                'table' => 'puntos',
                'data' => $data,
                'conditions' => array(
                    'id' => $punto['id']
                )
            ));

            if ($idPunto && $shape) {
                $res = $Db->queryResult("
                    update " . $tableGis . "
                    set " . $field . " = GEOMFROMTEXT('" . $shape . "')
                    where id_puntos = " . $idPunto . "
                ");
            }   
        } else {
            $idPunto = $Db->insert(array(
                'table' => 'puntos',
                'data' => $data
            ));

            if ($idPunto && $shape) {
                $res = $Db->queryResult("
                    insert into " . $tableGis . "
                    (id_puntos, " . $field . ")
                    values
                    (" . $idPunto . ", GEOMFROMTEXT('" . $shape . "'))
                ");
            }
        }

        if (empty($idPunto)) {
            $Vars->message($Errors->getList(), 'ko');
            return false;
        }

        $res = $Db->relate(array(
            'tables' => array(
                array(
                    'table' => 'puntos',
                    'conditions' => array(
                        'id' => $idPunto
                    )
                ),
                array(
                    'table' => 'datums',
                    'conditions' => array(
                        'url' => $punto['datum']
                    )
                ),
            )
        ));

        if (empty($res)) {
            $Vars->message($Errors->getList(), 'ko');
            return false;
        }

        $res = $Db->relate(array(
            'tables' => array(
                array(
                    'table' => 'puntos',
                    'conditions' => array(
                        'id' => $idPunto
                    )
                ),
                array(
                    'table' => 'puntos_tipos',
                    'conditions' => array(
                        'numero' => $tipo
                    )
                ),
            )
        ));

        if (empty($res)) {
            $Vars->message($Errors->getList(), 'ko');
            return false;
        }

        $res = $Db->relate(array(
            'tables' => array(
                array(
                    'table' => 'avistamentos',
                    'conditions' => array(
                        'id' => $avistamento['id']
                    )
                ),
                array(
                    'table' => 'puntos',
                    'conditions' => array(
                        'id' => $idPunto
                    )
                )
            )
        ));

        if (empty($res)) {
            $Vars->message($Errors->getList(), 'ko');
            return false;
        }
    }
}

$url = $Db->select(array(
    'table' => 'avistamentos',
    'fields' => 'url',
    'limit' => 1,
    'conditions' => array(
        'id' => $avistamento['id']
    )
));

if (($action === 'update')
&& $avistamento['usuarios_autor']['notificacions']
&& ($avistamento['usuarios_autor']['id'] !== $user['id'])
&& ($avistamento['posible'] && $especie)) {
    $Data->execute('actions|mail.php', array(
        'log' => array(
            'action' => 'avistamento-identificado',
            'table' => 'avistamentos',
            'id' => $avistamento['id'],
            'table2' => 'especies',
            'id2' => $especie['id']
        ),
        'vixiantes' => array(
            'avistamentos(vixiar).id' => $avistamento['id']
        ),
        'text' => array(
            'code' => 'mail-avistamento-identificacion',
            'user' => $user['nome']['title'],
            'date' => $Html->time($avistamento['data_observacion'], '', 'absolute'),
            'especie' => $especie['nome'],
            'link' => absolutePath('avistamento', $url['url'])
        )
    ));

    $Data->execute('actions|mail.php', array(
        'log' => array(
            'action' => 'avistamento-identificado',
            'table' => 'avistamentos',
            'id' => $avistamento['id'],
            'table2' => 'especies',
            'id2' => $especie['id']
        ),
        'vixiantes' => array(
            'especies(vixiar).id' => $especie['id']
        ),
        'text' => array(
            'code' => 'mail-avistamento-identificacion',
            'user' => $user['nome']['title'],
            'date' => $Html->time($avistamento['data_observacion'], '', 'absolute'),
            'especie' => $especie['nome'],
            'link' => absolutePath('avistamento', $url['url'])
        )
    ));
}

if (($action === 'insert') && $especie) {
    $Data->execute('actions|mail.php', array(
        'log' => array(
            'action' => 'nova-relacion',
            'table' => 'especies',
            'id' => $especie['id'],
            'table2' => 'avistamentos',
            'id2' => $avistamento['id']
        ),
        'vixiantes' => array(
            'especies(vixiar).id' => $especie['id']
        ),
        'text' => array(
            'code' => 'mail-avistamento-especie',
            'especie' => $especie['nome'],
            'link' => absolutePath('avistamento', $url['url'])
        )
    ));
}

// -------------------

$Data->execute('actions|sub-imaxes.php', array(
    'table' => 'avistamentos',
    'id' => $avistamento['id'],
    'imaxes' => $f['imaxes']
));

redirect(path('avistamento', $url['url']));
