<?php
defined('ANS') or die();

use Eu\Biodiversidade\FerramentaAreas;

include ($Data->file('acl-rota.php'));

$lastSyncDate = \DateTime::createFromFormat('d-m-Y H:i:s', $rota['data_observacions']);

$sync = false;

if ($lastSyncDate === false) {
    $sync = true;
} else {
    $dateDiff = $lastSyncDate->diff(new \DateTime());

    if ($dateDiff->days >= 1) {
        $sync = true;
    }
}

if ($sync) {
    if ($Vars->var['boxes']) {
        $idsAvArredores = array();
        $idsXeolocalizacionsArredores = array(
            'puntos' => array(),
            'centroides1' => array(),
            'centroides10' => array()
        );

        foreach ($Vars->var['boxes'] as $box) {
            $data = array('points' => array());
            $data['points'][0] = array('lat' => $box['ne']['lat'], 'lng' => $box['ne']['lng']);
            $data['points'][1] = array('lat' => $box['sw']['lat'], 'lng' => $box['ne']['lng']);
            $data['points'][2] = array('lat' => $box['sw']['lat'], 'lng' => $box['sw']['lng']);
            $data['points'][3] = array('lat' => $box['ne']['lat'], 'lng' => $box['sw']['lng']);

            $avistamentos = FerramentaAreas::getAvistamentos($data);

            $idsAvArredores = array_merge($idsAvArredores, arrayKeyValues($avistamentos, 'id'));

            $xeolocalizacions = FerramentaAreas::getPuntos($data);

            $idsXeolocalizacionsArredores['puntos'] = array_merge($idsXeolocalizacionsArredores['puntos'], arrayKeyValues($xeolocalizacions['puntos'], 'id'));
            $idsXeolocalizacionsArredores['centroides1'] = array_merge($idsXeolocalizacionsArredores['centroides1'], arrayKeyValues($xeolocalizacions['centroides1'], 'id'));
            $idsXeolocalizacionsArredores['centroides10'] = array_merge($idsXeolocalizacionsArredores['centroides10'], arrayKeyValues($xeolocalizacions['centroides10'], 'id'));
        }

        $idsAvArredores = array_unique($idsAvArredores);
    } else {
        $avistamentosArredores = FerramentaAreas::getAvistamentos($Vars->var);

        $idsAvArredores = arrayKeyValues($avistamentosArredores, 'id');

        $xeolocalizacions = FerramentaAreas::getPuntos($Vars->var);

        $idsXeolocalizacionsArredores['puntos'] = arrayKeyValues($xeolocalizacions['puntos'], 'id');
        $idsXeolocalizacionsArredores['centroides1'] = arrayKeyValues($xeolocalizacions['centroides1'], 'id');
        $idsXeolocalizacionsArredores['centroides10'] = arrayKeyValues($xeolocalizacions['centroides10'], 'id');
    }

    $avistamentosAsociados = $Db->select(array(
        'table' => 'avistamentos',
        'limit' => 500,
        'conditions' => array(
            'activo' => 1,
            'rotas.id' => $rota['id']
        ),
        'add_tables' => array(
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
            )
        )
    ));

    $idsAvAsociados = arrayKeyValues($avistamentosAsociados, 'id');
    $idsXeolocalizacionsAsociadas['puntos'] = arrayKeyValues($avistamentosAsociados['puntos'], 'id');
    $idsXeolocalizacionsAsociadas['centroides1'] = arrayKeyValues($avistamentosAsociados['centroides1'], 'id');
    $idsXeolocalizacionsAsociadas['centroides10'] = arrayKeyValues($avistamentosAsociados['centroides10'], 'id');

    $idsEngadir = array_diff($idsAvArredores, $idsAvAsociados);
    $idsEliminar = array_diff($idsAvAsociados, $idsAvArredores);

    $idsPuntosEngadir = array_diff($idsXeolocalizacionsArredores['puntos'], $idsXeolocalizacionsAsociadas['puntos']);
    $idsPuntosEliminar = array_diff($idsXeolocalizacionsAsociadas['puntos'], $idsXeolocalizacionsArredores['puntos']);
    $idsCentroides1Engadir = array_diff($idsXeolocalizacionsArredores['centroides1'], $idsXeolocalizacionsAsociadas['centroides1']);
    $idsCentroides1Eliminar = array_diff($idsXeolocalizacionsAsociadas['centroides1'], $idsXeolocalizacionsArredores['centroides1']);
    $idsCentroides10Engadir = array_diff($idsXeolocalizacionsArredores['centroides10'], $idsXeolocalizacionsAsociadas['centroides10']);
    $idsCentroides10Eliminar = array_diff($idsXeolocalizacionsAsociadas['centroides10'], $idsXeolocalizacionsArredores['centroides10']);

    if (count($idsEngadir) > 0) {
        $Db->relate(array(
            'tables' => array(
                array(
                    'table' => 'rotas',
                    'limit' => 1,
                    'conditions' => array(
                        'id' => $rota['id']
                    )
                ),
                array(
                    'table' => 'avistamentos',
                    'conditions' => array(
                        'activo' => 1,
                        'id' => $idsEngadir
                    )
                )
            )
        ));

        $Db->relate(array(
            'tables' => array(
                array(
                    'table' => 'rotas',
                    'limit' => 1,
                    'conditions' => array(
                        'id' => $rota['id']
                    )
                ),
                array(
                    'table' => 'especies',
                    'conditions' => array(
                        'activo' => 1,
                        'avistamentos.id' => $idsEngadir
                    )
                )
            )
        ));
    }

    if (count($idsEliminar) > 0) {
        $Db->unrelate(array(
            'tables' => array(
                array(
                    'table' => 'rotas',
                    'limit' => 1,
                    'conditions' => array(
                        'id' => $rota['id']
                    )
                ),
                array(
                    'table' => 'avistamentos',
                    'conditions' => array(
                        'activo' => 1,
                        'id' => $idsEliminar
                    )
                )
            )
        ));

        $Db->unrelate(array(
            'tables' => array(
                array(
                    'table' => 'rotas',
                    'limit' => 1,
                    'conditions' => array(
                        'id' => $rota['id']
                    )
                ),
                array(
                    'table' => 'especies',
                    'conditions' => array(
                        'activo' => 1,
                        'avistamentos.id' => $idsEliminar
                    )
                )
            )
        ));
    }

    if (count($idsPuntosEngadir) > 0) {
        $Db->relate(array(
            'tables' => array(
                array(
                    'table' => 'rotas',
                    'limit' => 1,
                    'conditions' => array(
                        'id' => $rota['id']
                    )
                ),
                array(
                    'table' => 'puntos',
                    'conditions' => array(
                        'id' => $idsPuntosEngadir
                    )
                )
            )
        ));
    }

    if (count($idsPuntosEliminar) > 0) {
        $Db->unrelate(array(
            'tables' => array(
                array(
                    'table' => 'rotas',
                    'limit' => 1,
                    'conditions' => array(
                        'id' => $rota['id']
                    )
                ),
                array(
                    'table' => 'puntos',
                    'conditions' => array(
                        'id' => $idsPuntosEliminar
                    )
                )
            )
        ));
    }

    if (count($idsCentroides1Engadir) > 0) {
        $Db->relate(array(
            'tables' => array(
                array(
                    'table' => 'rotas',
                    'limit' => 1,
                    'conditions' => array(
                        'id' => $rota['id']
                    )
                ),
                array(
                    'table' => 'puntos',
                    'conditions' => array(
                        'id' => $idsCentroides1Engadir
                    )
                )
            )
        ));
    }

    if (count($idsCentroides1Eliminar) > 0) {
        $Db->unrelate(array(
            'tables' => array(
                array(
                    'table' => 'rotas',
                    'limit' => 1,
                    'conditions' => array(
                        'id' => $rota['id']
                    )
                ),
                array(
                    'table' => 'puntos',
                    'conditions' => array(
                        'id' => $idsCentroides1Eliminar
                    )
                )
            )
        ));
    }

    if (count($idsCentroides10Engadir) > 0) {
        $Db->relate(array(
            'tables' => array(
                array(
                    'table' => 'rotas',
                    'limit' => 1,
                    'conditions' => array(
                        'id' => $rota['id']
                    )
                ),
                array(
                    'table' => 'puntos',
                    'conditions' => array(
                        'id' => $idsCentroides10Engadir
                    )
                )
            )
        ));
    }

    if (count($idsCentroides10Eliminar) > 0) {
        $Db->unrelate(array(
            'tables' => array(
                array(
                    'table' => 'rotas',
                    'limit' => 1,
                    'conditions' => array(
                        'id' => $rota['id']
                    )
                ),
                array(
                    'table' => 'puntos',
                    'conditions' => array(
                        'id' => $idsCentroides10Eliminar
                    )
                )
            )
        ));
    }

    $Db->update(array(
        'table' => 'rotas',
        'conditions' => array(
            'id' => $rota['id']
        ),
        'limit' => 1,
        'data' => array(
            'data_observacions' => date('Y-m-d H:i:s')
        )
    ));
}

die();