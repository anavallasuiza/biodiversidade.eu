<?php
defined('ANS') or die();

if (empty($user)) {
    return false;
}

include ($Data->file('acl-equipo-editar.php'));

if ($Vars->var['aceptar']) {
    $Db->relate(array(
        'tables' => array(
            array(
                'table' => 'usuarios',
                'conditions' => array(
                    'nome-url' => $Vars->var['aceptar']
                )
            ),
            array(
                'table' => 'equipos',
                'limit' => 1,
                'conditions' => array(
                    'id' => $equipo['id']
                )
            )
        )
    ));

    $Db->unrelate(array(
        'name' => 'solicitudes',
        'tables' => array(
            array(
                'table' => 'usuarios',
                'conditions' => array(
                    'nome-url' => $Vars->var['aceptar']
                )
            ),
            array(
                'table' => 'equipos',
                'limit' => 1,
                'conditions' => array(
                    'id' => $equipo['id']
                )
            )
        )
    ));

    $Data->execute('actions|mail.php', array(
        'vixiantes' => array(
            'nome-url' => $Vars->var['aceptar']
        ),
        'text' => array(
            'code' => 'mail-aceptado-equipo',
            'title' => $equipo['titulo'],
            'link' => absolutePath('equipo', $equipo['url'])
        )
    ));
}

if ($Vars->var['rexeitar']) {
    $Db->unrelate(array(
        'name' => 'solicitudes',
        'tables' => array(
            array(
                'table' => 'usuarios',
                'conditions' => array(
                    'nome-url' => $Vars->var['rexeitar']
                )
            ),
            array(
                'table' => 'equipos',
                'limit' => 1,
                'conditions' => array(
                    'id' => $equipo['id']
                )
            )
        )
    ));
}

if ($Vars->var['engadir']) {
    $Db->relate(array(
        'tables' => array(
            array(
                'table' => 'usuarios',
                'conditions' => array(
                    'nome-url' => explode(',', $Vars->var['engadir'])
                )
            ),
            array(
                'table' => 'equipos',
                'limit' => 1,
                'conditions' => array(
                    'id' => $equipo['id']
                )
            )
        )
    ));

    $Data->execute('actions|mail.php', array(
        'vixiantes' => array(
            'nome-url' => explode(',', $Vars->var['engadir'])
        ),
        'text' => array(
            'code' => 'mail-engadido-equipo',
            'title' => $equipo['titulo'],
            'link' => absolutePath('equipo', $equipo['url'])
        )
    ));
}

if ($Vars->var['quitar']) {
    $Db->unrelate(array(
        'tables' => array(
            array(
                'table' => 'usuarios',
                'conditions' => array(
                    'nome-url' => $Vars->var['quitar'],
                    'nome-url !=' => $user['nome']['url']
                )
            ),
            array(
                'table' => 'equipos',
                'limit' => 1,
                'conditions' => array(
                    'id' => $equipo['id']
                )
            )
        )
    ));
}

$Data->execute('actions|sub-logs.php', array(
    'table' => 'equipos',
    'id' => $equipo['id'],
    'action' => 'editores'
));

$Vars->message(__('Actualizouse correctamente a lista de usuarios editores deste equipo'), 'ok');

redirect(path());
