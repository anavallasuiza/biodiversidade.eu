<?php
defined('ANS') or die();

if (empty($user)) {
    $Vars->message(__('Sentímolo, pero este contido é só accesible para usuarios rexistrados.'), 'ko');
    referer(path(''));
}

if (isset($url) && ($url !== $Vars->var['url'])) {
    $Vars->var['url'] = $url;
}

include ($Data->file('acl-avistamento-editar.php'));

$Data->execute('acl-action.php', array('action' => 'avistamento-eliminar'));

$Data->execute('actions|sub-backups.php', array(
    'table' => 'avistamentos',
    'id' => $avistamento['id'],
    'action' => 'eliminar',
    'content' => $avistamento
));

$puntos = $Db->select(array(
    'table' => 'puntos',
    'conditions' => array(
        'avistamentos.id' => $avistamento['id']
    )
));

$idsPuntos = arrayKeyValues($puntos, 'id');

$res = $Db->queryResult("
    delete from gis_points
    where id_puntos in (" . join(",", $idsPuntos) .")
");

$res = $Db->queryResult("
    delete from gis_polygons
    where id_puntos in (" . join(",", $idsPuntos) .")
");

$Db->delete(array(
    'table' => 'puntos',
    'conditions' => array(
        'avistamentos.id' => $avistamento['id']
    )
));

$Db->delete(array(
    'table' => 'avistamentos',
    'limit' => 1,
    'conditions' => array(
        'id' => $avistamento['id']
    )
));

$Vars->message(__('O avistamento foi eliminado correctamente.'), 'ok');

redirect(path('catalogo', 'mapa'));
