<?php
defined('ANS') or die();

use Eu\Biodiversidade\FerramentaAreas;

include ($Data->file('acl-espazo.php'));

$lastSyncDate = \DateTime::createFromFormat('d-m-Y H:i:s', $espazo['data_observacions']);

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
    $avistamentosArredores = FerramentaAreas::getAvistamentos($Vars->var);

    $idsAvArredores = arrayKeyValues($avistamentosArredores, 'id');

    $xeolocalizacions = FerramentaAreas::getPuntos($Vars->var);

    $idsXeolocalizacionsArredores['puntos'] = arrayKeyValues($xeolocalizacions['puntos'], 'id');
    $idsXeolocalizacionsArredores['centroides1'] = arrayKeyValues($xeolocalizacions['centroides1'], 'id');
    $idsXeolocalizacionsArredores['centroides10'] = arrayKeyValues($xeolocalizacions['centroides10'], 'id');


    $avistamentosAsociados = $Db->select(array(
        'table' => 'avistamentos',
        'limit' => 500,
        'conditions' => array(
            'activo' => 1,
            'espazos.id' => $espazo['id']
        ),
        'add_tables' => array(
            array(
                'table' => 'puntos',
                'conditions' => array(
                    'puntos_tipos.numero' => 4,
                    'espazos.id' => $espazo['id']
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
                    'espazos.id' => $espazo['id']
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
                    'espazos.id' => $espazo['id']
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
                    'espazos.id' => $espazo['id']
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
                    'table' => 'espazos',
                    'limit' => 1,
                    'conditions' => array(
                        'id' => $espazo['id']
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
                    'table' => 'espazos',
                    'limit' => 1,
                    'conditions' => array(
                        'id' => $espazo['id']
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
                    'table' => 'espazos',
                    'limit' => 1,
                    'conditions' => array(
                        'id' => $espazo['id']
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
                    'table' => 'espazos',
                    'limit' => 1,
                    'conditions' => array(
                        'id' => $espazo['id']
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
                    'table' => 'espazos',
                    'limit' => 1,
                    'conditions' => array(
                        'id' => $espazo['id']
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
                    'table' => 'espazos',
                    'limit' => 1,
                    'conditions' => array(
                        'id' => $espazo['id']
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
                    'table' => 'espazos',
                    'limit' => 1,
                    'conditions' => array(
                        'id' => $espazo['id']
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
                    'table' => 'espazos',
                    'limit' => 1,
                    'conditions' => array(
                        'id' => $espazo['id']
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
                    'table' => 'espazos',
                    'limit' => 1,
                    'conditions' => array(
                        'id' => $espazo['id']
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
                    'table' => 'espazos',
                    'limit' => 1,
                    'conditions' => array(
                        'id' => $espazo['id']
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
        'table' => 'espazos',
        'conditions' => array(
            'id' => $espazo['id']
        ),
        'limit' => 1,
        'data' => array(
            'data_observacions' => date('Y-m-d H:i:s')
        )
    ));
}

die();