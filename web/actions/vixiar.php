<?php
defined('ANS') or die();

if (empty($user)) {
    $Vars->message(__('Precisas estar logueado para poder realizar esta acción'), 'ko');
    return false;
}

switch (ROUTE_0) {
    case 'avistamento':
    case 'especie':
    case 'ameaza':
    case 'iniciativa':
    case 'rota':
    case 'espazo':
    case 'blog':
        include ($Data->file('acl-'.ROUTE_0.'.php'));

        $table = ROUTE_0.'s';
        $related = ${ROUTE_0};

        break;

    default:
        return false;
}

$action = $related['vixiar'] ? 'unrelate' : 'relate';

$Db->$action(array(
    'name' => 'vixiar',
    'tables' => array(
        array(
            'table' => $table,
            'conditions' => array(
                'id' => $related['id']
            )
        ),
        array(
            'table' => 'usuarios',
            'conditions' => array(
                'id' => $user['id']
            )
        )
    )
));

if ($action === 'relate') {
    $Vars->message(__('Acabamos de engadirte a lista de vixiancia.'), 'ok');

    $Data->execute('actions|sub-logs.php', array(
        'table' => $table,
        'id' => $related['id'],
        'action' => 'vixiar'
    ));
} else {
    $Vars->message(__('Saiches a lista de vixiancia.'), 'ok');
}

return true;
