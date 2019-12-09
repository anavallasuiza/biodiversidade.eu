<?php
defined('ANS') or die();

if (empty($user)) {
    $Vars->message(__('Precisas estar logueado para poder realizar esta acción'), 'ko');
    return false;
}

$Data->execute('acl-action.php', array('action' => 'rota-validar'));

$rotas = $Vars->var['rotas'];

if (empty($rotas['id'])) {
    return true;
}

$Db->update(array(
    'table' => 'rotas',
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
        'id' => $rotas['id']
    )
));

foreach ($rotas['id'] as $id) {
    $Data->execute('actions|sub-logs.php', array(
        'table' => 'rotas',
        'id' => $id,
        'action' => 'validar',
        'public' => 0
    ));
}

$Vars->message(__('As rotas foron validadas correctamente.'), 'ok');

return true;
