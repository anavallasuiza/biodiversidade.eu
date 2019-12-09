<?php
defined('ANS') or die();

if (empty($user)) {
    $Vars->message(__('Precisas estar logueado para poder realizar esta acción'), 'ko');
    return false;
}

$Config->load('routes2tables.php');

$relations = array_keys($Db->tables['comentarios']->getRelations());
$relation = $Config->routes2tables[ROUTE_0];

if (empty($relation) || !in_array($relation['table'], $relations)) {
    $Vars->message(__('Parece que este contido non está dispoñible neste momento'), 'ko');
    return false;
}

$row = $Db->select(array(
    'table' => $relation['table'],
    'fields' => '*',
    'limit' => 1,
    'conditions' => array(
        'activo' => 1,
        $relation['url'] => $Vars->var[$relation['var'] ?: 'url']
    )
));

if (empty($row)) {
    $Vars->message(__('Parece que este contido non está dispoñible neste momento'), 'ko');
    return false;
}

$id_comentarios = $Db->insert(array(
    'table' => 'comentarios',
    'data' => array(
        'texto' => $Vars->var['texto'],
        'data' => date('Y-m-d H:i:s'),
        'activo' => 1
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
            'name' => 'autor',
            'conditions' => array(
                'id' => $user['id']
            )
        )
    )
));

if (empty($id_comentarios)) {
    $Vars->message($Errors->getList(), 'ko');
    return false;
}

if ($relation['vixiar']) {
    $Data->execute('actions|mail.php', array(
        'log' => array(
            'action' => 'comentar',
            'table' => $relation['table'],
            'id' => $row['id']
        ),
        'vixiantes' => array(
            $relation['vixiar'] => $row['id']
        ),
        'text' => array(
            'code' => 'mail-comentario',
            'title' => ($row['titulo'] ?: $row['nome']),
            'comment' => $Vars->var['texto'],
            'user' => $user['nome']['title']
        )
    ));
}

$Data->execute('actions|sub-logs.php', array(
    'table' => 'comentarios',
    'id' => $id_comentarios,
    'table2' => $relation['table'],
    'id2' => $row['id'],
    'action' => 'comentar'
));

$Vars->message(__('Moitas gracias polo teu comentario'), 'ok');

return true;
