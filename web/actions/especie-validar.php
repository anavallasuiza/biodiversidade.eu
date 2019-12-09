<?php
defined('ANS') or die();

if (empty($user)) {
    $Vars->message(__('Precisas estar logueado para poder realizar esta acción'), 'ko');
    return false;
}

$Data->execute('acl-action.php', array('action' => 'especie-validar'));

$especies = $Vars->var['especies'];

if (empty($especies['id'])) {
    return true;
}

$Db->update(array(
    'table' => 'especies',
    'data' => array(
        'validada' => 1
    ),
    'relate' => array(
        array(
            'table' => 'usuarios',
            'name' => 'validador',
            'limit' => 1,
            'conditions' => array(
                'id' => $user['id']
            )
        )
    ),
    'conditions' => array(
        'id' => $especies['id']
    )
));

foreach ($especies['id'] as $id) {
    $Data->execute('actions|sub-logs.php', array(
        'table' => 'especies',
        'id' => $id,
        'action' => 'validar',
        'public' => 0
    ));
}

$Vars->message(__('As especies foron validadas correctamente.'), 'ok');

return true;
