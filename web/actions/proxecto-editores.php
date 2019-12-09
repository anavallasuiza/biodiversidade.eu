<?php
defined('ANS') or die();

if (empty($user)) {
    return false;
}

include ($Data->file('acl-proxecto-editar.php'));

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
                'table' => 'proxectos',
                'limit' => 1,
                'conditions' => array(
                    'id' => $proxecto['id']
                )
            )
        )
    ));

    $Data->execute('actions|mail.php', array(
        'vixiantes' => array(
            'nome-url' => explode(',', $Vars->var['engadir'])
        ),
        'text' => array(
            'code' => 'mail-engadido-proxecto',
            'title' => $proxecto['titulo'],
            'link' => absolutePath('proxecto', $proxecto['url'])
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
                'table' => 'proxectos',
                'limit' => 1,
                'conditions' => array(
                    'id' => $proxecto['id']
                )
            )
        )
    ));
}

if ($Vars->var['solicitudes']) {
    $engadir = array_keys(array_filter($Vars->var['solicitudes']));

    if ($engadir) {
        $Db->relate(array(
            'tables' => array(
                array(
                    'table' => 'usuarios',
                    'conditions' => array(
                        'nome-url' => $engadir
                    )
                ),
                array(
                    'table' => 'proxectos',
                    'limit' => 1,
                    'conditions' => array(
                        'id' => $proxecto['id']
                    )
                )
            )
        ));
    }

    $Db->unrelate(array(
        'name' => 'solicitude',
        'tables' => array(
            array(
                'table' => 'usuarios',
                'conditions' => array(
                    'nome-url' => array_keys($Vars->var['solicitudes'])
                )
            ),
            array(
                'table' => 'proxectos',
                'limit' => 1,
                'conditions' => array(
                    'id' => $proxecto['id']
                )
            )
        )
    ));

    if ($engadir) {
        $Data->execute('actions|mail.php', array(
            'vixiantes' => array(
                'nome-url' => $engadir
            ),
            'text' => array(
                'code' => 'mail-engadido-proxecto',
                'title' => $proxecto['titulo'],
                'link' => absolutePath('proxecto', $proxecto['url'])
            )
        ));
    }
}

$Data->execute('actions|sub-logs.php', array(
    'table' => 'proxectos',
    'id' => $proxecto['id'],
    'action' => 'editores'
));

$Vars->message(__('Actualizouse correctamente a lista de usuarios editores deste proxecto'), 'ok');

redirect(path());
