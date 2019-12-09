<?php
defined('ANS') or die();

if (empty($user)) {
    $Vars->message(__('Sentímolo, pero este contido é só accesible para usuarios rexistrados.'), 'ko');
    referer(path(''));
}

include ($Data->file('acl-blog-editar.php'));
include ($Data->file('acl-post-editar.php'));

$Data->execute('acl-action.php', array('action' => 'post-eliminar'));

$Data->execute('actions|sub-backups.php', array(
    'table' => 'blogs_posts',
    'id' => $post['id'],
    'action' => 'eliminar',
    'content' => $post
));

$Db->delete(array(
    'table' => 'blogs_posts',
    'limit' => 1,
    'conditions' => array(
        'id' => $post['id']
    )
));

$Vars->message(__('O post foi eliminado correctamente.'), 'ok');

redirect(path('blog', $blog['url']));