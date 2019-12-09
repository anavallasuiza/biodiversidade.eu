<?php
defined('ANS') or die();

if (empty($conditions) || empty($limit)) {
    return array();
}

$query = array(
    'table' => 'backups',
    'fields' => '*',
    'limit' => $limit,
    'sort' => 'id DESC',
    'conditions' => $conditions,
    'add_tables' => array(
        array(
            'table' => 'usuarios',
            'name' => 'autor',
            'fields' => '*',
            'limit' => 1
        )
    )
);

if (isset($autor) && ($autor !== true)) {
    unset($query['add_tables'][0]);
}

if ($fields) {
    $query['fields'] = $fields;
}

if ($add_tables && is_array($add_tables)) {
    $query['add_tables'] = array_merge($query['add_tables'], $add_tables);
}

if ($limit) {
    $query['limit'] = intval($limit);
}

if ($cache && CACHE) {
    $query['cache'] = $cache;
}

if ($sort) {
    $query['sort'] = $sort;
}

if ($group) {
    $query['group'] = $group;
}

if ($offset) {
    $query['offset'] = $offset;
}

if ($page) {
    $query['pagination'] = array(
        'map' => 10,
        'page' => $page
    );
}

$backups = $Db->select($query);

if (empty($backups)) {
    return $backups;
}

if ($limit === 1) {
    $backups = array($backups);
}

foreach ($backups as &$backup) {
    $backup[$backup['related_table']] = $Db->select(array(
        'table' => $backup['related_table'],
        'fields' => '*',
        'limit' => 1,
        'conditions' => array(
            'id' => $backup['related_id']
        )
    ));

    if (empty($backup['related_table2'])) {
        continue;
    }

    $backup[$backup['related_table2']] = $Db->select(array(
        'table' => $backup['related_table2'],
        'fields' => '*',
        'limit' => 1,
        'conditions' => array(
            'id' => $backup['related_id2']
        )
    ));
}

unset($backup);

if ($limit === 1) {
    $backup = $backup[0];
}

return $backups;
