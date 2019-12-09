<?php
defined('ANS') or die();

if (empty($user)) {
    return false;
}

include ($Data->file('acl-blog-editar.php'));
include ($Data->file('acl-post-editar.php'));

$action = $post ? 'update' : 'insert';

$query = array(
    'table' => 'blogs_posts',
    'data' => array(
        'url' => $Vars->var['posts']['titulo'],
        'titulo' => $Vars->var['posts']['titulo'],
        'texto' => $Vars->var['posts']['texto'],
        'data' => ($post['data'] ?: date('Y-m-d H:i:s')),
        'activo' => 1
    ),
    'limit' => 1,
    'conditions' => array(
        'id' => $post['id']
    ),
    'relate' => array(
        array(
            'table' => 'blogs',
            'limit' => 1,
            'conditions' => array(
                'id' => $blog['id']
            )
        ),
        array(
            'table' => 'usuarios',
            'name' => 'autor',
            'limit' => 1,
            'conditions' => array(
                'id' => ($post['usuarios_autor']['id'] ?: $user['id'])
            )
        )
    )
);

$id_post = $Db->$action($query);

if (empty($id_post)) {
    $Vars->message($Errors->getList(), 'ko');
    return false;
}

if ($action === 'insert') {
    $Data->execute('actions|sub-logs.php', array(
        'table' => 'blogs_posts',
        'id' => $id_post,
        'action' => 'crear'
    ));

    $Db->update(array(
        'table' => 'blogs',
        'data' => array(
            'data_actualizado' => date('Y-m-d H:i:s')
        ),
        'limit' => 1,
        'conditions' => array(
            'id' => $blog['id']
        )
    ));

    $Vars->message(__('O post foi creado correctamente'), 'ok');
} else {
    $Data->execute('actions|sub-backups.php', array(
        'table' => 'blogs_posts',
        'id' => $post['id'],
        'action' => 'editar',
        'content' => $post
    ));

    $Vars->message(__('O post foi actualizado correctamente'), 'ok');
}

$post = $Db->select(array(
    'table' => 'blogs_posts',
    'fields' => 'url',
    'limit' => 1,
    'conditions' => array(
        'id' => ($post['id'] ?: $id_post)
    )
));

$Data->execute('actions|sub-imaxes.php', array(
    'table' => 'blogs_posts',
    'id' => $post['id'],
    'imaxes' => $Vars->var['imaxes']
));

if ($action === 'insert') {
    $Data->execute('actions|mail.php', array(
        'log' => array(
            'action' => 'crear',
            'table' => 'blogs_posts',
            'id' => $id_post,
        ),
        'vixiantes' => array(
            'blogs(vixiar).id' => $blog['id']
        ),
        'text' => array(
            'code' => 'mail-post-novo',
            'blog' => $blog['titulo'],
            'post' => $Vars->var['posts']['titulo'],
            'link' => absolutePath('post', $blog['url'], $post['url'])
        )
    ));
}

redirect(path('post', $blog['url'], $post['url']));
