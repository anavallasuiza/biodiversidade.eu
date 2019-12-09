<?php
defined('ANS') or die();

$puntos = $Db->select(array(
    'table' => 'puntos',
    'conditions' => array(
        'datum' => 'ED50',
        'tipo' => 'mgrs'
    )
));

$updatedPoints = 0;

foreach ($puntos as $punto) {
    $centroide = getMGRSCentroid($punto['mgrs']);
    $latLong = Coordinates::mgrsToLatLong($centroide, strtoupper($punto['datum']));

    if ($punto['latitude'] != round($latLong['lat'], 14) || $punto['lonxitude'] != round($latLong['lng'], 14)) {
        $Db->update(array(
            'table' => 'puntos',
            'conditions' => array(
                'id' => $punto['id']
            ),
            'data' => array(
                'latitude' => round($latLong['lat'], 14),
                'lonxitude' => round($latLong['lng'], 14)
            )
        ));

        $updatedPoints++;
    }
}

die($updatedPoints . " puntos actualizados");
