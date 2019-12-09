<?php
defined('ANS') or die();

use Eu\Biodiversidade\FerramentaAreas;

include ($Data->file('acl-iniciativa.php'));

$lastSyncDate = \DateTime::createFromFormat('d-m-Y H:i:s', $iniciativa['data_observacions']);

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
    $urlsEspeciesArredores = array_unique(arrayKeyValues($avistamentosArredores, 'especie_url'));

    $avistamentosAsociados = $Db->select(array(
        'table' => 'avistamentos',
        'limit' => 500,
        'conditions' => array(
            'activo' => 1,
            'iniciativas.id' => $iniciativa['id']
        ),
        'add_tables' => array(
            'especie' => array(
                'table' => 'especies',
                'limit' => 1
            )
        )
    ));

    $idsAvAsociados = arrayKeyValues($avistamentosAsociados, 'id');
    $especies = arrayKeyValues($avistamentosAsociados, 'especie');
    $urlsEspeciesAsociadas = array_unique(arrayKeyValues($especies, 'url'));

    $idsEngadir = array_diff($idsAvArredores, $idsAvAsociados);
    $idsEliminar = array_diff($idsAvAsociados, $idsAvArredores);

    $urlsEspeciesEngadir = array_diff($urlsEspeciesArredores, $urlsEspeciesAsociadas);
    $urlsEspeciesEliminar = array_diff($urlsEspeciesAsociadas, $urlsEspeciesArredores);

    if (count($idsEngadir) > 0) {
        $Db->relate(array(
            'tables' => array(
                array(
                    'table' => 'iniciativas',
                    'limit' => 1,
                    'conditions' => array(
                        'id' => $iniciativa['id']
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
    }

    if (count($idsEliminar) > 0) {
        $Db->unrelate(array(
            'tables' => array(
                array(
                    'table' => 'iniciativas',
                    'limit' => 1,
                    'conditions' => array(
                        'id' => $iniciativa['id']
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
    }

    if (count($urlsEspeciesEngadir) > 0) {
        $Db->relate(array(
            'tables' => array(
                array(
                    'table' => 'iniciativas',
                    'limit' => 1,
                    'conditions' => array(
                        'id' => $iniciativa['id']
                    )
                ),
                array(
                    'table' => 'especies',
                    'conditions' => array(
                        'activo' => 1,
                        'url' => $urlsEspeciesEngadir
                    )
                )
            )
        ));
    }

    if (count($urlsEspeciesEliminar) > 0) {
        $Db->unrelate(array(
            'tables' => array(
                array(
                    'table' => 'iniciativas',
                    'limit' => 1,
                    'conditions' => array(
                        'id' => $iniciativa['id']
                    )
                ),
                array(
                    'table' => 'especies',
                    'conditions' => array(
                        'activo' => 1,
                        'url' => $urlsEspeciesEliminar
                    )
                )
            )
        ));
    }

    $Db->update(array(
        'table' => 'iniciativas',
        'conditions' => array(
            'id' => $iniciativa['id']
        ),
        'limit' => 1,
        'data' => array(
            'data_observacions' => date('Y-m-d H:i:s')
        )
    ));

}

die();