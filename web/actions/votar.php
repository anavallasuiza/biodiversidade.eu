<?php
defined('ANS') or die();

$voto = (integer)$Vars->var['voto'];

if (empty($user) || ($voto < 1) || ($voto > 5)) {
    $Vars->message(__('Precisas estar logueado para poder realizar esta acción'), 'ko');
    return false;
}

$Config->load('routes2tables.php');

$relations = array_keys($Db->tables['votos']->getRelations());
$relation = preg_replace('/[^a-z_]/', '', $Config->routes2tables[ROUTE_0]);

if (empty($relation) || !in_array($relation['table'], $relations)) {
    $Vars->message(__('Parece que este contido non está dispoñible neste momento'), 'ko');
    return false;
}

$row = $Db->select(array(
    'table' => $relation['table'],
    'fields' => array('votos_contador', 'votos_valoracion'),
    'limit' => 1,
    'conditions' => array(
        'activo' => 1,
        $relation['url'] => $Vars->var['url']
    ),
    'add_tables' => array(
        array(
            'table' => 'votos',
            'limit' => 1,
            'conditions' => array(
                'usuarios.id' => $user['id']
            )
        )
    )
));

if (empty($row)) {
    $Vars->message(__('Parece que este contido non está dispoñible neste momento'), 'ko');
    return false;
}

if ($row['votos']) {
    $Vars->message(__('Sentímolo, pero só é posible votar unha vez'), 'ko');
    return false;
}

$id_votos = $Db->insert(array(
    'table' => 'votos',
    'data' => array(
        'ip' => ip(),
        'data' => date('Y-m-d H:i:s'),
        'voto' => $voto
    ),
    'relate' => array(
        array(
            'table' => $relation['table'],
            'limit' => 1,
            'conditions' => array(
                'id' => $row['id']
            )
        ),
        array(
            'table' => 'usuarios',
            'limit' => 1,
            'conditions' => array(
                'id' => $user['id']
            )
        )
    )
));

if (empty($id_votos)) {
    $Vars->message($Errors->getList(), 'ko');
    return false;
}

$Db->update(array(
    'table' => $relation['table'],
    'data' => array(
        'votos_contador' => (++$row['votos_contador']),
        'votos_valoracion' => ($row['votos_valoracion'] + $voto),
        'votos_media' => round(($row['votos_valoracion'] + $voto) / $row['votos_contador'], 2)
    ),
    'conditions' => array(
        'id' => $row['id']
    ),
    'limit' => 1
));

$Data->execute('actions|sub-logs.php', array(
    'table' => $relation['table'],
    'id' => $row['id'],
    'action' => 'votar'
));

$Vars->message(__('Moitas gracias polo teu voto'), 'ok');

return true;
