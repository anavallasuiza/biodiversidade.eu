<?php
defined('ANS') or die();

if (empty($user)) {
    $Vars->message(__('Sentímolo, pero este contido é só accesible para usuarios rexistrados.'), 'ko');
    referer(path(''));
}

include ($Data->file('acl-blog-editar.php'));

$Data->execute('acl-action.php', array('action' => 'blog-eliminar'));

$Data->execute('actions|sub-backups.php', array(
    'table' => 'blogs',
    'id' => $blog['id'],
    'action' => 'eliminar',
    'content' => $blog
));

$Db->delete(array(
    'table' => 'blogs_posts',
    'conditions' => array(
        'blogs.id' => $blog['id']
    )
));

$Db->delete(array(
    'table' => 'blogs',
    'limit' => 1,
    'conditions' => array(
        'id' => $blog['id']
    )
));

$Vars->message(__('O blog e todos os seus posts foron eliminados correctamente.'), 'ok');

redirect(path('blogs'));