<?php
defined('ANS') or die();

if (empty($limit)) {
    return array();
}

$vixiados = $Db->select(array(
    'table' => 'logs',
    'fields' => '*',
    'conditions' => array(
        'action' => 'vixiar',
        'usuarios(autor).id' => $user['id']
    )
));

if (empty($vixiados)) {
    return array();
}

$lista = array();

foreach ($vixiados as $vixiado) {
    if (!is_array($lista[$vixiado['related_table']])) {
        $lista[$vixiado['related_table']] = array();
    }

    $lista[$vixiado['related_table']][] = $vixiado['related_id'];
}

$logs = array();
$base = array(
    'table' => 'logs',
    'fields' => '*',
    'limit' => (($page === null) ? 5 : 0),
    'sort' => 'id DESC',
    'group' => 'related_id',
    'conditions' => array(
        'action' => 'editar'
    ),
    'add_tables' => array(
        array(
            'table' => 'usuarios',
            'name' => 'autor',
            'fields' => '*',
            'limit' => 1
        )
    )
);

foreach ($lista as $table => $ids) {
    $query = $base;
    $query['conditions']['usuarios(autor).id !='] = $user['id'];
    $query['conditions']['related_table'] = $table;
    $query['conditions']['related_id'] = array_unique($ids);
    $query['add_tables'][] = array(
        'table' => $table,
        'fields' => '*',
        'limit' => 1
    );

    $logs = array_merge($logs, $Db->select($query));
}

usort($logs, function ($a, $b) {
    return ($a['id'] > $b['id']) ? -1 : 1;
});

if ($page === null) {
    return array_slice($logs, 0, $limit);
}

$Data->pagination = $Db->getPagination(array(
    'limit' => $limit,
    'total' => count($logs),
    'pagination' => array(
        'map' => 10,
        'page' => $page
    )
));

return array_slice($logs, $page * $limit, $limit);
