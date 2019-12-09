<?php
defined('ANS') or die();

if (empty($user)) {
    $Vars->message(__('Precisas estar logueado para poder realizar esta acción'), 'ko');
    return false;
}

$Data->execute('acl-action.php', array('action' => 'avistamento-validar'));

$avistamentos = $Vars->var['avistamentos'];

if (empty($avistamentos['id'])) {
    return true;
}

$Db->update(array(
    'table' => 'avistamentos',
    'data' => array(
        'validado' => 1
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
        'id' => $avistamentos['id']
    )
));

foreach ($avistamentos['id'] as $id) {
    $Data->execute('actions|sub-logs.php', array(
        'table' => 'avistamentos',
        'id' => $id,
        'action' => 'validar',
        'public' => 0
    ));
}

$Vars->message(__('Os avistamentos foron validados correctamente.'), 'ok');

return true;
