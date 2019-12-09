<?php
defined('ANS') or die();

$Data->execute('acl-action.php', array('action' => 'especie-restaurar'));

$backup = $Db->select(array(
    'table' => 'backups',
    'limit' => 1,
    'conditions' => array(
        'id' => $Vars->var['id'],
        'related_table' => 'especies'
    ),
    'add_tables' => array(
        array(
            'table' => 'especies',
            'fields' => 'url',
            'limit' => 1
        )
    )
));

if (empty($backup)) {
    $Vars->message(__('O backup especificado non existe'), 'ko');
    return false;
}

$Vars->var['url'] = $backup['especies']['url'];

include ($Data->file('acl-especie.php'));

$content = unserialize(base64_decode($backup['content']));

$query = array(
    'table' => 'especies',
    'data' => array(),
    'conditions' => array(
        'id' => $especie['id']
    )
);

$fields = array_keys($Db->getTable('especies')->formats);

unset($fields['id']);

foreach ($fields as $field) {
    if (!strstr($field, 'id_')) {
        $query['data'][$field] = $content[$field];
    }
}

$success = $Db->update($query);

if (empty($success)) {
    $Vars->message(__('Ocorreu un erro o restaurar a versión da especie.'), 'ko');
    return false;
}

$Data->execute('actions|sub-backups.php', array(
    'table' => 'especies',
    'id' => $especie['id'],
    'action' => 'restaurar',
    'content' => $especie
));

$Vars->message(__('A versión da especie foi restaurada correctamente.'), 'ok');

redirect(path('especie', $content['url']));
