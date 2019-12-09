<?php
defined('ANS') or die();

if (!$table || !$id || !$action) {
    return false;
}

$relate = array(
    array(
        'table' => $table,
        'conditions' => array(
            'id' => $id
        )
    ),
    array(
        'table' => 'usuarios',
        'name' => 'autor',
        'conditions' => array(
            'id' => $user['id']
        )
    )
);

if ($table2 && $id2) {
    $relate[] = array(
        'table' => $table2,
        'conditions' => array(
            'id' => $id2
        )
    );
}

$Db->insert(array(
    'table' => 'logs',
    'data' => array(
        'related_table' => $table,
        'related_id' => (is_array($id) ? implode(',', $id) : $id),
        'related_table2' => $table2,
        'related_id2' => (is_array($id2) ? implode(',', $id2) : $id2),
        'date' => date('Y-m-d H:i:s'),
        'ip' => ip(),
        'action' => $action,
        'public' => (($public === null) ? 1 : 0)
    ),
    'relate' => $relate
));
