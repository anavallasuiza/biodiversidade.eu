<?php
defined('ANS') or die();

include ($Data->file('acl-nota.php'));

if ($nota['puntos']) {
    foreach ($nota['puntos'] as &$punto) {
        if (empty($punto['mgrs']) || intval($punto['latitude'])) {
            continue;
        }

        $centroide = getMGRSCentroid($punto['mgrs']);
        $tipo = getMGRSCentroidType(trim($punto['mgrs']));

        $latLong = Coordinates::mgrsToLatLong($centroide, 'WGS84');

        $punto['latitude'] = round($latLong['lat'], 14);
        $punto['lonxitude'] = round($latLong['lng'], 14);

        $Db->update(array(
            'table' => 'puntos',
            'limit' => 1,
            'data' => array(
                'latitude' => $punto['latitude'],
                'lonxitude' => $punto['lonxitude']
            ),
            'conditions' => array(
                'id' => $punto['id']
            )
        ));
    }

    unset($punto);
}

$Html->meta('title', $nota['titulo']);
