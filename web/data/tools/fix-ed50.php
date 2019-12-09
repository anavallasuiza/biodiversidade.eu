<?php
defined('ANS') or die();

$puntos = $Db->select(array(
    'table' => 'puntos',
    'conditions' => array(
        'datum' => 'ED50',
        'tipo' => 'utm'
    )
));

$updatedPoints = 0;

foreach ($puntos as $punto) {
    // Esta foi a corrección de metros aplicada para ED50 Spain & Portugal
    /*$punto['utm_x'] = $punto['utm_x'] - 132;
    $punto['utm_y'] = $punto['utm_y'] - 115;*/

    $latLong = Coordinates::utmToLatLong($punto['utm_x'], $punto['utm_y'], $punto['utm_fuso'], ($punto['utm_sur'] ? 'S' : 'N'), strtoupper($punto['datum']));

    if ($punto['latitude'] != round($latLong['lat'], 14) || $punto['lonxitude'] != round($latLong['lng'], 14)) {
        /*$Db->update(array(
            'table' => 'puntos',
            'conditions' => array(
                'id' => $punto['id']
            ),
            'data' => array(
                'latitude' => round($latLong['lat'], 14),
                'lonxitude' => round($latLong['lng'], 14)
            )
        ));*/

        $updatedPoints++;
    }
}

die($updatedPoints . " puntos actualizados");
