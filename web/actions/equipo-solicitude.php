<?php
defined('ANS') or die();

if (empty($user)) {
    $Vars->message(__('Sentímolo, pero este contido é só accesible para usuarios rexistrados.'), 'ko');
    referer(path(''));
}

include ($Data->file('acl-equipo.php'));

if (DENTRO) {
    return true;
}

$Db->relate(array(
    'name' => 'solicitudes',
    'tables' => array(
        array(
            'table' => 'equipos',
            'limit' => 1,
            'conditions' => array(
                'id' => $equipo['id']
            )
        ),
        array(
            'table' => 'usuarios',
            'limit' => 1,
            'conditions' => array(
                'id' => $user['id']
            )
        )
    )
));

if ($equipo['usuarios_autor']['notificacions']) {
    $Data->execute('actions|mail.php', array(
        'to' => $equipo['usuarios_autor']['usuario'],
        'subject' => __('O usuario %s solicitou a participación no equipo %s que ti xestionas', $user['nome']['title'], $equipo['titulo']),
        'text' => __('Podes xestionar o seu ingreso dende a %s', $user['name'], $Html->a(__('Xestion de membros'), absolutePath('editores-equipo', $equipo['url'])))
    ));
}

$Vars->message(__('A túa solicitude de membresía foi realizada correctamente'), 'ok');

return true;
