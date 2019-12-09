<?php
defined('ANS') or die();

$Data->execute('acl-action.php', array('action' => 'importar-observacions-gardar'));

if (empty($Vars->var['csv'])) {
    return false;
}

$csv = unserialize(base64_decode($Vars->var['csv']));

/*pre($Vars->var['especie']);
pre($csv);
die();*/

if (empty($csv)) {
    return false;
}

set_time_limit(0);
ini_set('max_execution_time', 0);
ini_set('memory_limit', '1024M');

use ANS\PHPCan\Loader;

Loader::registerComposer(SCENE_PATH.'libs/willdurand/Geocoder/vendor/');
Loader::registerComposer(SCENE_PATH.'libs/Math/GeographicPoint/');

$Geocoder = new \Geocoder\Geocoder();
$Geocoder->registerProviders(array(
//    new \Geocoder\Provider\BingMapsProvider(
//        new \Geocoder\HttpAdapter\CurlHttpAdapter()
//    ),
    new \Geocoder\Provider\OpenStreetMapsProvider(
        new \Geocoder\HttpAdapter\CurlHttpAdapter()
    ),
    new \Geocoder\Provider\GoogleMapsProvider(
        new \Geocoder\HttpAdapter\CurlHttpAdapter()
    ),
    new \Geocoder\Provider\YandexProvider(
        new \Geocoder\HttpAdapter\CurlHttpAdapter()
    )
));

$resultado = array(
    'erros' => array(),
    'avistamentos' => 0,
    'puntos' => 0,
    'especies' => array()
);

$idImportacion = $Db->insert(array(
    'table' => 'importacions',
    'data' => array(
        'data' => date('Y-m-d H:i:s'),
        'activo' => 1
    ),
    'relate' => array(
        array(
            'table' => 'usuarios',
            'limit' => 1,
            'conditions' => array(
                'id' => $user['id']
            )
        )
    )
));

$importacion = $Db->select(array(
    'table' => 'importacions',
    'limit' => 1,
    'conditions' => array(
        'id' => $idImportacion
    )
));

$especies = $Vars->var['especie'];
$avistamentosInsertados = 0;

foreach($csv as $code => $fila) {

    // Si los codigos no coinciden es que hay otra especie seleccionada
    if ($especies[$code]['url'] !== $code) {

        // Si no hay especie asocaida la creamos
        if (!$especies[$code]['url'] || $especies[$code]['url'] === 'especie-nova') {

            $idEspecie = $Db->insert(array(
                'table' => 'especies',
                'data' => array(
                    'url' => $code,
                    'nome' => $fila['nome'],
                    'data_alta' => date('Y-m-d H:i:s'),
                    'activo' => 1
                ),
                'relate' => array(
                    array(
                        'table' => 'importacions',
                        'limit' => 1,
                        'conditions' => array(
                            'id' => $importacion['id']
                        )
                    ),
                    array(
                        'table' => 'usuarios',
                        'name' => 'autor',
                        'conditions' => array(
                            'id' => $user['id']
                        )
                    )
                )
            ));

            if (!$idEspecie) {
                $resultado['erros'][$code] = __('Ocorreu un erro o dar de alta a especie "%s". %s', $fila['nome'], '<p>' . join('</p><p>', $Errors->getList()));
                continue;
            }

            $resultado['especies'][] = $fila['nome'];

            list($nomeXenero) = preg_split('/ /', $fila['nome']);

            $xenero = $Db->select(array(
                'table' => 'xeneros',
                'limit' => 1,
                'conditions' => array(
                    'nome' => $nomeXenero
                )
            ));

            if (!$xenero) {
                $resultado['erros'][$code] = __('Ocorreu un erro o asignar o xenero "%s" a nova especie "%s". %s', $nomeXenero, $fila['nome'], '<p>' . join('</p><p>', $Errors->getList()));
                continue;
            }

            // Estbalcemos o xenero
            $relateXenero = $Db->relate(array(
                'tables' => array(
                    array(
                        'table' => 'especies',
                        'conditions' => array(
                            'id' => $idEspecie
                        )
                    ),
                    array(
                        'table' => 'xeneros',
                        'conditions' => array(
                            'url' => $xenero['url']
                        )
                    )
                )
            ));

            // Establcemos a familia
            $relateXenero = $Db->relate(array(
                'tables' => array(
                    array(
                        'table' => 'especies',
                        'conditions' => array(
                            'id' => $idEspecie
                        )
                    ),
                    array(
                        'table' => 'familias',
                        'conditions' => array(
                            'xeneros.url' => $xenero['url']
                        )
                    )
                )
            ));

            // Establcemos a orde
            $relateXenero = $Db->relate(array(
                'tables' => array(
                    array(
                        'table' => 'especies',
                        'conditions' => array(
                            'id' => $idEspecie
                        )
                    ),
                    array(
                        'table' => 'ordes',
                        'conditions' => array(
                            'familias.xeneros.url' => $xenero['url']
                        )
                    )
                )
            ));

            // Establcemos a clase
            $relateXenero = $Db->relate(array(
                'tables' => array(
                    array(
                        'table' => 'especies',
                        'conditions' => array(
                            'id' => $idEspecie
                        )
                    ),
                    array(
                        'table' => 'clases',
                        'conditions' => array(
                            'ordes.familias.xeneros.url' => $xenero['url']
                        )
                    )
                )
            ));

            // Establcemos o reino
            $relateXenero = $Db->relate(array(
                'tables' => array(
                    array(
                        'table' => 'especies',
                        'conditions' => array(
                            'id' => $idEspecie
                        )
                    ),
                    array(
                        'table' => 'reinos',
                        'conditions' => array(
                            'xeneros.url' => $xenero['url']
                        )
                    )
                )
            ));

            // Establcemos o reino
            $relateFilos = $Db->relate(array(
                'tables' => array(
                    array(
                        'table' => 'especies',
                        'conditions' => array(
                            'id' => $idEspecie
                        )
                    ),
                    array(
                        'table' => 'filos',
                        'conditions' => array(
                            'xeneros.url' => $xenero['url']
                        )
                    )
                )
            ));
            
            // Establcemos o reino
            $relateGrupos = $Db->relate(array(
                'tables' => array(
                    array(
                        'table' => 'especies',
                        'conditions' => array(
                            'id' => $idEspecie
                        )
                    ),
                    array(
                        'table' => 'grupos',
                        'conditions' => array(
                            'xeneros.url' => $xenero['url']
                        )
                    )
                )
            ));
            
            $especieSeleccionada = $Db->select(array(
                'table' => 'especies',
                'limit' => 1,
                'conditions' => array(
                    'id' => $idEspecie
                )
            ));

        } else {

            $especieSeleccionada = $Db->select(array(
                'table' => 'especies',
                'limit' => 1,
                'conditions' => array(
                    'url' => $especies[$code]['url']
                )
            ));
        }

        // Si hay seleccionado sinonimos actualizamos el nombre/sinonimos
        if ($especies[$code]['sinonimo']) {

            if ($especies[$code]['sinonimo'] === 'nome') {

                $dataSinonimos = array(
                    'sinonimos' => ($especieSeleccionada['sinonimos'] ? $especieSeleccionada['sinonimos'] . ', ' : '') . $especieSeleccionada['nome'],
                    'nome' => $fila['nome']
                );

            } else {
                $dataSinonimos = array(
                    'sinonimos' => ($especieSeleccionada['sinonimos'] ? $especieSeleccionada['sinonimos'] . ', ' : '') . $fila['nome']
                );
            }

            $updateEspecie = $Db->update(array(
                'table' => 'especies',
                'data' => $dataSinonimos,
                'conditions' => array(
                    'url' => $especieSeleccionada['url']
                )
            ));

             if (!$updateEspecie) {
                $resultado['erros'][$code] = __('Ocorreu un erro o establecer o sinonimo da especie. "%s". %s', $$especieSeleccionada['nome'], '<p>' . join('</p><p>', $Errors->getList()));
                continue;
            }   
        }
    } else {

        $especieSeleccionada = $Db->select(array(
            'table' => 'especies',
            'limit' => 1,
            'conditions' => array(
                'url' => $code
            )
        ));
    }

    // Creamos los avistamientos
    foreach ($fila['fichas'] as $codeFicha => $ficha) {

        try {

            $checksum = $ficha['checksum'];

            $existe = $Db->select(array(
                'table' => 'avistamentos',
                'fields' => 'id',
                'limit' => 1,
                'conditions' => array(
                    'checksum' => $checksum
                )
            ));

            if ($existe) {
                $resultado['erros'][$code] = $resultado['erros'][$code] ?: array();
                $resultado['erros'][$code][] = __('Ocorreu un erro o establecer o sinonimo da especie. "%s". %s', $$especieSeleccionada['nome'], '<p>' . join('</p><p>', $Errors->getList()));
                continue;
            }

            // Novos campos: outros_observadores, sexo, fase, migracion, especies_acompanhantes
            $sexo = null;
            $fase = null;
            $migracion = null;

            if ($especies[$codeFicha]['sexo']) {
                $sexo = $especies[$codeFicha]['sexo'];
            }
            if ($especies[$codeFicha]['fase']) {
                $fase = $especies[$codeFicha]['fase'];
            }
            if ($especies[$codeFicha]['migracion']) {
                $migracion = $especies[$codeFicha]['migracion'];
            }

            $id_avistamento = $Db->insert(array(
                'table' => 'avistamentos',
                'data' => array(
                    'url' => $especieSeleccionada['nome'],
                    'nome' => $especieSeleccionada['nome'],
                    'localidade' => $ficha['localidade'],
                    'referencia' => $ficha['tipo_referencia'],
                    'colector' => $ficha['colector'],
                    'observacions' => $ficha['observacions'],
                    'data_observacion' => $ficha['data_observacion'],
                    'outros_observadores' => $ficha['outros_observadores'],
                    'sexo' => $sexo,
                    'fase' => $fase,
                    'migracion' => $migracion,
                    'checksum' => $fila['checksum'],
                    'data_alta' => date('Y-m-d H:i:s'),
                    'activo' => 1
                ),
                'relate' => array(
                    array(
                        'table' => 'usuarios',
                        'name' => 'autor',
                        'conditions' => array(
                            'id' => $user['id']
                        )
                    ),
                    array(
                        'table' => 'especies',
                        'conditions' => array(
                            'id' => $especieSeleccionada['id']
                        )
                    ),
                    array(
                        'table' => 'importacions',
                        'limit' => 1,
                        'conditions' => array(
                            'id' => $importacion['id']
                        )
                    )
                )
            ));

            if (empty($id_avistamento)) {
                $resultado['erros'][$code] = $resultado['erros'][$code] ?: array();
                $resultado['erros'][$code][] = __('Ocorreu un erro o crear o avistamento. %s', '<p>' . join('</p><p>', $Errors->getList()));
                continue;
            }

            $resultado['avistamentos']++;

            foreach ($ficha['puntos'] as $punto) {

                $tipoPunto = $ficha['tipo'] ?: 4;
                $tipoFichaPunto = 'latlong';

                $punto['datum'] = getDatumCode($punto['datum']);

                if ($punto['utm_x'] && $punto['utm_y'] && $punto['fuso']) {
                    
                    $fuso = trim(strtoupper($punto['fuso']));

                    if (!preg_match('/([0-9]{1,2})\s?([A-Z])?/', $fuso, $matches)) {
                        $resultado['erros'][$code] = $resultado['erros'][$code] ?: array();
                        $resultado['erros'][$code][] = __('O punto %s non ten coordenadas validas', join(', ', $punto) , '<p>' . join('</p><p>', $Errors->getList()));
                        continue;
                    }

                    $punto['fuso'] = $matches[1];
                    $punto['utm_sur'] = $matches[2] === 'S' ? 1: 0;

                    $utm_sur = $punto['utm_sur'] ? 'S' : 'N';
                    $utm_x = $punto['utm_x'];
                    $utm_y = $punto['utm_y'];

                    $tipoFichaPunto = 'utm';

                    $latLong = Coordinates::utmToLatLong($utm_x, $utm_y, $punto['fuso'], $utm_sur, $punto['datum']);

                } else if ($punto['lat'] && $punto['long']) {
                    
                    $latLong = array('lat' => $punto['lat'], 'lng' => $punto['long']);
                    $tipoPunto = 4;

                } else if ($punto['mgrs']) {

                    $centroide = getMGRSCentroid($punto['mgrs']);
                    $tipoPunto = getMGRSCentroidType($punto['mgrs']);

                    $latLong = Coordinates::mgrsToLatLong($centroide, $punto['datum']);

                    $tipoFichaPunto = 'mgrs';

                } else {
                    $resultado['erros'][$code] = $resultado['erros'][$code] ?: array();
                    $resultado['erros'][$code][] = __('O punto %s non ten coordenadas validas', join(', ', $punto) , '<p>' . join('</p><p>', $Errors->getList()));
                    continue;
                }

                $query = array(
                    'table' => 'puntos',
                    'data' => array(
                        'datum' => $punto['datum'],
                        'mgrs' => $punto['mgrs'],
                        'utm_fuso' => $punto['fuso'],
                        'utm_sur' => $punto['utm_sur'] ? 1 : 0,
                        'utm_x' => $punto['utm_x'],
                        'utm_y' => $punto['utm_y'],
                        'll' => $punto['ll'],
                        'latitude' => $latLong['lat'],
                        'lonxitude' => $latLong['lng'],
                        'tipo' => $tipoFichaPunto
                    ),
                    'relate' => array(
                        array(
                            'table' => 'puntos_tipos',
                            'conditions' => array(
                                'numero' => $tipoPunto
                            )
                        ),
                        array(
                            'table' => 'avistamentos',
                            'conditions' => array(
                                'id' => $id_avistamento
                            )
                        ),
                        array(
                            'table' => 'importacions',
                            'limit' => 1,
                            'conditions' => array(
                                'id' => $importacion['id']
                            )
                        ),
                        array(
                            'table' => 'datums',
                            'limit' => 1,
                            'conditions' => array(
                                'url' => $punto['datum']
                            )
                        )
                    )
                );

                $ok = $Db->insert($query);

                if (empty($ok)) {
                    $resultado['erros'][$code] = $resultado['erros'][$code] ?: array();
                    $resultado['erros'][$code][] = __('Ocorreu un erro o crear o punto "%s". %s', join(', ', $punto) ,'<p>' . join('</p><p>', $Errors->getList()));
                    continue;
                }
                
                if ($tipoPunto === 1 || $tipoPunto === 2) {
            
                    $tableGis = 'gis_polygons';
                    $field = 'polygon';
                    
                    $polygonPoints = Coordinates::getCentroidCorners($latLong['lat'], $latLong['lng'], $tipoPunto == 1 ? 1000: 10000);
                
                    $polygonString = array();
                    
                    foreach($polygonPoints as $polPoint) {
                        $polygonString[] = $polPoint['latitude'] . ' ' . $polPoint['longitude'];
                    }
                    
                    $polygonString[] = $polygonPoints[0]['latitude'] . ' ' . $polygonPoints[0]['longitude'];
                    $shape = "POLYGON((" . join(', ', $polygonString) . "))";
                    
                } else {
                    
                    $tableGis = 'gis_points';
                    $field = 'point';
                    $shape = "POINT(" . $latLong['lat']  . " " . $latLong['lng'] . ")";
                }
                
                $res = $Db->queryResult("
                    insert into " . $tableGis . "
                    (id_puntos, " . $field . ")
                    values
                    (" . $ok . ", GEOMFROMTEXT('" . $shape . "'))
                ");

                $resultado['puntos']++;
            }
            
        } catch(Exeption $e) {
            $resultado['erros'][$code] = $resultado['erros'][$code] ?: array();
            $resultado['erros'][$code][] = __('Ocorreu un erro %s', $e->getMessage());
            continue;
        }
    }
}
