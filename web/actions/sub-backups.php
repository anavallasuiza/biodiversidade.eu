<?php
defined('ANS') or die();

if (!$table || !$id || !$action || !$content) {
    return false;
}

$Data->execute('actions|sub-logs.php', array(
    'table' => $table,
    'id' => $id,
    'action' => $action,
    'public' => $public
));

$last = $Db->select(array(
    'table' => 'backups',
    'fields' => 'checksum',
    'limit' => 1,
    'sort' => 'id DESC',
    'conditions' => array(
        $table.'.id' => $id
    )
));

$serialized = serialize($content);
$checksum = md5($serialized);

if ($checksum != $last['checksum']) {
    $Db->insert(array(
        'table' => 'backups',
        'data' => array(
            'related_table' => $table,
            'related_id' => $id,
            'date' => date('Y-m-d H:i:s'),
            'ip' => ip(),
            'action' => $action,
            'checksum' => $checksum,
            'content' => base64_encode($serialized)
        ),
        'relate' => array(
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
        )
    ));
}