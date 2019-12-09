<?php
defined('ANS') or die();

if (empty($Vars->var['csv'])) {
    return false;
}

$csv = unserialize(base64_decode($Vars->var['csv']));

if (empty($csv)) {
    return false;
}

if (!is_array($Vars->var['escollidas'])) {
    $Vars->message(__('Non se seleccionou ningunha fila para importar'), 'ko');
    return false;
}

set_time_limit(0);
ini_set('max_execution_time', 0);
ini_set('memory_limit', '1024M');

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

$erros = array();
$cnt_especies = $cnt_avistamentos = $cnt_puntos;

foreach ($Vars->var['escollidas'] as $i) {
    if (!isset($csv[$i])) {
        continue;
    }

    $fila = $csv[$i];

    if (empty($fila['especie']) || empty($fila['fichas'])) {
        continue;
    }

    ++$cnt_especies;

    foreach ($fila['fichas'] as $ficha) {

        $checksum = md5(serialize($ficha));

        $existe = $Db->select(array(
            'table' => 'avistamentos',
            'fields' => 'id',
            'conditions' => array(
                'checksum' => $checksum
            )
        ));

        if ($existe) {
            continue;
        }

        $id_avistamento = $Db->insert(array(
            'table' => 'avistamentos',
            'data' => array(
                'url' => $fila['especie']['nome'],
                'nome' => $fila['especie']['nome'],
                'localidade' => $ficha['localidade'],
                'referencia' => $ficha['tipo_referencia'],
                'colector' => $ficha['colector'],
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
                        'id' => $fila['especie']['id']
                    )
                )
            )
        ));

        if (empty($id_avistamento)) {
            $erros[] = implode('', $Errors->getList());
            continue;
        }

        ++$cnt_avistamentos;

        foreach ($ficha['puntos'] as $punto) {
            if ($punto['utm_x'] && $punto['utm_y']) {
                $utm_x = $punto['utm_x'];
                $utm_y = $punto['utm_y'];

                if (stristr($punto['datum'], 'ed50') || strstr($punto['datum'], '1950')) {
                    $utm_x -= 87.987;
                    $utm_y -= 108.639;
                }

                $GP = new \Math\GeographicPoint\UTM($utm_x, $utm_y, $punto['fuso'], $punto['datum']);

                $GP = $GP->toLatitudeLongitude();
            } else if ($punto['ll']) {
                $GP = new \Math\GeographicPoint\LatitudeLongitude($punto['ll'], '', $punto['datum']);
            } else if ($punto['lat'] && $punto['long']) {
                $GP = new \Math\GeographicPoint\LatitudeLongitude($punto['lat'], $punto['long'], $punto['datum']);
            } else if ($punto['mgrs']) {
                $GP = new \Math\GeographicPoint\MGRS($punto['mgrs'], $punto['datum']);

                if (($GP = $GP->toTM()) === false) {
                    continue;
                }

                $GP = $GP->toLatitudeLongitude();
            } else {
                continue;
            }

            $latitude = round($GP->getLatitude(), 14);
            $lonxitude = round($GP->getLongitude(), 14);

            $query = array(
                'table' => 'puntos',
                'data' => array(
                    'datum' => $punto['datum'],
                    'mgrs' => $punto['mgrs'],
                    'utm_fuso' => $punto['fuso'],
                    'utm_x' => $punto['utm_x'],
                    'utm_y' => $punto['utm_y'],
                    'll' => $punto['ll'],
                    'latitude' => $latitude,
                    'lonxitude' => $lonxitude
                ),
                'relate' => array(
                    array(
                        'table' => 'puntos_tipos',
                        'conditions' => array(
                            'id' => $ficha['tipo']
                        )
                    ),
                    array(
                        'table' => 'avistamentos',
                        'conditions' => array(
                            'id' => $id_avistamento
                        )
                    )
                )
            );

            $ok = $Db->insert($query);

            if (empty($ok)) {
                $erros[] = implode('', $Errors->getList());
                continue;
            }

            ++$cnt_puntos;
        }
    }
}

if (empty($erros)) {
    $message = __('Todos os rexistros foron procesados correctamente:');
    $message .= ' '.__('%s avistamentos de %s especies e %s puntos.', $cnt_avistamentos, $cnt_especies, $cnt_puntos);
    $message .= ' '.__('Estes avistamentos serán visibles tanto na web como no teu perfil en canto sexan procesados.');
} else {
    $message = __('Ocorreu un erro procesando algúns rexistros e non se gardaron os datos:');
    $message .= '<p>'.implode('</p><p>', $errors).'</p>';
    $message .= __('Revise o arquivo e volvao a intentar de novo');
}

$Vars->message($message, $errors ? 'ko' : 'ok');

return false;
