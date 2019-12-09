<?php
defined('ANS') or die();

$Data->execute('acl-action.php', array('action' => 'caderno-restaurar'));

$backup = $Db->select(array(
    'table' => 'backups',
    'limit' => 1,
    'conditions' => array(
        'id' => $Vars->var['id'],
        'related_table' => 'cadernos'
    ),
    'add_tables' => array(
        array(
            'table' => 'cadernos',
            'fields' => 'url',
            'limit' => 1,
            'add_tables' => array(
                array(
                    'table' => 'proxectos',
                    'fields' => 'url',
                    'limit' => 1
                )
            )
        )
    )
));

if (empty($backup)) {
    $Vars->message(__('O backup especificado non existe'), 'ko');
    return false;
}

$Vars->var['proxecto'] = $backup['cadernos']['proxectos']['url'];
$Vars->var['url'] = $backup['cadernos']['url'];

include ($Data->file('acl-proxecto.php'));
include ($Data->file('acl-caderno.php'));

$content = unserialize(base64_decode($backup['content']));

$query = array(
    'table' => 'cadernos',
    'data' => array(),
    'conditions' => array(
        'id' => $caderno['id']
    )
);

$fields = array_keys($Db->getTable('cadernos')->formats);

unset($fields['id']);

foreach ($fields as $field) {
    if (!strstr($field, 'id_')) {
        $query['data'][$field] = $content[$field];
    }
}

$success = $Db->update($query);

if (empty($success)) {
    $Vars->message(__('Ocorreu un erro o restaurar a versión da caderno.'), 'ko');
    return false;
}

$Data->execute('actions|sub-backups.php', array(
    'table' => 'cadernos',
    'id' => $caderno['id'],
    'action' => 'restaurar',
    'content' => $caderno
));

$Vars->message(__('A versión do caderno foi restaurada correctamente.'), 'ok');

redirect(path('caderno', $proxecto['url'], $content['url']));
