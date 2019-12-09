<?php
defined('ANS') or die();

if (empty($user)) {
    return false;
}

include ($Data->file('acl-blog-editar.php'));

if ($Vars->var['engadir']) {
    $Db->relate(array(
        'name' => 'autor',
        'tables' => array(
            array(
                'table' => 'usuarios',
                'conditions' => array(
                    'nome-url' => explode(',', $Vars->var['engadir'])
                )
            ),
            array(
                'table' => 'blogs',
                'limit' => 1,
                'conditions' => array(
                    'id' => $blog['id']
                )
            )
        )
    ));

    $Data->execute('actions|mail.php', array(
        'vixiantes' => array(
            'nome-url' => explode(',', $Vars->var['engadir'])
        ),
        'text' => array(
            'code' => 'mail-engadido-editor-blog',
            'title' => $blog['titulo'],
            'link' => absolutePath('blog', $blog['url'])
        )
    ));
}

if ($Vars->var['quitar']) {
    $Db->unrelate(array(
        'name' => 'autor',
        'tables' => array(
            array(
                'table' => 'usuarios',
                'conditions' => array(
                    'nome-url' => $Vars->var['quitar'],
                    'nome-url !=' => $user['nome']['url']
                )
            ),
            array(
                'table' => 'blogs',
                'limit' => 1,
                'conditions' => array(
                    'id' => $blog['id']
                )
            )
        )
    ));
}

$Data->execute('actions|sub-logs.php', array(
    'table' => 'blogs',
    'id' => $blog['id'],
    'action' => 'editores'
));

$Vars->message(__('Actualizouse correctamente a lista de usuarios editores deste blog'), 'ok');

redirect(path());
