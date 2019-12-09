<?php
defined('ANS') or die();

if (empty($table) || empty($id)) {
    return false;
}

$taboaPuntos = $pois ? 'pois' : 'puntos';

$idPuntos = array();
$idFormas = array();

foreach((array)$shapes as $shape) {
    if (empty($shape['points'])) {
        continue;
    }

    if ($shape['type'] === 'marker') {
        $query = array(
            'table' => $taboaPuntos,
            'data' => array(
                'latitude' => $shape['points'][0]['lat'],
                'lonxitude' => $shape['points'][0]['lng'],

            ),
            'conditions' => array(
                'id' => $shape['code']
            )
        );

        if (empty($pois)) {
            $query['data']['datum'] = 'wgs84';
            $query['data']['tipo'] = 'latlong';
        } else {
            $query['data']['nome'] = $shape['nome'];
            $query['data']['texto'] = $shape['texto'];
        }

        $action = $shape['id'] ? 'update' : 'insert';
        $success = $Db->$action($query);

        if (empty($success)) {
            $Vars->message($Errors->getList(), 'ko');
            return false;
        }

        $shape['id'] = $shape['id'] ?: $success;

        $Db->relate(array(
            'tables' => array(
                array(
                    'table' => $taboaPuntos,
                    'conditions' => array(
                        'id' => $shape['id']
                    )
                ),
                array(
                    'table' => $table,
                    'conditions' => array(
                        'id' => $id
                    )
                )
            )
        ));

        if ($pois) {
            $Db->relate(array(
                'tables' => array(
                    array(
                        'table' => $taboaPuntos,
                        'conditions' => array(
                            'id' => $shape['id']
                        )
                    ),
                    array(
                        'table' => 'pois_tipos',
                        'conditions' => array(
                            'url' => $shape['tipo']
                        )
                    )
                )
            ));
        }

        $idPuntos[] = $shape['id'];

        continue;
    }

    $idForma = $shape['code'];

    if (empty($shape['code'])) {
        $idForma = $Db->insert(array(
            'table' => 'formas',
            'data' => array(
                'tipo' => $shape['type']
            )
        ));

        if (empty($idForma)) {
            $Vars->message($Errors->getList(), 'ko');
            return false;
        }

        $Db->relate(array(
            'tables' => array(
                array(
                    'table' => 'formas',
                    'conditions' => array(
                        'id' => $idForma
                    )
                ),
                array(
                    'table' => $table,
                    'conditions' => array(
                        'id' => $id
                    )
                )
            )
        ));
    } else {
        $Db->delete(array(
            'table' => 'puntos',
            'conditions' => array(
                'formas.id' => $idForma
            )
        ));
    }

    $idFormas[] = $idForma;

    foreach ($shape['points'] as $i => $point) {
        $id_puntos = $Db->insert(array(
            'table' => 'puntos',
            'data' => array(
                'datum' => 'wgs84',
                'latitude' => $point['lat'],
                'lonxitude' => $point['lng'],
                'tipo' => 'latlong',
                'orde' => $i
            )
        ));

        if (empty($id_puntos)) {
            $Vars->message($Errors->getList(), 'ko');
            return false;
        }

        $Db->relate(array(
            'tables' => array(
                array(
                    'table' => 'puntos',
                    'conditions' => array(
                        'id' => $id_puntos
                    )
                ),
                array(
                    'table' => 'formas',
                    'conditions' => array(
                        'id' => $idForma
                    )
                )
            )
        ));

        $idPuntos[] = $id_puntos;
    }
}

$Db->delete(array(
    'table' => $taboaPuntos,
    'conditions' => array(
        'id !=' => $idPuntos,
        $table.'.id' => $id
    )
));

$Db->delete(array(
    'table' => 'formas',
    'conditions' => array(
        'id !=' => $idFormas,
        $table.'.id' => $id
    )
));

return true;
